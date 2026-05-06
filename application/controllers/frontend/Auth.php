<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model');
    }

    // ------------------------------------------------------------------
    // LOGIN
    // ------------------------------------------------------------------

    public function login(): void
    {
        if (is_logged_in()) { redirect('/'); }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('email',    'Email',    'required|valid_email|trim');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->_render('auth/login', ['title' => 'Login']);
                return;
            }

            $email    = $this->input->post('email', TRUE);
            $password = $this->input->post('password');
            $user     = $this->User_model->get_by_email($email);

            if ( ! $user || ! $this->User_model->verify_password($password, $user['password_hash'])) {
                $this->session->set_flashdata('error', 'Invalid email or password.');
                $this->_render('auth/login', ['title' => 'Login']);
                return;
            }

            if ($user['is_banned']) {
                $this->session->set_flashdata('error', 'Your account has been suspended.');
                $this->_render('auth/login', ['title' => 'Login']);
                return;
            }

            // Set session
            $this->session->set_userdata([
                'user_id'   => $user['id'],
                'user_data' => $user,
            ]);
            $this->User_model->update($user['id'], ['last_seen_at' => date('Y-m-d H:i:s')]);

            $redirect = $this->session->flashdata('redirect_to') ?: '/';
            redirect($redirect);
        }

        $this->_render('auth/login', ['title' => 'Login']);
    }

    // ------------------------------------------------------------------
    // REGISTER
    // ------------------------------------------------------------------

    public function register(): void
    {
        if (is_logged_in()) { redirect('/'); }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('name',             'Name',             'required|trim|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('email',            'Email',            'required|valid_email|trim|is_unique[users.email]');
            $this->form_validation->set_rules('password',         'Password',         'required|min_length[8]');
            $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'required|matches[password]');

            if ($this->form_validation->run() === FALSE) {
                $this->_render('auth/register', ['title' => 'Register']);
                return;
            }

            $id = $this->User_model->create([
                'name'     => $this->input->post('name', TRUE),
                'email'    => $this->input->post('email', TRUE),
                'password' => $this->input->post('password'),
                'role'     => ROLE_USER,
            ]);

            $this->session->set_flashdata('success', 'Account created! Please log in.');
            redirect('login');
        }

        $this->_render('auth/register', ['title' => 'Create Account']);
    }

    // ------------------------------------------------------------------
    // LOGOUT
    // ------------------------------------------------------------------

    public function logout(): void
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    // ------------------------------------------------------------------
    // FORGOT PASSWORD
    // ------------------------------------------------------------------

    public function forgot_password(): void
    {
        if ($this->input->method() === 'post') {
            $email = $this->input->post('email', TRUE);
            // In production: generate reset token, store it, send email
            $this->session->set_flashdata('success', 'If that email exists, a reset link has been sent.');
            redirect('forgot-password');
        }
        $this->_render('auth/forgot_password', ['title' => 'Forgot Password']);
    }

    // ------------------------------------------------------------------
    // PRIVATE
    // ------------------------------------------------------------------

    private function _render(string $view, array $data = []): void
    {
        $this->load->view('layouts/auth_layout', array_merge($data, ['content_view' => 'frontend/' . $view]));
    }
}
