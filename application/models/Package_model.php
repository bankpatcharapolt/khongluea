<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Package_model extends CI_Model {

    public function get_all_active(): array
    {
        return $this->db->where('is_active', 1)->order_by('sort_order', 'ASC')->get('premium_packages')->result_array();
    }

    public function get_by_id(int $id): ?array
    {
        $row = $this->db->get_where('premium_packages', ['id' => $id, 'is_active' => 1])->row_array();
        return $row ?: NULL;
    }

    public function get_all_admin(): array
    {
        return $this->db->order_by('sort_order', 'ASC')->get('premium_packages')->result_array();
    }

    public function create(array $data): int
    {
        $this->db->insert('premium_packages', $data);
        return $this->db->insert_id();
    }

    public function update(int $id, array $data): bool
    {
        return $this->db->where('id', $id)->update('premium_packages', $data);
    }

    /**
     * Activate a package for a user (deducts credits and logs subscription).
     */
    public function activate(int $user_id, int $package_id): bool
    {
        $this->load->model('User_model');
        $package = $this->get_by_id($package_id);
        if ( ! $package) return FALSE;

        $credits = $this->User_model->get_credits($user_id);
        if ($credits < $package['price_in_credits']) return FALSE; // insufficient credits

        $this->db->trans_start();

        $expires_at = NULL;
        if ($package['duration_days']) {
            $expires_at = date('Y-m-d H:i:s', strtotime("+{$package['duration_days']} days"));
        }

        $this->db->insert('user_packages', [
            'user_id'       => $user_id,
            'package_id'    => $package_id,
            'credits_spent' => $package['price_in_credits'],
            'expires_at'    => $expires_at,
        ]);

        // Deduct credits
        $this->User_model->adjust_credits($user_id, -$package['price_in_credits'], CREDIT_SPEND, $package_id, 'Package: ' . $package['name']);

        // Update premium status if it's a subscription package
        if ($package['duration_days'] && $package['max_listings'] !== NULL) {
            $this->db->where('id', $user_id)->update('users', [
                'premium_status'    => 1,
                'premium_expires_at' => $expires_at,
            ]);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Get active (non-expired) package subscription for a user.
     */
    public function get_active_for_user(int $user_id): ?array
    {
        $row = $this->db->select('user_packages.*, premium_packages.*')
            ->from('user_packages')
            ->join('premium_packages', 'premium_packages.id = user_packages.package_id')
            ->where('user_packages.user_id', $user_id)
            ->group_start()
                ->where('user_packages.expires_at IS NULL')
                ->or_where('user_packages.expires_at >=', date('Y-m-d H:i:s'))
            ->group_end()
            ->order_by('user_packages.created_at', 'DESC')
            ->limit(1)->get()->row_array();
        return $row ?: NULL;
    }
}
