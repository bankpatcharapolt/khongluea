<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Strike_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        // CI3: get_instance() guaranteed after bootstrap
        $CI =& get_instance();
        $this->db = $CI->db;
    }

    protected string $table = 'giveaway_strikes';

    public function add(int $user_id, string $reason = 'no_show', ?int $reservation_id = NULL): void
    {
        $this->db->insert($this->table, [
            'user_id'        => $user_id,
            'reason'         => $reason,
            'reservation_id' => $reservation_id,
            'expires_at'     => date('Y-m-d H:i:s', strtotime('+30 days')),
        ]);

        // Update cached count on users table
        $this->db->query(
            'UPDATE users
                SET active_strikes = (
                    SELECT COUNT(*) FROM giveaway_strikes
                     WHERE user_id = ? AND expires_at > NOW())
              WHERE id = ?',
            [$user_id, $user_id]
        );
    }

    public function count_active(int $user_id): int
    {
        return (int)$this->db
            ->where('user_id', $user_id)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->count_all_results($this->table);
    }

    public function get_for_user(int $user_id): array
    {
        return $this->db
            ->select('s.*, r.booking_ref')
            ->from("{$this->table} s")
            ->join('giveaway_reservations r', 'r.id = s.reservation_id', 'left')
            ->where('s.user_id', $user_id)
            ->order_by('s.created_at', 'DESC')
            ->get()->result_array();
    }

    public function remove(int $strike_id): void
    {
        $strike = $this->db->get_where($this->table, ['id' => $strike_id])->row_array();
        if (!$strike) return;
        $this->db->delete($this->table, ['id' => $strike_id]);
        // Recalculate cached count
        $this->db->query(
            'UPDATE users
                SET active_strikes = (
                    SELECT COUNT(*) FROM giveaway_strikes
                     WHERE user_id = ? AND expires_at > NOW())
              WHERE id = ?',
            [$strike['user_id'], $strike['user_id']]
        );
    }
}
