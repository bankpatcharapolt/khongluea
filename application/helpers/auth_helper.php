<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('is_logged_in')) {
    function is_logged_in(): bool {
        $CI =& get_instance();
        return (bool) $CI->session->userdata('user_id');
    }
}

if ( ! function_exists('current_user')) {
    function current_user(): ?array {
        $CI =& get_instance();
        $user_id = $CI->session->userdata('user_id');
        if ( ! $user_id) return NULL;
        return $CI->session->userdata('user_data') ?: NULL;
    }
}

if ( ! function_exists('is_admin')) {
    function is_admin(): bool {
        $user = current_user();
        return $user && $user['role'] === ROLE_ADMIN;
    }
}

if ( ! function_exists('require_login')) {
    function require_login(bool $admin = FALSE): void {
        if ( ! is_logged_in()) {
            $CI =& get_instance();
            $CI->session->set_flashdata('error', 'Please log in to continue.');
            redirect(site_url('login'));
        }
        if ($admin && ! is_admin()) {
            show_error('Access denied. Admins only.', 403);
        }
    }
}
