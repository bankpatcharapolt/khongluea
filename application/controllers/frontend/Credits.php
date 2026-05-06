<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Credits extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login();
    }

    public function index(): void
    {
        $user         = current_user();
        $transactions = $this->db->where('user_id', $user['id'])
            ->order_by('created_at', 'DESC')
            ->limit(50)
            ->get('credit_transactions')->result_array();

        $this->load->view('layouts/frontend_layout', [
            'title'        => 'My Credits',
            'transactions' => $transactions,
            'content_view' => 'frontend/credits/index',
        ]);
    }
}
