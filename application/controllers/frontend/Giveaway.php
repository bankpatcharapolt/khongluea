<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Giveaway extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model([
            'Giveaway_listing_model',
            'Giveaway_reservation_model',
            'Category_model',
        ]);
    }

    // ── BROWSE ────────────────────────────────────────────
    public function index(): void
    {
        $filters = [
            'search'      => $this->input->get('q', TRUE),
            'category_id' => $this->input->get('category_id'),
            'lat'         => $this->input->get('lat'),
            'lng'         => $this->input->get('lng'),
            'radius_km'   => $this->input->get('radius') ?: 10,
        ];

        $page    = max(1, (int)$this->input->get('page'));
        $limit   = 16;
        $offset  = ($page - 1) * $limit;

        $listings = $this->Giveaway_listing_model->get_active($filters, $limit, $offset);
        $total    = $this->Giveaway_listing_model->count_active($filters);

        $this->load->library('pagination');
        $this->pagination->initialize([
            'base_url'    => site_url('giveaway'),
            'total_rows'  => $total,
            'per_page'    => $limit,
            'cur_tag_open' => '<span class="page-link active">',
            'cur_tag_close'=> '</span>',
        ]);

        $this->_render('giveaway/index', [
            'title'      => 'รับของฟรี — ของเหลือจากร้านค้า',
            'listings'   => $listings,
            'filters'    => $filters,
            'total'      => $total,
            'pagination' => $this->pagination->create_links(),
            'categories' => $this->Category_model->get_all_active(),
        ]);
    }

    // ── DETAIL ────────────────────────────────────────────
    public function view(int $id): void
    {
        $listing = $this->Giveaway_listing_model->get_by_id($id);
        if (!$listing) show_404();

        $this->Giveaway_listing_model->increment_views($id);

        $user       = current_user();
        $my_res     = NULL;
        if (is_logged_in() && $user) {
            $my_res = $this->db
                ->get_where('giveaway_reservations', [
                    'listing_id'  => $id,
                    'receiver_id' => $user['id'],
                ])->row_array();
        }

        $this->_render('giveaway/view', [
            'title'   => htmlspecialchars($listing['title']) . ' — แจกฟรี',
            'listing' => $listing,
            'my_res'  => $my_res,
        ]);
    }

    // ── RESERVE ───────────────────────────────────────────
    public function reserve(int $listing_id): void
    {
        require_login();
        if ($this->input->method() !== 'post') redirect(site_url('giveaway/' . $listing_id));

        $user   = current_user();
        $result = $this->Giveaway_reservation_model->reserve($listing_id, $user['id']);

        if (!$result['ok']) {
            $messages = [
                'daily_limit'   => 'คุณจองได้สูงสุด 2 รายการต่อวัน',
                'suspended'     => 'บัญชีของคุณถูกระงับชั่วคราวเนื่องจากไม่มารับของ',
                'not_available' => 'ของชิ้นนี้ไม่พร้อมให้รับแล้ว',
                'out_of_stock'  => 'ของหมดแล้ว',
                'window_closed' => 'เลยเวลารับของแล้ว',
                'own_listing'   => 'ไม่สามารถจองของที่ตัวเองลงไว้ได้',
                'db_error'      => 'เกิดข้อผิดพลาด กรุณาลองใหม่',
            ];
            $this->session->set_flashdata('error', $messages[$result['reason']] ?? 'เกิดข้อผิดพลาด');
            redirect(site_url('giveaway/view/' . $listing_id));
            return;
        }

        if ($result['reason'] === 'already_reserved') {
            redirect(site_url('giveaway/qr/' . $result['booking_ref']));
            return;
        }

        $this->session->set_flashdata('success', 'จองสำเร็จ! กรุณาแสดง QR code ตอนรับของ');
        redirect(site_url('giveaway/qr/' . $result['booking_ref']));
    }

    // ── QR CODE PAGE ──────────────────────────────────────
    public function qr(string $booking_ref): void
    {
        require_login();
        $user = current_user();

        $res = $this->Giveaway_reservation_model->get_by_booking_ref($booking_ref);
        if (!$res || (int)$res['receiver_id'] !== (int)$user['id']) show_404();

        $this->_render('giveaway/qr', [
            'title' => 'QR Code — ' . $booking_ref,
            'res'   => $res,
        ]);
    }

    // ── CANCEL ────────────────────────────────────────────
    public function cancel(int $reservation_id): void
    {
        require_login();
        if ($this->input->method() !== 'post') redirect(site_url('giveaway/my-reservations'));

        $user = current_user();
        $ok   = $this->Giveaway_reservation_model
            ->cancel_by_receiver($reservation_id, $user['id']);

        $msg = $ok ? ['success' => 'ยกเลิกการจองแล้ว'] : ['error' => 'ไม่สามารถยกเลิกได้'];
        $this->session->set_flashdata(key($msg), current($msg));
        redirect(site_url('giveaway/my-reservations'));
    }

    // ── MY RESERVATIONS ───────────────────────────────────
    public function my_reservations(): void
    {
        require_login();
        $user = current_user();
        $reservations = $this->Giveaway_reservation_model->get_by_receiver($user['id']);

        $this->_render('giveaway/my_reservations', [
            'title'        => 'การจองของฉัน',
            'reservations' => $reservations,
        ]);
    }

    // ── PRIVATE ───────────────────────────────────────────
    private function _render(string $view, array $data = []): void
    {
        $this->load->view('layouts/frontend_layout',
            array_merge($data, ['content_view' => 'frontend/' . $view]));
    }
}
