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
        $featured   = [];
        $recent     = [];
        $categories = [];

        // โหลด database ก่อน
        $this->load->database();

        // Query categories โดยตรง — ไม่ผ่าน model เพื่อ debug
        $cat_result = $this->db
            ->where('is_active', 1)
            ->order_by('sort_order', 'ASC')
            ->get('categories');

        if ($cat_result) {
            $categories = $cat_result->result_array();
        }

        $this->load->model('Item_model');

        try {
            $featured = $this->Item_model->get_featured(8);
        } catch (Throwable $e) {
            log_message('error', 'Welcome::featured: ' . $e->getMessage());
        }
        try {
            $recent = $this->Item_model->get_recent(12);
        } catch (Throwable $e) {
            log_message('error', 'Welcome::recent: ' . $e->getMessage());
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
