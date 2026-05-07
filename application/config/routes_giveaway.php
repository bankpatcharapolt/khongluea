<?php
// ============================================================
// เพิ่ม routes เหล่านี้ใน application/config/routes.php
// ============================================================

// ── Giveaway (Receiver) ──────────────────────────────────
$route['giveaway']                          = 'frontend/Giveaway/index';
$route['giveaway/view/(:num)']              = 'frontend/Giveaway/view/$1';
$route['giveaway/reserve/(:num)']           = 'frontend/Giveaway/reserve/$1';
$route['giveaway/cancel/(:num)']            = 'frontend/Giveaway/cancel/$1';
$route['giveaway/qr/(:any)']                = 'frontend/Giveaway/qr/$1';
$route['giveaway/my-reservations']          = 'frontend/Giveaway/my_reservations';

// ── Donor ────────────────────────────────────────────────
$route['donor/listings']                    = 'frontend/Donor/my_listings';
$route['donor/create']                      = 'frontend/Donor/create';
$route['donor/edit/(:num)']                 = 'frontend/Donor/edit/$1';
$route['donor/delete/(:num)']               = 'frontend/Donor/delete/$1';
$route['donor/verify/(:num)']               = 'frontend/Donor/verify/$1';

// ── Cron ─────────────────────────────────────────────────
$route['cron/giveaway/expire']              = 'Cron/giveaway_expire';
$route['cron/giveaway/close']               = 'Cron/giveaway_close';

// ── Admin ────────────────────────────────────────────────
$route['admin/giveaway']                    = 'admin/Giveaway/index';
$route['admin/giveaway/strikes']            = 'admin/Giveaway/strikes';
$route['admin/giveaway/remove-strike/(:num)'] = 'admin/Giveaway/remove_strike/$1';
$route['admin/giveaway/delete/(:num)']      = 'admin/Giveaway/delete_listing/$1';
