<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login(TRUE);
        $this->load->model('Category_model');
    }

    public function index(): void
    {
        $this->_render('categories/index', [
            'title'      => 'Categories',
            'categories' => $this->Category_model->get_all_admin(),
        ]);
    }

    public function create(): void
    {
        if ($this->input->method() !== 'post') redirect('admin/categories');

        $slug = url_title($this->input->post('slug', TRUE), '-', TRUE);
        $this->Category_model->create([
            'name'       => $this->input->post('name', TRUE),
            'slug'       => $slug,
            'icon'       => $this->input->post('icon', TRUE),
            'sort_order' => (int)$this->input->post('sort_order'),
            'is_active'  => 1,
        ]);
        $this->session->set_flashdata('success', 'Category created.');
        redirect('admin/categories');
    }

    public function update(int $id): void
    {
        if ($this->input->method() !== 'post') redirect('admin/categories');

        $this->Category_model->update($id, [
            'name'       => $this->input->post('name', TRUE),
            'slug'       => url_title($this->input->post('slug', TRUE), '-', TRUE),
            'icon'       => $this->input->post('icon', TRUE),
            'sort_order' => (int)$this->input->post('sort_order'),
            'is_active'  => $this->input->post('is_active') ? 1 : 0,
        ]);
        $this->session->set_flashdata('success', 'Category updated.');
        redirect('admin/categories');
    }

    private function _render(string $view, array $data = []): void
    {
        $this->load->view('layouts/admin_layout', array_merge($data, ['content_view' => 'admin/' . $view]));
    }
}
