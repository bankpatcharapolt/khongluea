<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index(): void
    {
        $featured   = [];
        $recent     = [];
        $categories = [];

        // Load models with DB error protection
        try {
            $this->load->model(['Item_model', 'Category_model']);
            $featured   = $this->Item_model->get_featured(8);
            $recent     = $this->Item_model->get_recent(12);
            $categories = $this->Category_model->get_all_active();
        } catch (Exception $e) {
            // DB not ready — show empty home page gracefully
            log_message('error', 'Home::index DB error: ' . $e->getMessage());
        }

        $this->load->view('layouts/frontend_layout', [
            'title'        => 'ของเหลือ — ซื้อ ขาย แจกฟรี',
            'featured'     => $featured,
            'recent'       => $recent,
            'categories'   => $categories,
            'content_view' => 'frontend/home/index',
        ]);
    }
}
