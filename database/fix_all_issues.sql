-- ============================================================
-- ของเหลือ — Fix All Issues Migration
-- รัน SQL นี้ใน phpMyAdmin ทั้งหมด
-- ============================================================

-- 1. เพิ่ม is_free column ถ้ายังไม่มี
ALTER TABLE `items`
  ADD COLUMN IF NOT EXISTS `is_free` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`;

-- อัปเดต is_free ให้ตรงกับ price=0 ที่มีอยู่แล้ว
UPDATE `items` SET `is_free` = IF(`price` <= 0, 1, 0);

-- 2. เพิ่ม index ถ้ายังไม่มี
ALTER TABLE `items`
  ADD INDEX IF NOT EXISTS `idx_items_free` (`is_free`);

-- 3. เพิ่มหมวดหมู่ภาษาไทยครบ
-- ล้าง categories เก่าก่อน (ถ้ามีข้อมูล English)
-- แล้วใส่ Thai ทั้งหมด
INSERT INTO `categories` (`name`, `slug`, `icon`, `sort_order`, `is_active`)
VALUES
  ('อาหาร & เครื่องดื่ม','food-beverage',    'bi-cup-hot-fill',     0, 1),
  ('อิเล็กทรอนิกส์',     'electronics',       'bi-cpu',              1, 1),
  ('แฟชั่น & เสื้อผ้า',  'fashion-apparel',   'bi-bag',              2, 1),
  ('เฟอร์นิเจอร์ & บ้าน','furniture-home',    'bi-house',            3, 1),
  ('หนังสือ & มีเดีย',   'books-media',       'bi-book',             4, 1),
  ('กีฬา & กลางแจ้ง',   'sports-outdoors',   'bi-bicycle',          5, 1),
  ('ของเล่น & เกม',      'toys-games',        'bi-controller',       6, 1),
  ('ยานพาหนะ & อะไหล่',  'vehicles-parts',    'bi-car-front',        7, 1),
  ('แจกฟรี',             'free-items',        'bi-gift',             8, 1),
  ('บริการ',             'services',          'bi-person-workspace', 9, 1),
  ('อื่นๆ',              'other',             'bi-three-dots',      10, 1)
ON DUPLICATE KEY UPDATE
  `name`       = VALUES(`name`),
  `icon`       = VALUES(`icon`),
  `sort_order` = VALUES(`sort_order`),
  `is_active`  = 1;
