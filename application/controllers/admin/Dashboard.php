<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login(TRUE); // admin only
        $this->load->model(['User_model', 'Item_model', 'Report_model']);
    }

    public function index(): void
    {
        $data = [
            'title'          => 'Admin Dashboard',
            'total_users'    => $this->User_model->count_total(),
            'total_items'    => $this->Item_model->count_total(),
            'active_items'   => $this->Item_model->count_active(),
            'pending_reports'=> $this->Report_model->count_pending(),
        ];
        $this->load->view('layouts/admin_layout', array_merge($data, ['content_view' => 'admin/dashboard/index']));
    }
}
