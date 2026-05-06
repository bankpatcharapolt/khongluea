<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Premium extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Package_model');
    }

    public function packages(): void
    {
        $data = [
            'title'    => 'Premium Packages',
            'packages' => $this->Package_model->get_all_active(),
        ];
        $this->load->view('layouts/frontend_layout', array_merge($data, ['content_view' => 'frontend/premium/packages']));
    }

    public function activate(): void
    {
        require_login();

        if ($this->input->method() !== 'post') redirect(site_url('premium'));

        $user       = current_user();
        $package_id = (int)$this->input->post('package_id');
        $success    = $this->Package_model->activate($user['id'], $package_id);

        if ($success) {
            // Refresh session user data
            $updated = $this->db->get_where('users', ['id' => $user['id']])->row_array();
            $this->session->set_userdata('user_data', $updated);
            $this->session->set_flashdata('success', 'Package activated successfully!');
        } else {
            $this->session->set_flashdata('error', 'Activation failed. Insufficient credits or invalid package.');
        }
        redirect(site_url('premium'));
    }
}
