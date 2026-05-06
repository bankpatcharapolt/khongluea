-- รัน SQL นี้ถ้ามี database อยู่แล้วและต้องการเพิ่ม map_url
ALTER TABLE `items` 
ADD COLUMN `map_url` VARCHAR(500) NULL DEFAULT NULL COMMENT 'Google Maps link'
AFTER `location_lng`;
