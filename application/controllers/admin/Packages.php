<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packages extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login(TRUE);
        $this->load->model('Package_model');
    }

    public function index(): void
    {
        $this->_render('packages/index', [
            'title'    => 'Premium Packages',
            'packages' => $this->Package_model->get_all_admin(),
        ]);
    }

    public function create(): void
    {
        if ($this->input->method() !== 'post') redirect('admin/packages');
        $this->Package_model->create($this->_extract_post());
        $this->session->set_flashdata('success', 'Package created.');
        redirect('admin/packages');
    }

    public function update(int $id): void
    {
        if ($this->input->method() !== 'post') redirect('admin/packages');
        $this->Package_model->update($id, $this->_extract_post());
        $this->session->set_flashdata('success', 'Package updated.');
        redirect('admin/packages');
    }

    private function _extract_post(): array
    {
        return [
            'name'             => $this->input->post('name', TRUE),
            'description'      => $this->input->post('description', TRUE),
            'price_in_credits' => (int)$this->input->post('price_in_credits'),
            'duration_days'    => $this->input->post('duration_days') ?: NULL,
            'max_listings'     => $this->input->post('max_listings') ?: NULL,
            'can_bump'         => $this->input->post('can_bump') ? 1 : 0,
            'bump_quota'       => (int)$this->input->post('bump_quota'),
            'can_highlight'    => $this->input->post('can_highlight') ? 1 : 0,
            'highlight_quota'  => (int)$this->input->post('highlight_quota'),
            'sort_order'       => (int)$this->input->post('sort_order'),
            'is_active'        => $this->input->post('is_active') ? 1 : 0,
        ];
    }

    private function _render(string $view, array $data = []): void
    {
        $this->load->view('layouts/admin_layout', array_merge($data, ['content_view' => 'admin/' . $view]));
    }
}
