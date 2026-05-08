<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group  = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'          => '',
    'hostname'     => '127.0.0.1',   // ใช้ IP แทน localhost เพื่อบังคับ TCP
    'port'         => 3306,
    'username'     => 'root',
    'password'     => '',            // XAMPP default: ไม่มี password
    'database'     => 'khongluea',
    'dbdriver'     => 'mysqli',
    'dbprefix'     => '',
    'pconnect'     => FALSE,
    'db_debug'     => FALSE,         // ปิด debug เพื่อไม่ให้ throw fatal error
    'cache_on'     => FALSE,
    'cachedir'     => '',
    'char_set'     => 'utf8mb4',
    'dbcollat'     => 'utf8mb4_unicode_ci',
    'swap_pre'     => '',
    'encrypt'      => FALSE,
    'compress'     => FALSE,
    'stricton'     => FALSE,
    'failover'     => array(),
    'save_queries' => TRUE
);
