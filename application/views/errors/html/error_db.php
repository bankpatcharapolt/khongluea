<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Database Error — ของเหลือ</title>
<style>
body{font-family:'Segoe UI',sans-serif;background:#f4f6f4;margin:0;padding:40px 20px;}
.box{max-width:640px;margin:0 auto;background:#fff;border-radius:12px;padding:40px;border-top:4px solid #dc3545;box-shadow:0 2px 16px rgba(0,0,0,.08);}
.brand{font-size:1.3rem;font-weight:700;color:#1a9e5c;margin-bottom:24px;}
h1{font-size:1.2rem;color:#dc3545;margin:0 0 8px;}
.code{background:#f8f9fa;border:1px solid #dee2e6;border-radius:6px;padding:12px 16px;font-family:monospace;font-size:.85rem;color:#555;margin-top:12px;white-space:pre-wrap;word-break:break-all;}
</style>
</head>
<body>
<div class="box">
    <div class="brand">🏷️ ของเหลือ</div>
    <h1>🗄️ Database Error</h1>
    <p style="color:#555;">เกิดปัญหาในการเชื่อมต่อฐานข้อมูล กรุณาตรวจสอบการตั้งค่า database.php</p>
    <?php if (defined('ENVIRONMENT') && ENVIRONMENT !== 'production'): ?>
    <div class="code"><?php echo $message; ?></div>
    <?php endif; ?>
</div>
</body>
</html>
