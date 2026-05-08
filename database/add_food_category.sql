-- เพิ่ม category อาหาร & เครื่องดื่ม
INSERT INTO `categories` (`name`, `slug`, `icon`, `sort_order`, `is_active`)
VALUES ('อาหาร & เครื่องดื่ม', 'food-beverage', 'bi-cup-hot-fill', 0, 1)
ON DUPLICATE KEY UPDATE `name` = 'อาหาร & เครื่องดื่ม', `icon` = 'bi-cup-hot-fill', `sort_order` = 0;

-- อัปเดต sort_order ที่เหลือให้ขยับ
UPDATE `categories` SET `sort_order` = 1  WHERE `slug` = 'electronics';
UPDATE `categories` SET `sort_order` = 2  WHERE `slug` = 'fashion-apparel';
UPDATE `categories` SET `sort_order` = 3  WHERE `slug` = 'furniture-home';
UPDATE `categories` SET `sort_order` = 4  WHERE `slug` = 'books-media';
UPDATE `categories` SET `sort_order` = 5  WHERE `slug` = 'sports-outdoors';
UPDATE `categories` SET `sort_order` = 6  WHERE `slug` = 'toys-games';
UPDATE `categories` SET `sort_order` = 7  WHERE `slug` = 'vehicles-parts';
UPDATE `categories` SET `sort_order` = 8  WHERE `slug` = 'free-items';
UPDATE `categories` SET `sort_order` = 9  WHERE `slug` = 'services';
UPDATE `categories` SET `sort_order` = 10 WHERE `slug` = 'other';
