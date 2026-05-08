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
        $this->load->model(['Category_model', 'Item_model']);

        $featured   = [];
        $recent     = [];
        $categories = [];

        // แยก try/catch แต่ละ call เพื่อไม่ให้ error หนึ่งทำให้ทั้งหมดพัง
        try {
            $categories = $this->Category_model->get_all_active();
        } catch (Throwable $e) {
            log_message('error', 'Welcome::categories: ' . $e->getMessage());
        }
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
