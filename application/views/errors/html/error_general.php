<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Error — ของเหลือ</title>
<style>
body{font-family:'Segoe UI',sans-serif;background:#f4f6f4;color:#1a2e1a;margin:0;padding:40px 20px;}
.box{max-width:600px;margin:0 auto;background:#fff;border-radius:12px;padding:40px;border-top:4px solid #1877f2;box-shadow:0 2px 16px rgba(26,158,92,.10);}
.brand{font-size:1.3rem;font-weight:700;color:#1877f2;margin-bottom:24px;}
h1{font-size:1.2rem;color:#dc3545;margin:0 0 12px;}
p{line-height:1.7;color:#555;margin:0;}
.back{display:inline-block;margin-top:20px;padding:8px 20px;background:#1877f2;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;font-size:.88rem;}
</style>
</head>
<body>
<div class="box">
    <div class="brand">🏷️ ของเหลือ</div>
    <h1>เกิดข้อผิดพลาด</h1>
    <p><?php echo $message; ?></p>
    <a href="javascript:history.back()" class="back">← ย้อนกลับ</a>
</div>
</body>
</html>
