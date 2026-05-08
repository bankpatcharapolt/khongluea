-- ============================================================
-- Fix items table — เพิ่ม columns ที่อาจหายไป
-- รัน SQL นี้ถ้าหมวดหมู่หน้าแรกยังไม่แสดง
-- ============================================================

-- เพิ่ม columns ที่จำเป็น (IF NOT EXISTS = ไม่ error ถ้ามีอยู่แล้ว)
ALTER TABLE `items`
  ADD COLUMN IF NOT EXISTS `is_free`        TINYINT(1)  NOT NULL DEFAULT 0 AFTER `status`,
  ADD COLUMN IF NOT EXISTS `is_bumped`      TINYINT(1)  NOT NULL DEFAULT 0 AFTER `is_free`,
  ADD COLUMN IF NOT EXISTS `bumped_at`      DATETIME        NULL DEFAULT NULL AFTER `is_bumped`,
  ADD COLUMN IF NOT EXISTS `is_highlighted` TINYINT(1)  NOT NULL DEFAULT 0 AFTER `bumped_at`,
  ADD COLUMN IF NOT EXISTS `is_featured`    TINYINT(1)  NOT NULL DEFAULT 0 AFTER `is_highlighted`,
  ADD COLUMN IF NOT EXISTS `map_url`        VARCHAR(500)    NULL DEFAULT NULL AFTER `location_lng`,
  ADD COLUMN IF NOT EXISTS `view_count`     INT UNSIGNED NOT NULL DEFAULT 0 AFTER `is_featured`,
  ADD COLUMN IF NOT EXISTS `expires_at`     DATETIME        NULL DEFAULT NULL AFTER `view_count`;

-- อัปเดต is_free ให้ตรงกับ price ที่มีอยู่
UPDATE `items` SET `is_free` = IF(`price` <= 0, 1, 0);

-- เพิ่ม indexes ที่จำเป็น
ALTER TABLE `items`
  ADD INDEX IF NOT EXISTS `idx_items_free`      (`is_free`),
  ADD INDEX IF NOT EXISTS `idx_items_bumped`    (`is_bumped`, `bumped_at`),
  ADD INDEX IF NOT EXISTS `idx_items_featured`  (`is_featured`),
  ADD INDEX IF NOT EXISTS `idx_items_created`   (`created_at`);

-- ตรวจสอบ users table ว่ามี is_banned column
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `is_banned`      TINYINT(1)  NOT NULL DEFAULT 0 AFTER `role`,
  ADD COLUMN IF NOT EXISTS `credits`        INT         NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `premium_status` TINYINT(1)  NOT NULL DEFAULT 0,
  ADD COLUMN IF NOT EXISTS `bio`            TEXT            NULL DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `phone`          VARCHAR(20)     NULL DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `city`           VARCHAR(100)    NULL DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `avatar`         VARCHAR(300)    NULL DEFAULT NULL;

SELECT 'Done! รัน SQL เสร็จแล้ว' AS result;
