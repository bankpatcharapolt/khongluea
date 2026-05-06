<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login();
        $this->load->model('User_model');
    }

    // ───── Settings ─────────────────────────────
    public function settings(): void
    {
        $user = current_user();
        $data = ['title' => 'ตั้งค่าบัญชี', 'user' => $user, 'content_view' => 'frontend/account/settings'];
        $this->load->view('layouts/frontend_layout', $data);
    }

    public function settings_save(): void
    {
        if ($this->input->method() !== 'post') {
            redirect(site_url('account/settings'));
        }
        $user = current_user();
        $this->form_validation->set_rules('name',  'ชื่อ', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('phone', 'เบอร์โทร', 'trim|max_length[20]');
        $this->form_validation->set_rules('city',  'จังหวัด', 'trim|max_length[100]');
        $this->form_validation->set_rules('bio',   'แนะนำตัว','trim|max_length[500]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' | '));
            redirect(site_url('account/settings'));
            return;
        }

        $update = [
            'name'  => $this->input->post('name', TRUE),
            'phone' => $this->input->post('phone', TRUE),
            'city'  => $this->input->post('city',  TRUE),
            'bio'   => $this->input->post('bio',   TRUE),
        ];

        // เปลี่ยนรหัสผ่าน (ถ้ากรอก)
        $new_pass = $this->input->post('new_password');
        $cur_pass = $this->input->post('current_password');
        if ($new_pass) {
            $current_user_data = $this->User_model->get_by_id($user['id']);
            if ( ! $this->User_model->verify_password($cur_pass, $current_user_data['password_hash'])) {
                $this->session->set_flashdata('error', 'รหัสผ่านปัจจุบันไม่ถูกต้อง');
                redirect(site_url('account/settings'));
                return;
            }
            if (strlen($new_pass) < 8) {
                $this->session->set_flashdata('error', 'รหัสผ่านใหม่ต้องมีอย่างน้อย 8 ตัวอักษร');
                redirect(site_url('account/settings'));
                return;
            }
            $update['password'] = $new_pass;
        }

        // อัปโหลด avatar (ถ้ามี)
        if ( ! empty($_FILES['avatar']['name'])) {
            $this->load->library('upload', [
                'upload_path'   => FCPATH . 'uploads/avatars/',
                'allowed_types' => 'jpg|jpeg|png|webp',
                'max_size'      => 1024,
                'encrypt_name'  => TRUE,
            ]);
            if ($this->upload->do_upload('avatar')) {
                $img = $this->upload->data();
                $update['avatar'] = 'uploads/avatars/' . $img['file_name'];
            }
        }

        $this->User_model->update($user['id'], $update);

        // อัปเดต session
        $fresh = $this->User_model->get_by_id($user['id']);
        $this->session->set_userdata('user_data', $fresh);

        $this->session->set_flashdata('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        redirect(site_url('account/settings'));
    }

    // ───── Notifications ─────────────────────────
    public function notifications(): void
    {
        $user  = current_user();
        $notifs = $this->db
            ->where('user_id', $user['id'])
            ->order_by('created_at', 'DESC')
            ->limit(50)
            ->get('notifications')->result_array();

        // Mark all as read
        $this->db->where('user_id', $user['id'])->where('is_read', 0)
            ->update('notifications', ['is_read' => 1]);

        $this->load->view('layouts/frontend_layout', [
            'title'        => 'การแจ้งเตือน',
            'notifs'       => $notifs,
            'content_view' => 'frontend/account/notifications',
        ]);
    }
}
