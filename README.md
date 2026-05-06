# ของเหลือ (Khong Luea)

แพลตฟอร์มซื้อขายของมือสอง C2C แบบ Freemium  
สร้างด้วย **CodeIgniter 3 · MySQL · Bootstrap 5 · Vanilla JS**

---

## คุณสมบัติ

- ลงประกาศขาย/แจกของมือสอง พร้อมรูปภาพหลายรูป
- ค้นหาและกรองสินค้าตามหมวดหมู่ ราคา สภาพ
- แชทในแอป ระหว่างผู้ซื้อและผู้ขาย (AJAX polling)
- ระบบเครดิต + แพ็กเกจพรีเมียม (ปักหมุด / ไฮไลต์)
- บันทึกของที่ชอบ (Favorites)
- ระบบรายงานสินค้า/ผู้ใช้
- Admin Panel ครบครัน (ไทย)

---

## ติดตั้ง

### 1. ต้องการ
- PHP 7.4+ (PHP 8.x แนะนำ)
- MySQL 5.7+ / MariaDB 10.3+
- Apache + mod_rewrite

### 2. ตั้งค่าฐานข้อมูล
```sql
CREATE DATABASE khongluea_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
แล้ว import:
```bash
mysql -u root -p khongluea_db < database/schema.sql
```

### 3. แก้ไข config
`application/config/database.php`:
```php
'username' => 'your_db_user',
'password' => 'your_db_password',
'database' => 'khongluea_db',
```

`application/config/config.php`:
```php
$config['base_url']       = 'http://localhost/khongluea/';
$config['encryption_key'] = 'RANDOM_32_CHAR_STRING';
```

### 4. โฟลเดอร์ upload
```bash
mkdir -p public/uploads/items public/uploads/avatars
chmod 755 public/uploads/items public/uploads/avatars
```

### 5. วาง CodeIgniter 3 core
ดาวน์โหลดจาก https://codeigniter.com/download  
วาง `system/` และ `index.php` ที่ root ของโปรเจกต์

### 6. สร้าง Admin User
```sql
INSERT INTO users (role, name, email, password_hash, email_verified, is_active)
VALUES ('admin','แอดมิน','admin@khongluea.com',
  '$2y$10$REPLACE_WITH_BCRYPT_HASH', 1, 1);
```
Generate hash: `php -r "echo password_hash('yourpassword', PASSWORD_BCRYPT);"`

---

## สีและ Design

| ตัวแปร | ค่า | ใช้สำหรับ |
|--------|-----|-----------|
| `--kl-green` | `#1a9e5c` | สีหลัก brand |
| `--kl-green-dark` | `#157a47` | hover state |
| `--kl-orange` | `#f97316` | CTA buttons |
| `--kl-yellow` | `#fbbf24` | premium badges |

---

&copy; ของเหลือ · Khong Luea
