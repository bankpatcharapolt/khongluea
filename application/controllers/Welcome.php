<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Welcome — Root default controller สำหรับ CI3
 * โหลด Home จาก frontend subdirectory
 */
class Welcome extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index(): void
    {
        // Load frontend Home controller logic โดยตรง
        $this->load->model(['Item_model', 'Category_model']);

        $featured   = [];
        $recent     = [];
        $categories = [];

        try {
            $featured   = $this->Item_model->get_featured(8);
            $recent     = $this->Item_model->get_recent(12);
            $categories = $this->Category_model->get_all_active();
        } catch (Exception $e) {
            log_message('error', 'Welcome::index DB: ' . $e->getMessage());
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
