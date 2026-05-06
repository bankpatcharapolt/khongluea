<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Favorites extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_login();
        $this->load->model('Favorite_model');
    }

    public function index(): void
    {
        $user  = current_user();
        $items = $this->Favorite_model->get_for_user($user['id']);

        $this->load->view('layouts/frontend_layout', [
            'title'        => 'My Favourites',
            'items'        => $items,
            'content_view' => 'frontend/favorites/index',
        ]);
    }

    // AJAX toggle
    public function toggle(): void
    {
        if ( ! $this->input->is_ajax_request()) show_error('Bad request.', 400);

        $user    = current_user();
        $item_id = (int)$this->input->post('item_id');

        if ( ! $item_id) {
            $this->output->set_status_header(422)->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Invalid item.']));
            return;
        }

        $action = $this->Favorite_model->toggle($user['id'], $item_id);

        $this->output->set_content_type('application/json')
            ->set_output(json_encode(['action' => $action]));
    }
}
