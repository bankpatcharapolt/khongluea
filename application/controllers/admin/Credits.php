<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Credits extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login(TRUE);
    }

    public function index(): void
    {
        $transactions = $this->db
            ->select('credit_transactions.*, users.name AS user_name, users.email AS user_email')
            ->from('credit_transactions')
            ->join('users', 'users.id = credit_transactions.user_id')
            ->order_by('credit_transactions.created_at', 'DESC')
            ->limit(100)
            ->get()->result_array();

        $this->load->view('layouts/admin_layout', [
            'title'        => 'Credit Transactions',
            'transactions' => $transactions,
            'content_view' => 'admin/credits/transactions',
        ]);
    }
}
