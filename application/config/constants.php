<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// User roles
define('ROLE_ADMIN', 'admin');
define('ROLE_USER',  'user');

// Item statuses
define('ITEM_ACTIVE',   'active');
define('ITEM_RESERVED', 'reserved');
define('ITEM_SOLD',     'sold');
define('ITEM_DELETED',  'deleted');

// Credit operation types
define('CREDIT_PURCHASE',  'purchase');
define('CREDIT_SPEND',     'spend');
define('CREDIT_REFUND',    'refund');
define('CREDIT_BONUS',     'bonus');
define('CREDIT_ADMIN_ADJ', 'admin_adjustment');

// Listing limits
define('FREE_LISTING_LIMIT', 5);

// Pagination
define('ITEMS_PER_PAGE',    24);
define('MESSAGES_PER_PAGE', 30);
define('ADMIN_PER_PAGE',    25);

// Image upload
define('UPLOAD_PATH',       FCPATH . 'uploads/items/');
define('AVATAR_PATH',       FCPATH . 'uploads/avatars/');
define('MAX_ITEM_IMAGES',   8);
define('MAX_UPLOAD_KB',     2048);

// Chat polling interval in milliseconds (used in JS)
define('CHAT_POLL_INTERVAL', 4000);

// Placeholder image
define('IMG_PLACEHOLDER', 'assets/img/no-image.svg');
