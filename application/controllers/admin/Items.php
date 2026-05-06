<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login(TRUE);
        $this->load->model('Item_model');
        $this->load->library('pagination');
    }

    public function index(): void
    {
        $filters = [
            'search' => $this->input->get('q', TRUE),
            'status' => $this->input->get('status'),
        ];
        $total  = $this->Item_model->admin_count($filters);
        $page   = max(1, (int)$this->input->get('page'));
        $offset = ($page - 1) * ADMIN_PER_PAGE;

        $config = [
            'base_url'   => site_url('admin/items'),
            'total_rows' => $total,
            'per_page'   => ADMIN_PER_PAGE,
            'query_string_segment' => 'page',
        ];
        $this->pagination->initialize($config);

        $data = [
            'title'      => 'Manage Items',
            'items'      => $this->Item_model->admin_get_all($filters, ADMIN_PER_PAGE, $offset),
            'total'      => $total,
            'pagination' => $this->pagination->create_links(),
        ];
        $this->_render('items/index', $data);
    }

    public function toggle_feature(int $id): void
    {
        $item = $this->Item_model->get_by_id($id);
        if ($item) {
            $this->Item_model->admin_update($id, ['is_featured' => $item['is_featured'] ? 0 : 1]);
        }
        $this->session->set_flashdata('success', 'Featured status updated.');
        redirect(site_url('admin/items'));
    }

    public function delete(int $id): void
    {
        $this->Item_model->admin_update($id, ['status' => ITEM_DELETED]);
        $this->session->set_flashdata('success', 'Item deleted.');
        redirect(site_url('admin/items'));
    }

    private function _render(string $view, array $data = []): void
    {
        $this->load->view('layouts/admin_layout', array_merge($data, ['content_view' => 'admin/' . $view]));
    }
}
