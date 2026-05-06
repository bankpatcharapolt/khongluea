<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller']   = 'frontend/Home';
$route['404_override']         = '';
$route['translate_uri_dashes'] = FALSE;

// Admin
$route['admin']                              = 'admin/Dashboard/index';
$route['admin/dashboard']                    = 'admin/Dashboard/index';
$route['admin/users']                        = 'admin/Users/index';
$route['admin/users/ban/(:num)']             = 'admin/Users/ban/$1';
$route['admin/users/credits/(:num)']         = 'admin/Users/credits/$1';
$route['admin/items']                        = 'admin/Items/index';
$route['admin/items/toggle_feature/(:num)']  = 'admin/Items/toggle_feature/$1';
$route['admin/items/delete/(:num)']          = 'admin/Items/delete/$1';
$route['admin/categories']                   = 'admin/Categories/index';
$route['admin/categories/create']            = 'admin/Categories/create';
$route['admin/categories/update/(:num)']     = 'admin/Categories/update/$1';
$route['admin/reports']                      = 'admin/Reports/index';
$route['admin/reports/resolve/(:num)']       = 'admin/Reports/resolve/$1';
$route['admin/packages']                     = 'admin/Packages/index';
$route['admin/packages/create']              = 'admin/Packages/create';
$route['admin/packages/update/(:num)']       = 'admin/Packages/update/$1';
$route['admin/credits']                      = 'admin/Credits/index';

// Auth
$route['login']               = 'frontend/Auth/login';
$route['register']            = 'frontend/Auth/register';
$route['logout']              = 'frontend/Auth/logout';
$route['forgot-password']     = 'frontend/Auth/forgot_password';

// Items
$route['items']                = 'frontend/Items/index';
$route['items/create']         = 'frontend/Items/create';
$route['items/edit/(:num)']    = 'frontend/Items/edit/$1';
$route['items/delete/(:num)']  = 'frontend/Items/delete/$1';
$route['items/(:num)/(:any)']  = 'frontend/Items/detail/$1';
$route['items/(:num)']         = 'frontend/Items/detail/$1';

// Chat
$route['chat']                 = 'frontend/Chat/inbox';
$route['chat/start']           = 'frontend/Chat/start';
$route['chat/send']            = 'frontend/Chat/send';
$route['chat/poll']            = 'frontend/Chat/poll';
$route['chat/(:num)']          = 'frontend/Chat/thread/$1';

// Profile
$route['profile/my-listings']  = 'frontend/Profile/my_listings';
$route['profile/(:any)']       = 'frontend/Profile/view/$1';

// Others
$route['search']               = 'frontend/Search/index';
$route['credits']              = 'frontend/Credits/index';
$route['premium']              = 'frontend/Premium/packages';
$route['premium/activate']     = 'frontend/Premium/activate';
$route['favorites']            = 'frontend/Favorites/index';
$route['favorites/toggle']     = 'frontend/Favorites/toggle';
