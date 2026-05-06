<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>เกิดข้อผิดพลาด — ของเหลือ</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Segoe UI',sans-serif;background:#f5f7f5;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
.box{background:#fff;border-radius:16px;padding:2.5rem 2rem;max-width:600px;width:100%;border-left:5px solid #e53935;box-shadow:0 4px 24px rgba(0,0,0,.08);}
.brand{color:#00a046;font-size:1.1rem;font-weight:700;margin-bottom:1.25rem;}
h2{color:#e53935;font-size:1.2rem;margin-bottom:.75rem;display:flex;align-items:center;gap:.5rem;}
.msg{background:#fff5f5;border:1px solid #fcc;border-radius:8px;padding:.9rem 1rem;font-size:.85rem;color:#555;line-height:1.7;word-break:break-all;}
.detail{margin-top:.75rem;font-size:.78rem;color:#888;}
.btn{display:inline-block;margin-top:1.5rem;background:#00a046;color:#fff;padding:.6rem 1.5rem;border-radius:9px;text-decoration:none;font-weight:600;font-size:.9rem;}
.btn:hover{background:#008c3a;}
</style>
</head>
<body>
<div class="box">
    <div class="brand">🏷️ ของเหลือ</div>
    <h2>⚠️ Application Error</h2>
    <div class="msg"><?php echo htmlspecialchars($message ?? ($exception->getMessage() ?? 'Unknown error')); ?></div>
    <?php if (defined('ENVIRONMENT') && ENVIRONMENT === 'development' && isset($exception)): ?>
    <div class="detail">
        <strong>File:</strong> <?php echo htmlspecialchars($exception->getFile()); ?><br>
        <strong>Line:</strong> <?php echo $exception->getLine(); ?>
    </div>
    <?php endif; ?>
    <a href="javascript:history.back()" class="btn">← ย้อนกลับ</a>
</div>
</body>
</html>
