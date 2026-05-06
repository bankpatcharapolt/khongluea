-- ============================================================
-- FREEMIUM C2C USED-ITEM MARKETPLACE
-- Complete MySQL Schema
-- Engine: InnoDB | Charset: utf8mb4
-- ============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------
-- 1. USERS
-- ------------------------------------------------------------
CREATE TABLE `users` (
  `id`                INT(11)      UNSIGNED NOT NULL AUTO_INCREMENT,
  `role`              ENUM('admin','user')   NOT NULL DEFAULT 'user',
  `name`              VARCHAR(100)           NOT NULL,
  `email`             VARCHAR(191)           NOT NULL,
  `password_hash`     VARCHAR(255)           NOT NULL,
  `phone`             VARCHAR(20)                NULL DEFAULT NULL,
  `avatar`            VARCHAR(255)               NULL DEFAULT NULL,
  `bio`               TEXT                       NULL DEFAULT NULL,
  `city`              VARCHAR(100)               NULL DEFAULT NULL,
  `premium_status`    TINYINT(1)             NOT NULL DEFAULT 0,
  `premium_expires_at` DATETIME                  NULL DEFAULT NULL,
  `credits`           INT(11)      UNSIGNED  NOT NULL DEFAULT 0,
  `is_active`         TINYINT(1)             NOT NULL DEFAULT 1,
  `is_banned`         TINYINT(1)             NOT NULL DEFAULT 0,
  `email_verified`    TINYINT(1)             NOT NULL DEFAULT 0,
  `verify_token`      VARCHAR(100)               NULL DEFAULT NULL,
  `reset_token`       VARCHAR(100)               NULL DEFAULT NULL,
  `reset_expires_at`  DATETIME                   NULL DEFAULT NULL,
  `last_seen_at`      DATETIME                   NULL DEFAULT NULL,
  `created_at`        DATETIME               NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`        DATETIME               NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_premium` (`premium_status`),
  KEY `idx_users_active` (`is_active`, `is_banned`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 2. CATEGORIES
-- ------------------------------------------------------------
CREATE TABLE `categories` (
  `id`          INT(11)  UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id`   INT(11)  UNSIGNED     NULL DEFAULT NULL,
  `name`        VARCHAR(100)      NOT NULL,
  `slug`        VARCHAR(120)      NOT NULL,
  `icon`        VARCHAR(100)          NULL DEFAULT NULL,
  `sort_order`  SMALLINT(5)  UNSIGNED NOT NULL DEFAULT 0,
  `is_active`   TINYINT(1)        NOT NULL DEFAULT 1,
  `created_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_slug` (`slug`),
  KEY `idx_categories_parent` (`parent_id`),
  CONSTRAINT `fk_categories_parent`
    FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. ITEMS
-- ------------------------------------------------------------
CREATE TABLE `items` (
  `id`              INT(11)  UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`         INT(11)  UNSIGNED NOT NULL,
  `category_id`     INT(11)  UNSIGNED NOT NULL,
  `title`           VARCHAR(200)      NOT NULL,
  `description`     TEXT              NOT NULL,
  `price`           DECIMAL(12,2)     NOT NULL DEFAULT 0.00,
  `condition`       ENUM('new','like_new','good','fair','poor') NOT NULL DEFAULT 'good',
  `location_text`   VARCHAR(200)          NULL DEFAULT NULL,
  `location_lat`    DECIMAL(10,7)         NULL DEFAULT NULL,
  `location_lng`    DECIMAL(10,7)         NULL DEFAULT NULL,
  `status`          ENUM('active','reserved','sold','deleted') NOT NULL DEFAULT 'active',
  `is_free`         TINYINT(1)        NOT NULL DEFAULT 0,
  `view_count`      INT(11)  UNSIGNED NOT NULL DEFAULT 0,
  `is_bumped`       TINYINT(1)        NOT NULL DEFAULT 0,
  `bumped_at`       DATETIME              NULL DEFAULT NULL,
  `is_highlighted`  TINYINT(1)        NOT NULL DEFAULT 0,
  `is_featured`     TINYINT(1)        NOT NULL DEFAULT 0,
  `expires_at`      DATETIME              NULL DEFAULT NULL,
  `created_at`      DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_items_user`       (`user_id`),
  KEY `idx_items_category`   (`category_id`),
  KEY `idx_items_status`     (`status`),
  KEY `idx_items_free`       (`is_free`),
  KEY `idx_items_bumped`     (`is_bumped`, `bumped_at`),
  KEY `idx_items_featured`   (`is_featured`),
  KEY `idx_items_created`    (`created_at`),
  KEY `idx_items_location`   (`location_lat`, `location_lng`),
  CONSTRAINT `fk_items_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_items_category`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 4. ITEM IMAGES
-- ------------------------------------------------------------
CREATE TABLE `item_images` (
  `id`          INT(11)  UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id`     INT(11)  UNSIGNED NOT NULL,
  `image_path`  VARCHAR(300)      NOT NULL,
  `is_primary`  TINYINT(1)        NOT NULL DEFAULT 0,
  `sort_order`  TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_item_images_item`    (`item_id`),
  KEY `idx_item_images_primary` (`item_id`, `is_primary`),
  CONSTRAINT `fk_item_images_item`
    FOREIGN KEY (`item_id`) REFERENCES `items` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. CONVERSATIONS
-- ------------------------------------------------------------
CREATE TABLE `conversations` (
  `id`          INT(11)  UNSIGNED NOT NULL AUTO_INCREMENT,
  `item_id`     INT(11)  UNSIGNED NOT NULL,
  `buyer_id`    INT(11)  UNSIGNED NOT NULL,
  `seller_id`   INT(11)  UNSIGNED NOT NULL,
  `last_message_at` DATETIME      NULL DEFAULT NULL,
  `created_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_conversation` (`item_id`, `buyer_id`, `seller_id`),
  KEY `idx_conv_buyer`  (`buyer_id`),
  KEY `idx_conv_seller` (`seller_id`),
  KEY `idx_conv_item`   (`item_id`),
  CONSTRAINT `fk_conv_item`   FOREIGN KEY (`item_id`)   REFERENCES `items`  (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_conv_buyer`  FOREIGN KEY (`buyer_id`)  REFERENCES `users`  (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_conv_seller` FOREIGN KEY (`seller_id`) REFERENCES `users`  (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. MESSAGES
-- ------------------------------------------------------------
CREATE TABLE `messages` (
  `id`              INT(11)  UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversation_id` INT(11)  UNSIGNED NOT NULL,
  `sender_id`       INT(11)  UNSIGNED NOT NULL,
  `receiver_id`     INT(11)  UNSIGNED NOT NULL,
  `item_id`         INT(11)  UNSIGNED NOT NULL,
  `message`         TEXT              NOT NULL,
  `is_read`         TINYINT(1)        NOT NULL DEFAULT 0,
  `read_at`         DATETIME              NULL DEFAULT NULL,
  `created_at`      DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_messages_conv`     (`conversation_id`),
  KEY `idx_messages_sender`   (`sender_id`),
  KEY `idx_messages_receiver` (`receiver_id`),
  KEY `idx_messages_unread`   (`receiver_id`, `is_read`),
  KEY `idx_messages_item`     (`item_id`),
  CONSTRAINT `fk_messages_conv`
    FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_messages_sender`
    FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_messages_receiver`
    FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 7. PREMIUM PACKAGES
-- ------------------------------------------------------------
CREATE TABLE `premium_packages` (
  `id`                  INT(11)  UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`                VARCHAR(100)      NOT NULL,
  `description`         TEXT                  NULL,
  `price_in_credits`    INT(11)  UNSIGNED  NOT NULL DEFAULT 0,
  `duration_days`       SMALLINT(5) UNSIGNED  NULL DEFAULT NULL,
  `max_listings`        SMALLINT(5) UNSIGNED  NULL DEFAULT NULL,
  `can_bump`            TINYINT(1)        NOT NULL DEFAULT 0,
  `bump_quota`          TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  `can_highlight`       TINYINT(1)        NOT NULL DEFAULT 0,
  `highlight_quota`     TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  `is_active`           TINYINT(1)        NOT NULL DEFAULT 1,
  `sort_order`          SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,
  `created_at`          DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`          DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_pkg_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 8. USER PACKAGE SUBSCRIPTIONS
-- ------------------------------------------------------------
CREATE TABLE `user_packages` (
  `id`          INT(11)  UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     INT(11)  UNSIGNED NOT NULL,
  `package_id`  INT(11)  UNSIGNED NOT NULL,
  `credits_spent` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `bumps_used`  TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  `highlights_used` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0,
  `expires_at`  DATETIME              NULL DEFAULT NULL,
  `created_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_up_user`    (`user_id`),
  KEY `idx_up_package` (`package_id`),
  KEY `idx_up_expires` (`user_id`, `expires_at`),
  CONSTRAINT `fk_up_user`    FOREIGN KEY (`user_id`)    REFERENCES `users`            (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_up_package` FOREIGN KEY (`package_id`) REFERENCES `premium_packages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 9. CREDIT TRANSACTIONS
-- ------------------------------------------------------------
CREATE TABLE `credit_transactions` (
  `id`          INT(11)  UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     INT(11)  UNSIGNED NOT NULL,
  `amount`      INT(11)           NOT NULL,
  `type`        ENUM('purchase','spend','refund','bonus','admin_adjustment') NOT NULL,
  `reference_id` INT(11) UNSIGNED     NULL DEFAULT NULL,
  `note`        VARCHAR(255)          NULL DEFAULT NULL,
  `balance_after` INT(11) UNSIGNED  NOT NULL DEFAULT 0,
  `created_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ct_user`    (`user_id`),
  KEY `idx_ct_type`    (`type`),
  KEY `idx_ct_created` (`created_at`),
  CONSTRAINT `fk_ct_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 10. REPORTS
-- ------------------------------------------------------------
CREATE TABLE `reports` (
  `id`          INT(11)  UNSIGNED NOT NULL AUTO_INCREMENT,
  `reporter_id` INT(11)  UNSIGNED NOT NULL,
  `item_id`     INT(11)  UNSIGNED     NULL DEFAULT NULL,
  `reported_user_id` INT(11) UNSIGNED NULL DEFAULT NULL,
  `reason`      ENUM('spam','prohibited_item','misleading_info','offensive_content','counterfeit','other') NOT NULL DEFAULT 'other',
  `details`     TEXT                  NULL,
  `status`      ENUM('pending','reviewed','resolved','dismissed') NOT NULL DEFAULT 'pending',
  `admin_note`  TEXT                  NULL,
  `reviewed_by` INT(11)  UNSIGNED     NULL DEFAULT NULL,
  `reviewed_at` DATETIME              NULL DEFAULT NULL,
  `created_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_reports_reporter`  (`reporter_id`),
  KEY `idx_reports_item`      (`item_id`),
  KEY `idx_reports_user`      (`reported_user_id`),
  KEY `idx_reports_status`    (`status`),
  CONSTRAINT `fk_reports_reporter`
    FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reports_item`
    FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_reports_reported_user`
    FOREIGN KEY (`reported_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_reports_reviewed_by`
    FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 11. FAVORITES
-- ------------------------------------------------------------
CREATE TABLE `favorites` (
  `id`         INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT(11) UNSIGNED NOT NULL,
  `item_id`    INT(11) UNSIGNED NOT NULL,
  `created_at` DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_favorite` (`user_id`, `item_id`),
  KEY `idx_fav_item` (`item_id`),
  CONSTRAINT `fk_fav_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_fav_item` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 12. NOTIFICATIONS
-- ------------------------------------------------------------
CREATE TABLE `notifications` (
  `id`          INT(11)  UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     INT(11)  UNSIGNED NOT NULL,
  `type`        VARCHAR(60)       NOT NULL,
  `title`       VARCHAR(200)      NOT NULL,
  `body`        TEXT                  NULL,
  `url`         VARCHAR(300)          NULL,
  `is_read`     TINYINT(1)        NOT NULL DEFAULT 0,
  `created_at`  DATETIME          NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_notif_user`   (`user_id`),
  KEY `idx_notif_unread` (`user_id`, `is_read`),
  CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- SEED: Default categories
-- ------------------------------------------------------------
INSERT INTO `categories` (`name`, `slug`, `icon`, `sort_order`) VALUES
('Electronics',       'electronics',        'bi-cpu',               1),
('Fashion & Apparel', 'fashion-apparel',    'bi-bag',               2),
('Furniture & Home',  'furniture-home',     'bi-house',             3),
('Books & Media',     'books-media',        'bi-book',              4),
('Sports & Outdoors', 'sports-outdoors',    'bi-bicycle',           5),
('Toys & Games',      'toys-games',         'bi-controller',        6),
('Vehicles & Parts',  'vehicles-parts',     'bi-car-front',         7),
('Free Items',        'free-items',         'bi-gift',              8),
('Services',          'services',           'bi-person-workspace',  9),
('Other',             'other',              'bi-three-dots',       10);

-- ------------------------------------------------------------
-- SEED: Premium packages
-- ------------------------------------------------------------
INSERT INTO `premium_packages`
  (`name`, `description`, `price_in_credits`, `duration_days`,
   `max_listings`, `can_bump`, `bump_quota`, `can_highlight`, `highlight_quota`, `sort_order`)
VALUES
('Free Tier',     'Default account. 5 active listings.',         0,   NULL,  5,    0,  0,  0, 0, 1),
('Starter Pack',  'Up to 20 listings + 3 bumps.',               50,   30,   20,    1,  3,  0, 0, 2),
('Pro Seller',    'Unlimited listings, 10 bumps & highlights.', 150,   30,   NULL, 1, 10,  1, 5, 3),
('Bump Item',     'One-time bump to the top.',                   20,   NULL, NULL,  1,  1,  0, 0, 4),
('Highlight Item','One-time highlight for 7 days.',              30,    7,   NULL,  0,  0,  1, 1, 5);

SET FOREIGN_KEY_CHECKS = 1;
