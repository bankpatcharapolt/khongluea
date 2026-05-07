<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
*/
$config['base_url'] = "https://khongluea.com/";
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
*/
$config['encryption_key'] = 'REPLACE_WITH_32_CHAR_RANDOM_STRING_HERE';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
*/
$config['sess_driver']             = 'files';
$config['sess_cookie_name']        = 'kl_session';
$config['sess_expiration']         = 7200;
$config['sess_save_path']          = sys_get_temp_dir();
$config['sess_match_ip']           = FALSE;
$config['sess_time_to_update']     = 300;
$config['sess_regenerate_destroy'] = FALSE;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
*/
$config['cookie_prefix'] = 'kl_';
$config['cookie_domain']   = '';
$config['cookie_path']     = '/';
$config['cookie_secure']   = FALSE;
$config['cookie_httponly']  = TRUE;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
*/
$config['csrf_protection']   = TRUE;
$config['csrf_token_name']   = 'csrf_token';
$config['csrf_cookie_name']  = 'csrf_cookie';
$config['csrf_expire']       = 7200;
$config['csrf_regenerate']   = TRUE;
$config['csrf_exclude_uris'] = [
    'chat/send',
    'chat/poll',
    'frontend/Chat/poll',
    'frontend/Chat/send',
];

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Global XSS Filtering — Apply manually per input field
|--------------------------------------------------------------------------
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Logging
|--------------------------------------------------------------------------
*/
$config['log_threshold'] = 4; // debug — เปลี่ยนเป็น 1 เมื่อ production
$config['log_path']      = APPPATH . 'logs/';
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| File Upload Settings
|--------------------------------------------------------------------------
*/
$config['upload_path']         = FCPATH . 'uploads/items/';
$config['avatar_path']         = FCPATH . 'uploads/avatars/';
$config['allowed_types']       = 'jpg|jpeg|png|webp';
$config['max_size']            = 2048;
$config['max_images_per_item'] = 8;

/*
|--------------------------------------------------------------------------
| Miscellaneous
|--------------------------------------------------------------------------
*/
$config['charset']          = 'UTF-8';
$config['subclass_prefix']  = 'MY_';
$config['composer_autoload'] = FALSE;
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['allow_get_array']    = TRUE;
$config['enable_query_strings'] = FALSE;
$config['error_prefix']       = '<p>';
$config['error_suffix']       = '</p>';
$config['language']           = 'english';
$config['time_reference']     = 'local';
$config['rewrite_short_tags'] = FALSE;
