<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Admin Panel Routes
|--------------------------------------------------------------------------
*/
$route['admin']                 = 'admin/Dashboard/index';
$route['admin/dashboard']       = 'admin/Dashboard/index';
$route['admin/(:any)']          = 'admin/$1';

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
$route['default_controller']    = 'frontend/Home';
$route['404_override']          = '';
$route['translate_uri_dashes']  = FALSE;

// Items
$route['items']                 = 'frontend/Items/index';
$route['items/create']          = 'frontend/Items/create';
$route['items/(:num)']          = 'frontend/Items/detail/$1';
$route['items/(:num)/(:any)']   = 'frontend/Items/detail/$1';
$route['items/edit/(:num)']     = 'frontend/Items/edit/$1';

// Auth
$route['login']                 = 'frontend/Auth/login';
$route['register']              = 'frontend/Auth/register';
$route['logout']                = 'frontend/Auth/logout';
$route['forgot-password']       = 'frontend/Auth/forgot_password';

// Chat
$route['chat']                  = 'frontend/Chat/inbox';
$route['chat/(:num)']           = 'frontend/Chat/thread/$1';

// Profile
$route['profile/(:any)']        = 'frontend/Profile/view/$1';

// Search
$route['search']                = 'frontend/Search/index';

// Credits
$route['credits']               = 'frontend/Credits/index';

// Premium
$route['premium']               = 'frontend/Premium/packages';

// Favorites
$route['favorites']             = 'frontend/Favorites/index';
