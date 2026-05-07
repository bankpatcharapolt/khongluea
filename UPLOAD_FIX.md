# แก้ปัญหาอัปโหลดรูปไม่ได้บน XAMPP

## ขั้นตอนที่ 1 — ทดสอบ diagnose
เปิด: http://localhost/khongluea/test_upload.php
แล้วลองอัปโหลดรูปดู จะเห็นว่าปัญหาอยู่ที่ไหน

## ขั้นตอนที่ 2 — แก้ php.ini (ถ้าจำเป็น)
เปิดไฟล์: `C:\xampp\php\php.ini`

หาและแก้ค่าเหล่านี้:
```ini
file_uploads = On
upload_max_filesize = 10M
post_max_size = 20M
upload_tmp_dir = "C:\xampp\tmp"
```

แล้ว **Restart Apache** ใน XAMPP Control Panel

## ขั้นตอนที่ 3 — ตรวจสิทธิ์ folder
ตรวจว่า folder เหล่านี้มีอยู่และ Apache เขียนได้:
- `C:\xampp\htdocs\khongluea\uploads\`
- `C:\xampp\htdocs\khongluea\uploads\items\`
- `C:\xampp\htdocs\khongluea\uploads\avatars\`

วิธีสร้าง folder บน Windows CMD:
```
mkdir C:\xampp\htdocs\khongluea\uploads\items
mkdir C:\xampp\htdocs\khongluea\uploads\avatars
```

## ขั้นตอนที่ 4 — ดู Error Log
ถ้ายังไม่ได้ ดู log ได้ที่:
- `C:\xampp\htdocs\khongluea\application\logs\log-[วันที่].php`
- `C:\xampp\apache\logs\error.log`

## ขั้นตอนที่ 5 — ลบ debug tool
เมื่ออัปโหลดได้แล้ว ให้ลบไฟล์: `test_upload.php`
และเปลี่ยน log_threshold กลับเป็น 1 ใน `application/config/config.php`
