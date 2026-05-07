<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Donor extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login();
        $this->load->model([
            'Giveaway_listing_model',
            'Giveaway_reservation_model',
            'Image_model',
            'Category_model',
        ]);
        $this->_require_donor();
    }

    // ── MY LISTINGS ───────────────────────────────────────
    public function my_listings(): void
    {
        $user     = current_user();
        $listings = $this->Giveaway_listing_model->get_by_donor($user['id']);

        $this->_render('donor/my_listings', [
            'title'    => 'รายการแจกของของฉัน',
            'listings' => $listings,
        ]);
    }

    // ── CREATE ────────────────────────────────────────────
    public function create(): void
    {
        $user = current_user();

        if ($this->input->method() === 'post') {
            // Daily donor listing cap
            $today = $this->Giveaway_listing_model->count_donor_active_today($user['id']);
            if ($today >= 10) {
                $this->session->set_flashdata('error', 'คุณลงประกาศได้สูงสุด 10 รายการต่อวัน');
                redirect(site_url('donor/create'));
                return;
            }

            $this->form_validation
                ->set_rules('title',        'ชื่อ',       'required|trim|max_length[200]')
                ->set_rules('description',  'รายละเอียด', 'trim')
                ->set_rules('quantity',     'จำนวน',      'required|integer|greater_than[0]')
                ->set_rules('pickup_address','ที่อยู่',   'required|trim')
                ->set_rules('pickup_start', 'เริ่มรับ',   'required')
                ->set_rules('pickup_end',   'สิ้นสุด',    'required');

            if ($this->form_validation->run() === FALSE) {
                $this->_show_create_form();
                return;
            }

            $listing_id = $this->Giveaway_listing_model->create([
                'donor_user_id'  => $user['id'],
                'category_id'    => $this->input->post('category_id') ?: NULL,
                'title'          => $this->input->post('title', TRUE),
                'description'    => $this->security->xss_clean($this->input->post('description')),
                'quantity_total' => (int)$this->input->post('quantity'),
                'pickup_address' => $this->input->post('pickup_address', TRUE),
                'pickup_lat'     => $this->input->post('pickup_lat') ?: NULL,
                'pickup_lng'     => $this->input->post('pickup_lng') ?: NULL,
                'pickup_start'   => $this->input->post('pickup_start'),
                'pickup_end'     => $this->input->post('pickup_end'),
            ]);

            // Upload images
            if (!empty($_FILES['images']['name'][0])) {
                $this->_upload_images($listing_id);
            }

            $this->session->set_flashdata('success', 'ลงประกาศแจกของสำเร็จแล้ว!');
            redirect(site_url('donor/listings'));
            return;
        }

        $this->_show_create_form();
    }

    // ── EDIT ──────────────────────────────────────────────
    public function edit(int $id): void
    {
        $user    = current_user();
        $listing = $this->Giveaway_listing_model->get_by_id($id);
        if (!$listing || (int)$listing['donor_user_id'] !== (int)$user['id']) show_404();

        if ($this->input->method() === 'post') {
            $data = [
                'title'          => $this->input->post('title', TRUE),
                'description'    => $this->security->xss_clean($this->input->post('description')),
                'pickup_address' => $this->input->post('pickup_address', TRUE),
                'pickup_lat'     => $this->input->post('pickup_lat') ?: NULL,
                'pickup_lng'     => $this->input->post('pickup_lng') ?: NULL,
                'pickup_end'     => $this->input->post('pickup_end'), // can only extend
                'status'         => $this->input->post('status'),
            ];
            $this->Giveaway_listing_model->update($id, $user['id'], $data);

            if (!empty($_FILES['images']['name'][0])) {
                $this->_upload_images($id);
            }

            $this->session->set_flashdata('success', 'อัปเดตประกาศแล้ว');
            redirect(site_url('donor/listings'));
            return;
        }

        $this->_render('donor/edit', [
            'title'      => 'แก้ไขประกาศ',
            'listing'    => $listing,
            'categories' => $this->Category_model->get_all_active(),
        ]);
    }

    // ── DELETE ────────────────────────────────────────────
    public function delete(int $id): void
    {
        if ($this->input->method() !== 'post') redirect(site_url('donor/listings'));
        $user = current_user();
        $this->Giveaway_listing_model->soft_delete($id, $user['id']);
        // Cancel all pending reservations for this listing
        $this->db
            ->where('listing_id', $id)
            ->where('status', 'pending')
            ->update('giveaway_reservations', [
                'status'        => 'cancelled',
                'cancelled_at'  => date('Y-m-d H:i:s'),
                'cancel_reason' => 'listing_deleted',
            ]);
        $this->session->set_flashdata('success', 'ลบประกาศแล้ว');
        redirect(site_url('donor/listings'));
    }

    // ── VERIFY PICKUP ─────────────────────────────────────
    public function verify(int $listing_id): void
    {
        $user    = current_user();
        $listing = $this->Giveaway_listing_model->get_by_id($listing_id);
        if (!$listing || (int)$listing['donor_user_id'] !== (int)$user['id']) show_404();

        if ($this->input->method() === 'post') {
            $reservation_id = (int)$this->input->post('reservation_id');
            $result = $this->Giveaway_reservation_model->donor_verify($reservation_id, $user['id']);

            if ($result['ok']) {
                // Send notification to receiver
                $res = $this->db->get_where('giveaway_reservations', ['id' => $reservation_id])->row_array();
                if ($res) {
                    $this->db->insert('notifications', [
                        'user_id'    => $res['receiver_id'],
                        'type'       => 'pickup_confirmed',
                        'title'      => 'รับของสำเร็จ!',
                        'body'       => 'ร้าน ' . ($listing['business_name'] ?: $listing['donor_name']) . ' ยืนยันการรับของแล้ว',
                        'is_read'    => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                }
                $this->session->set_flashdata('success', 'ยืนยันการรับของสำเร็จ!');
            } else {
                $msgs = [
                    'not_found_or_unauthorized' => 'ไม่พบการจองนี้',
                    'window_not_open'           => 'ยังไม่ถึงเวลารับของ',
                ];
                $this->session->set_flashdata('error', $msgs[$result['reason']] ?? 'เกิดข้อผิดพลาด');
            }
            redirect(site_url('donor/verify/' . $listing_id));
            return;
        }

        $pending = $this->Giveaway_reservation_model->get_pending_for_listing($listing_id);
        $this->_render('donor/verify', [
            'title'   => 'ยืนยันการรับของ — ' . htmlspecialchars($listing['title']),
            'listing' => $listing,
            'pending' => $pending,
        ]);
    }

    // ── PRIVATE ───────────────────────────────────────────
    private function _require_donor(): void
    {
        $user = current_user();
        if (!$user || !$user['is_donor']) {
            $this->session->set_flashdata('error', 'กรุณาสมัครเป็นผู้แจกของก่อน');
            redirect(site_url('account/settings'));
        }
    }

    private function _show_create_form(): void
    {
        $this->_render('donor/create', [
            'title'      => 'ลงประกาศแจกของ',
            'categories' => $this->Category_model->get_all_active(),
        ]);
    }

    private function _upload_images(int $listing_id): void
    {
        $files      = $_FILES['images'];
        $count      = count($files['name']);
        $upload_dir = rtrim(str_replace('\\', '/', FCPATH), '/') . '/uploads/giveaway/';

        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, TRUE);

        $existing  = count($this->Giveaway_listing_model->get_images($listing_id));
        $is_first  = ($existing === 0);
        $allowed   = ['jpg','jpeg','png','webp'];

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) continue;
            $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) continue;
            if ($files['size'][$i] > 2 * 1024 * 1024) continue; // 2MB

            $name = md5(uniqid(mt_rand(), TRUE)) . '.' . $ext;
            if (move_uploaded_file($files['tmp_name'][$i], $upload_dir . $name)) {
                $this->Giveaway_listing_model->add_image(
                    $listing_id,
                    'uploads/giveaway/' . $name,
                    $is_first && $i === 0
                );
            }
        }
    }

    private function _render(string $view, array $data = []): void
    {
        $this->load->view('layouts/frontend_layout',
            array_merge($data, ['content_view' => 'frontend/' . $view]));
    }
}
