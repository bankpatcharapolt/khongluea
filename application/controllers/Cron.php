<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cron Controller
 * Run every 15 minutes via server cron:
 *   curl -s https://khongluea.com/cron/giveaway/expire
 * Or via CLI:
 *   php index.php cron giveaway_expire
 */
class Cron extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->_check_access();
        $this->load->model([
            'Giveaway_listing_model',
            'Giveaway_reservation_model',
            'Strike_model',
        ]);
    }

    // ── EXPIRE NO-SHOW RESERVATIONS ───────────────────────
    public function giveaway_expire(): void
    {
        $expired = $this->Giveaway_reservation_model->get_expired_pending();
        $count   = 0;

        foreach ($expired as $res) {
            $this->db->trans_start();

            // Mark as no_show
            $this->Giveaway_reservation_model->mark_no_show((int)$res['id']);

            // Return quantity to listing
            $this->db->query(
                'UPDATE giveaway_listings
                    SET quantity_left = quantity_left + 1,
                        status = IF(status = "completed", "active", status)
                  WHERE id = ? AND status IN ("active","completed","paused")',
                [$res['listing_id']]
            );

            // Apply no-show strike
            $this->Strike_model->add(
                (int)$res['receiver_id'],
                'no_show',
                (int)$res['id']
            );

            $this->db->trans_complete();

            if ($this->db->trans_status()) {
                $count++;
                log_message('info', "Cron expire: reservation #{$res['id']} → no_show, strike added to user #{$res['receiver_id']}");
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'  => 'ok',
                'expired' => $count,
                'ran_at'  => date('Y-m-d H:i:s'),
            ]));
    }

    // ── CLOSE EXPIRED LISTINGS ────────────────────────────
    public function giveaway_close(): void
    {
        $now = date('Y-m-d H:i:s');

        // Close listings past pickup_end that weren't fully claimed
        $result = $this->db->query(
            'UPDATE giveaway_listings
                SET status = "expired"
              WHERE pickup_end < ?
                AND status IN ("active","paused")',
            [$now]
        );

        $affected = $this->db->affected_rows();
        log_message('info', "Cron close: {$affected} listings marked expired");

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status'  => 'ok',
                'closed'  => $affected,
                'ran_at'  => $now,
            ]));
    }

    // ── BLOCK NON-CRON REQUESTS ───────────────────────────
    private function _check_access(): void
    {
        $is_cli = (php_sapi_name() === 'cli');
        $token  = $this->input->get('token') ?: $this->input->server('HTTP_X_CRON_TOKEN');
        $valid  = defined('CRON_SECRET') ? ($token === CRON_SECRET) : FALSE;

        if (!$is_cli && !$valid) {
            $this->output->set_status_header(403)
                ->set_output('Forbidden')->_display();
            exit;
        }
    }
}
