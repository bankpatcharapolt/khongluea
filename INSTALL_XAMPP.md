# 📦 วิธีติดตั้ง ของเหลือ บน XAMPP (Windows)

## ขั้นตอนการติดตั้ง

### 1. วางไฟล์
```
C:\xampp\htdocs\khongluea\
├── index.php          ← จาก ZIP นี้
├── .htaccess          ← จาก ZIP นี้
├── application\       ← จาก ZIP นี้
├── public\            ← จาก ZIP นี้
├── database\          ← จาก ZIP นี้
└── system\            ← ดาวน์โหลด CI3 แยก (ดูข้อ 2)
```

### 2. ดาวน์โหลด CodeIgniter 3
1. ไปที่ https://codeigniter.com/download
2. ดาวน์โหลด **CodeIgniter 3.x** (ไม่ใช่ CI4)
3. แตก ZIP แล้วคัดลอกโฟลเดอร์ `system/` วางใน `C:\xampp\htdocs\khongluea\`

### 3. สร้างฐานข้อมูล
เปิด phpMyAdmin → http://localhost/phpmyadmin
```sql
CREATE DATABASE khongluea_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
แล้ว Import ไฟล์ `database\schema.sql`

### 4. ตั้งค่า database
แก้ไข `application\config\database.php`:
```php
'hostname' => 'localhost',
'username' => 'root',        // XAMPP default
'password' => '',            // XAMPP default ไม่มี password
'database' => 'khongluea_db',
```

### 5. ตั้งค่า base_url
แก้ไข `application\config\config.php`:
```php
$config['base_url'] = 'http://localhost/khongluea/';
```

### 6. เปิดใช้ mod_rewrite
แก้ไข `C:\xampp\apache\conf\httpd.conf`
หาบรรทัด `#LoadModule rewrite_module` → ลบ `#` ออก
แล้ว Restart Apache

### 7. สร้าง Admin User
ใน phpMyAdmin รัน SQL:
```sql
INSERT INTO users (role, name, email, password_hash, email_verified, is_active, credits)
VALUES (
  'admin',
  'แอดมิน',
  'admin@khongluea.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.',
  1, 1, 999
);
```
> รหัสผ่าน default คือ: **password**
> (เปลี่ยนทันทีหลังล็อกอิน!)

### 8. สร้างโฟลเดอร์ upload
```
C:\xampp\htdocs\khongluea\public\uploads\items\     (สร้างโฟลเดอร์เปล่า)
C:\xampp\htdocs\khongluea\public\uploads\avatars\   (สร้างโฟลเดอร์เปล่า)
```

### 9. เปิดเว็บ
- หน้าหลัก: http://localhost/khongluea/
- Admin Panel: http://localhost/khongluea/admin

---

## ❗ Error ที่พบบ่อย

### "failed to open stream: error_php.php"
**สาเหตุ:** ไม่มีโฟลเดอร์ `application\views\errors\html\`
**แก้:** ไฟล์นี้มีอยู่ใน ZIP แล้ว ตรวจสอบว่าแตก ZIP ครบทุกไฟล์

### "No input file specified" หรือหน้าว่าง
**สาเหตุ:** mod_rewrite ไม่ได้เปิด หรือ base_url ไม่ตรง
**แก้:** ตรวจสอบข้อ 5 และ 6

### "Unable to connect to your database server"
**สาเหตุ:** database.php ตั้งค่าผิด
**แก้:** ตรวจสอบข้อ 4

### "The URI you submitted has disallowed characters"
**สาเหตุ:** URL มีอักขระพิเศษ
**แก้:** ปกติไม่เกิดถ้าใช้ routes ที่กำหนดไว้

---
