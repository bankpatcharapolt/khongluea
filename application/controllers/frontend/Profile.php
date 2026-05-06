<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['User_model', 'Item_model']);
    }

    public function view(string $identifier): void
    {
        // $identifier can be a name slug or numeric id
        $profile = is_numeric($identifier)
            ? $this->User_model->get_by_id((int)$identifier)
            : $this->db->get_where('users', ['name' => $identifier, 'is_active' => 1])->row_array();

        if ( ! $profile || $profile['is_banned']) show_404();

        $current     = current_user();
        $is_own      = is_logged_in() && $current['id'] == $profile['id'];
        $items       = $this->Item_model->get_list(['user_id' => $profile['id']], 24);

        $this->_render('profile/public', [
            'title'           => $profile['name'] . '\'s Profile',
            'profile'         => $profile,
            'items'           => $items,
            'is_own_profile'  => $is_own,
        ]);
    }

    // Convenience alias for logged-in users
    public function my_listings(): void
    {
        require_login();
        $user  = current_user();
        $items = $this->Item_model->get_list(['user_id' => $user['id']], 50);
        $this->_render('profile/public', [
            'title'          => 'My Listings',
            'profile'        => $user,
            'items'          => $items,
            'is_own_profile' => TRUE,
        ]);
    }

    private function _render(string $view, array $data = []): void
    {
        $this->load->view('layouts/frontend_layout', array_merge($data, ['content_view' => 'frontend/' . $view]));
    }
}
