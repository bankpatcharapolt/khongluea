<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Item_model', 'Category_model']);
        $this->load->library('pagination');
    }

    public function index(): void
    {
        // Delegates to Item browse with q parameter
        $q = $this->input->get('q', TRUE);
        if ($q) {
            redirect('items?' . http_build_query(['q' => $q]));
        }
        redirect('items');
    }
}
