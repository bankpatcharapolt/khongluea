<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login(TRUE);
        $this->load->model('Report_model');
        $this->load->library('pagination');
    }

    public function index(): void
    {
        $filters = ['status' => $this->input->get('status') ?: 'pending'];
        $total   = (int)$this->db->where($filters)->count_all_results('reports');
        $page    = max(1, (int)$this->input->get('page'));
        $offset  = ($page - 1) * ADMIN_PER_PAGE;

        $data = [
            'title'   => 'Reports',
            'reports' => $this->Report_model->get_all($filters, ADMIN_PER_PAGE, $offset),
            'total'   => $total,
        ];
        $this->_render('reports/index', $data);
    }

    public function resolve(int $id): void
    {
        $admin  = current_user();
        $status = $this->input->post('status') ?: 'resolved';
        $note   = $this->input->post('note', TRUE) ?: '';
        $this->Report_model->update_status($id, $status, $admin['id'], $note);
        $this->session->set_flashdata('success', 'Report updated.');
        redirect('admin/reports');
    }

    private function _render(string $view, array $data = []): void
    {
        $this->load->view('layouts/admin_layout', array_merge($data, ['content_view' => 'admin/' . $view]));
    }
}
