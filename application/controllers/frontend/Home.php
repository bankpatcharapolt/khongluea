<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Item_model', 'Category_model']);
    }

    public function index(): void
    {
        $data = [
            'title'      => 'ของเหลือ — ซื้อ ขาย แจกฟรี',
            'featured'   => $this->Item_model->get_featured(8),
            'recent'     => $this->Item_model->get_recent(12),
            'categories' => $this->Category_model->get_all_active(),
        ];
        $this->load->view('layouts/frontend_layout', array_merge($data, ['content_view' => 'frontend/home/index']));
    }
}
