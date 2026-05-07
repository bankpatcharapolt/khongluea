-- อัปเดตชื่อ category เป็นภาษาไทย
-- รันใน phpMyAdmin หรือ MySQL client

UPDATE `categories` SET `name` = 'อิเล็กทรอนิกส์'    WHERE `slug` = 'electronics';
UPDATE `categories` SET `name` = 'แฟชั่น & เสื้อผ้า'  WHERE `slug` = 'fashion-apparel';
UPDATE `categories` SET `name` = 'เฟอร์นิเจอร์ & บ้าน' WHERE `slug` = 'furniture-home';
UPDATE `categories` SET `name` = 'หนังสือ & มีเดีย'    WHERE `slug` = 'books-media';
UPDATE `categories` SET `name` = 'กีฬา & กลางแจ้ง'    WHERE `slug` = 'sports-outdoors';
UPDATE `categories` SET `name` = 'ของเล่น & เกม'       WHERE `slug` = 'toys-games';
UPDATE `categories` SET `name` = 'ยานพาหนะ & อะไหล่'  WHERE `slug` = 'vehicles-parts';
UPDATE `categories` SET `name` = 'แจกฟรี'              WHERE `slug` = 'free-items';
UPDATE `categories` SET `name` = 'บริการ'               WHERE `slug` = 'services';
UPDATE `categories` SET `name` = 'อื่นๆ'                WHERE `slug` = 'other';
