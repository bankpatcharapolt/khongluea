<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Giveaway extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login(TRUE); // admin only
        $this->load->model([
            'Giveaway_listing_model',
            'Giveaway_reservation_model',
            'Strike_model',
        ]);
    }

    public function index(): void
    {
        $status   = $this->input->get('status') ?: 'active';
        $listings = $this->db
            ->select('gl.*, u.name AS donor_name, u.business_name,
                      (SELECT COUNT(*) FROM giveaway_reservations
                       WHERE listing_id=gl.id) AS total_reservations')
            ->from('giveaway_listings gl')
            ->join('users u', 'u.id = gl.donor_user_id')
            ->where('gl.status', $status)
            ->order_by('gl.created_at', 'DESC')
            ->limit(50)->get()->result_array();

        $this->_render('giveaway/index', [
            'title'    => 'Giveaway Listings',
            'listings' => $listings,
            'status'   => $status,
        ]);
    }

    public function strikes(): void
    {
        $strikes = $this->db
            ->select('s.*, u.name AS user_name, u.email,
                      r.booking_ref')
            ->from('giveaway_strikes s')
            ->join('users u', 'u.id = s.user_id')
            ->join('giveaway_reservations r', 'r.id = s.reservation_id', 'left')
            ->order_by('s.created_at', 'DESC')
            ->limit(100)->get()->result_array();

        $this->_render('giveaway/strikes', [
            'title'   => 'Strike Log',
            'strikes' => $strikes,
        ]);
    }

    public function remove_strike(int $id): void
    {
        $this->Strike_model->remove($id);
        $this->session->set_flashdata('success', 'ลบ strike แล้ว');
        redirect(site_url('admin/giveaway/strikes'));
    }

    public function delete_listing(int $id): void
    {
        $this->db->where('id', $id)->update('giveaway_listings', ['status' => 'deleted']);
        $this->session->set_flashdata('success', 'ลบประกาศแล้ว');
        redirect(site_url('admin/giveaway'));
    }

    private function _render(string $view, array $data = []): void
    {
        $this->load->view('layouts/admin_layout',
            array_merge($data, ['content_view' => 'admin/' . $view]));
    }
}
