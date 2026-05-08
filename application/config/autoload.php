<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages'] = [];

$autoload['libraries'] = [
    'database',
    'session',
    'form_validation',
];

$autoload['drivers'] = [];

$autoload['helper'] = [
    'url',
    'form',
    'html',
    'security',
    'auth',        // custom
    'marketplace', // custom
];

$autoload['config'] = [];

$autoload['language'] = [];

$autoload['model'] = []; // Models loaded by controllers individually
