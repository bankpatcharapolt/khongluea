<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Item_model', 'Category_model', 'Image_model', 'Favorite_model']);
        $this->load->library('pagination');
    }

    // ------------------------------------------------------------------
    // BROWSE
    // ------------------------------------------------------------------

    public function index(): void
    {
        $filters = [
            'category_id' => $this->input->get('category_id'),
            'is_free'     => $this->input->get('is_free'),
            'condition'   => $this->input->get('condition'),
            'min_price'   => $this->input->get('min_price'),
            'max_price'   => $this->input->get('max_price'),
            'search'      => $this->input->get('q', TRUE),
            'sort'        => $this->input->get('sort') ?: 'newest',
        ];

        $total   = $this->Item_model->count_list($filters);
        $page    = max(1, (int)$this->input->get('page'));
        $offset  = ($page - 1) * ITEMS_PER_PAGE;

        $config = [
            'base_url'    => current_url() . '?' . http_build_query(array_filter($filters)),
            'total_rows'  => $total,
            'per_page'    => ITEMS_PER_PAGE,
            'uri_segment' => 0,
            'use_page_numbers' => TRUE,
            'query_string_segment' => 'page',
        ];
        $this->pagination->initialize($config);

        $data = [
            'title'      => 'Browse Items',
            'items'      => $this->Item_model->get_list($filters, ITEMS_PER_PAGE, $offset),
            'categories' => $this->Category_model->get_all_active(),
            'filters'    => $filters,
            'total'      => $total,
            'pagination' => $this->pagination->create_links(),
        ];
        $this->_render('items/index', $data);
    }

    // ------------------------------------------------------------------
    // DETAIL
    // ------------------------------------------------------------------

    public function detail(int $id): void
    {
        $item = $this->Item_model->get_by_id($id);
        if ( ! $item) show_404();

        $this->Item_model->increment_views($id);

        $is_favorited = FALSE;
        if (is_logged_in()) {
            $user = current_user();
            $is_favorited = $this->Favorite_model->is_favorited($user['id'], $id);
        }

        // Related items (same category)
        $related = $this->Item_model->get_list(['category_id' => $item['category_id']], 4);

        $data = [
            'title'        => $item['title'],
            'item'         => $item,
            'is_favorited' => $is_favorited,
            'related'      => $related,
        ];
        $this->_render('items/detail', $data);
    }

    // ------------------------------------------------------------------
    // CREATE
    // ------------------------------------------------------------------

    public function create(): void
    {
        require_login();
        $user = current_user();

        // Check free listing limit
        if ( ! $user['premium_status']) {
            $active_count = $this->Item_model->count_active_by_user($user['id']);
            if ($active_count >= FREE_LISTING_LIMIT) {
                $this->session->set_flashdata('error', 'You have reached the free listing limit (' . FREE_LISTING_LIMIT . '). Upgrade to post more.');
                redirect(site_url('premium'));
            }
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('title',       'Title',       'required|trim|max_length[200]');
            $this->form_validation->set_rules('category_id', 'Category',    'required|is_natural_no_zero');
            $this->form_validation->set_rules('description', 'Description', 'required|trim');
            $this->form_validation->set_rules('price',       'Price',       'required|decimal');
            $this->form_validation->set_rules('condition',   'Condition',   'required|in_list[new,like_new,good,fair,poor]');

            if ($this->form_validation->run() === FALSE) {
                $this->_render('items/create', ['title' => 'Post Item', 'categories' => $this->Category_model->get_all_active()]);
                return;
            }

            $item_id = $this->Item_model->create([
                'user_id'       => $user['id'],
                'category_id'   => (int)$this->input->post('category_id'),
                'title'         => $this->input->post('title', TRUE),
                'description'   => $this->security->xss_clean($this->input->post('description')),
                'price'         => (float)$this->input->post('price'),
                'condition'     => $this->input->post('condition'),
                'location_text' => $this->input->post('location_text', TRUE),
                'location_lat'  => $this->input->post('location_lat') ?: NULL,
                'location_lng'  => $this->input->post('location_lng') ?: NULL,
            ]);

            // Handle image uploads
            $this->load->model('Image_model');
            $this->Image_model->upload_multiple($item_id, 'images');

            $this->session->set_flashdata('success', 'Item posted successfully!');
            redirect(site_url('items/' . $item_id));
        }

        $this->_render('items/create', ['title' => 'Post New Item', 'categories' => $this->Category_model->get_all_active()]);
    }

    // ------------------------------------------------------------------
    // EDIT
    // ------------------------------------------------------------------

    public function edit(int $id): void
    {
        require_login();
        $user = current_user();
        $item = $this->Item_model->get_by_id($id);

        if ( ! $item || $item['user_id'] !== $user['id']) show_error('Not authorized.', 403);

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('title',       'Title',    'required|trim|max_length[200]');
            $this->form_validation->set_rules('price',       'Price',    'required|decimal');
            $this->form_validation->set_rules('condition',   'Condition','required|in_list[new,like_new,good,fair,poor]');

            if ($this->form_validation->run() === FALSE) {
                $this->_render('items/edit', ['title' => 'Edit Item', 'item' => $item, 'categories' => $this->Category_model->get_all_active()]);
                return;
            }

            $this->Item_model->update($id, $user['id'], [
                'title'         => $this->input->post('title', TRUE),
                'description'   => $this->security->xss_clean($this->input->post('description')),
                'price'         => (float)$this->input->post('price'),
                'condition'     => $this->input->post('condition'),
                'category_id'   => (int)$this->input->post('category_id'),
                'location_text' => $this->input->post('location_text', TRUE),
                'status'        => $this->input->post('status'),
            ]);

            $this->session->set_flashdata('success', 'Item updated.');
            redirect(site_url('items/' . $id));
        }

        $this->_render('items/edit', ['title' => 'Edit Item', 'item' => $item, 'categories' => $this->Category_model->get_all_active()]);
    }

    // ------------------------------------------------------------------
    // DELETE
    // ------------------------------------------------------------------

    public function delete(int $id): void
    {
        require_login();
        $user = current_user();
        $this->Item_model->soft_delete($id, $user['id']);
        $this->session->set_flashdata('success', 'Item removed.');
        redirect(site_url('profile/my-listings'));
    }

    // ------------------------------------------------------------------
    // PRIVATE
    // ------------------------------------------------------------------

    private function _render(string $view, array $data = []): void
    {
        $this->load->view('layouts/frontend_layout', array_merge($data, ['content_view' => 'frontend/' . $view]));
    }
}
