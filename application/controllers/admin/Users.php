<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login(TRUE);
        $this->load->model('User_model');
        $this->load->library('pagination');
    }

    public function index(): void
    {
        $filters = ['search' => $this->input->get('q', TRUE), 'role' => $this->input->get('role')];
        $total   = $this->User_model->count_all($filters);
        $page    = max(1, (int)$this->input->get('page'));
        $offset  = ($page - 1) * ADMIN_PER_PAGE;

        $config = [
            'base_url'   => site_url('admin/users'),
            'total_rows' => $total,
            'per_page'   => ADMIN_PER_PAGE,
            'query_string_segment' => 'page',
        ];
        $this->pagination->initialize($config);

        $data = [
            'title'      => 'Manage Users',
            'users'      => $this->User_model->get_all($filters, ADMIN_PER_PAGE, $offset),
            'total'      => $total,
            'pagination' => $this->pagination->create_links(),
        ];
        $this->_render('users/index', $data);
    }

    public function ban(int $id): void
    {
        $user = $this->User_model->get_by_id($id);
        if ( ! $user) show_404();
        $this->User_model->ban($id, ! $user['is_banned']);
        $msg = $user['is_banned'] ? 'User unbanned.' : 'User banned.';
        $this->session->set_flashdata('success', $msg);
        redirect(site_url('admin/users'));
    }

    public function credits(int $id): void
    {
        if ($this->input->method() === 'post') {
            $amount = (int)$this->input->post('amount');
            $note   = $this->input->post('note', TRUE);
            $this->User_model->adjust_credits($id, $amount, CREDIT_ADMIN_ADJ, NULL, $note ?: 'Admin adjustment');
            $this->session->set_flashdata('success', 'Credits updated.');
        }
        redirect(site_url('admin/users'));
    }

    private function _render(string $view, array $data = []): void
    {
        $this->load->view('layouts/admin_layout', array_merge($data, ['content_view' => 'admin/' . $view]));
    }
}
