<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Giveaway_reservation_model extends CI_Model {

    protected string $table = 'giveaway_reservations';

    // ── ATOMIC RESERVE ────────────────────────────────────
    /**
     * Reserve a slot atomically using SELECT … FOR UPDATE.
     * Prevents race conditions when multiple users reserve simultaneously.
     *
     * @return array ['ok'=>bool, 'reason'=>string, 'booking_ref'=>string]
     */
    public function reserve(int $listing_id, int $receiver_id): array
    {
        // ── Pre-flight checks (fast, outside transaction) ─

        // 1. Daily reservation limit
        $today_count = (int)$this->db
            ->where('receiver_id', $receiver_id)
            ->where('DATE(reserved_at)', date('Y-m-d'))
            ->where_in('status', ['pending', 'confirmed'])
            ->count_all_results($this->table);

        if ($today_count >= 2) {
            return ['ok' => FALSE, 'reason' => 'daily_limit'];
        }

        // 2. Strike check — 3+ active strikes = suspended
        $active_strikes = (int)$this->db
            ->where('user_id', $receiver_id)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->count_all_results('giveaway_strikes');

        if ($active_strikes >= 3) {
            return ['ok' => FALSE, 'reason' => 'suspended'];
        }

        // 3. Not already reserved this listing (idempotent)
        $existing = $this->db->get_where($this->table, [
            'listing_id'  => $listing_id,
            'receiver_id' => $receiver_id,
        ])->row_array();
        if ($existing && in_array($existing['status'], ['pending', 'confirmed'])) {
            return ['ok' => TRUE, 'reason' => 'already_reserved', 'booking_ref' => $existing['booking_ref']];
        }

        // ── Atomic transaction ────────────────────────────
        $this->db->trans_start();

        // Lock the listing row to prevent concurrent updates
        $row = $this->db->query(
            'SELECT id, quantity_left, status, pickup_start, pickup_end, donor_user_id
               FROM giveaway_listings
              WHERE id = ? FOR UPDATE',
            [$listing_id]
        )->row_array();

        if (!$row || $row['status'] !== 'active') {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'reason' => 'not_available'];
        }
        if ((int)$row['quantity_left'] < 1) {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'reason' => 'out_of_stock'];
        }
        if ($row['pickup_end'] < date('Y-m-d H:i:s')) {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'reason' => 'window_closed'];
        }
        if ((int)$row['donor_user_id'] === $receiver_id) {
            $this->db->trans_rollback();
            return ['ok' => FALSE, 'reason' => 'own_listing'];
        }

        // Decrement quantity atomically — mark completed if last unit
        $this->db->query(
            'UPDATE giveaway_listings
                SET quantity_left = quantity_left - 1,
                    status = IF(quantity_left - 1 = 0, "completed", "active")
              WHERE id = ?',
            [$listing_id]
        );

        // Generate unique booking reference & QR token
        $booking_ref = $this->_gen_booking_ref();
        $qr_token    = hash('sha256', $booking_ref . $listing_id . $receiver_id . ENCRYPTION_KEY);

        $this->db->insert($this->table, [
            'listing_id'  => $listing_id,
            'receiver_id' => $receiver_id,
            'booking_ref' => $booking_ref,
            'qr_token'    => $qr_token,
            'quantity'    => 1,
            'status'      => 'pending',
        ]);

        $reservation_id = $this->db->insert_id();
        $this->db->trans_complete();

        if (!$this->db->trans_status()) {
            return ['ok' => FALSE, 'reason' => 'db_error'];
        }

        return [
            'ok'             => TRUE,
            'reason'         => 'reserved',
            'booking_ref'    => $booking_ref,
            'qr_token'       => $qr_token,
            'reservation_id' => $reservation_id,
        ];
    }

    // ── CANCEL (by receiver) ──────────────────────────────
    public function cancel_by_receiver(int $reservation_id, int $receiver_id, string $reason = 'user_cancelled'): bool
    {
        $res = $this->db->get_where($this->table, [
            'id'          => $reservation_id,
            'receiver_id' => $receiver_id,
            'status'      => 'pending',
        ])->row_array();

        if (!$res) return FALSE;

        $this->db->trans_start();

        $this->db->where('id', $reservation_id)
            ->update($this->table, [
                'status'        => 'cancelled',
                'cancelled_at'  => date('Y-m-d H:i:s'),
                'cancel_reason' => $reason,
            ]);

        // Return quantity to listing
        $this->db->query(
            'UPDATE giveaway_listings
                SET quantity_left = quantity_left + 1,
                    status = "active"
              WHERE id = ? AND status IN ("active","completed")',
            [$res['listing_id']]
        );

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // ── DONOR VERIFY PICKUP ───────────────────────────────
    public function donor_verify(int $reservation_id, int $donor_user_id): array
    {
        // Validate donor owns this listing's reservation
        $res = $this->db
            ->select('r.*, l.donor_user_id, l.pickup_start, l.pickup_end')
            ->from("{$this->table} r")
            ->join('giveaway_listings l', 'l.id = r.listing_id')
            ->where('r.id', $reservation_id)
            ->where('l.donor_user_id', $donor_user_id)
            ->where('r.status', 'pending')
            ->get()->row_array();

        if (!$res) {
            return ['ok' => FALSE, 'reason' => 'not_found_or_unauthorized'];
        }
        if (date('Y-m-d H:i:s') < $res['pickup_start']) {
            return ['ok' => FALSE, 'reason' => 'window_not_open'];
        }

        $this->db->where('id', $reservation_id)
            ->update($this->table, [
                'donor_verified' => 1,
                'status'         => 'confirmed',
                'confirmed_at'   => date('Y-m-d H:i:s'),
            ]);

        return ['ok' => TRUE];
    }

    // ── QR LOOKUP ─────────────────────────────────────────
    public function get_by_booking_ref(string $booking_ref): ?array
    {
        return $this->db
            ->select('r.*, l.title AS listing_title, l.pickup_address,
                      l.pickup_start, l.pickup_end, l.donor_user_id,
                      u.name AS donor_name, u.business_name,
                      u.phone AS donor_phone')
            ->from("{$this->table} r")
            ->join('giveaway_listings l', 'l.id = r.listing_id')
            ->join('users u',             'u.id = l.donor_user_id')
            ->where('r.booking_ref', $booking_ref)
            ->get()->row_array() ?: NULL;
    }

    public function get_by_qr_token(string $token): ?array
    {
        return $this->db
            ->get_where($this->table, ['qr_token' => $token])
            ->row_array() ?: NULL;
    }

    // ── RECEIVER HISTORY ──────────────────────────────────
    public function get_by_receiver(int $receiver_id, int $limit = 20, int $offset = 0): array
    {
        return $this->db
            ->select('r.*, l.title AS listing_title, l.pickup_address,
                      l.pickup_start, l.pickup_end,
                      u.business_name AS donor_business,
                      (SELECT image_path FROM giveaway_listing_images
                       WHERE listing_id=l.id AND is_primary=1 LIMIT 1) AS listing_image')
            ->from("{$this->table} r")
            ->join('giveaway_listings l', 'l.id = r.listing_id')
            ->join('users u',             'u.id = l.donor_user_id')
            ->where('r.receiver_id', $receiver_id)
            ->order_by('r.reserved_at', 'DESC')
            ->limit($limit, $offset)
            ->get()->result_array();
    }

    // ── DONOR RESERVATIONS (for verify page) ─────────────
    public function get_pending_for_listing(int $listing_id): array
    {
        return $this->db
            ->select('r.*, u.name AS receiver_name, u.phone AS receiver_phone')
            ->from("{$this->table} r")
            ->join('users u', 'u.id = r.receiver_id')
            ->where('r.listing_id', $listing_id)
            ->where('r.status', 'pending')
            ->order_by('r.reserved_at', 'ASC')
            ->get()->result_array();
    }

    // ── CRON: EXPIRE NO-SHOWS ─────────────────────────────
    public function get_expired_pending(): array
    {
        return $this->db->query(
            'SELECT r.id, r.receiver_id, r.listing_id, r.booking_ref
               FROM giveaway_reservations r
               JOIN giveaway_listings l ON l.id = r.listing_id
              WHERE r.status = "pending"
                AND l.pickup_end < NOW()'
        )->result_array();
    }

    public function mark_no_show(int $id): void
    {
        $this->db->where('id', $id)->update($this->table, [
            'status'        => 'no_show',
            'cancelled_at'  => date('Y-m-d H:i:s'),
            'cancel_reason' => 'auto_expired',
        ]);
    }

    // ── PRIVATE ───────────────────────────────────────────
    private function _gen_booking_ref(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // no confusing chars O,0,I,1
        do {
            $ref = 'KL-';
            for ($i = 0; $i < 6; $i++) {
                $ref .= $chars[random_int(0, strlen($chars) - 1)];
            }
            $exists = $this->db->get_where($this->table, ['booking_ref' => $ref])->num_rows();
        } while ($exists > 0);
        return $ref;
    }
}
