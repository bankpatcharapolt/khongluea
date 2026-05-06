<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="utf-8">
<title>Application Error</title>
<style>
body{font-family:'Segoe UI',sans-serif;background:#f8f9fa;color:#333;margin:0;padding:40px 20px;}
.box{max-width:600px;margin:0 auto;background:#fff;border-radius:12px;padding:32px;border-left:5px solid #dc3545;box-shadow:0 2px 12px rgba(0,0,0,.08);}
h1{color:#dc3545;font-size:1.4rem;margin:0 0 12px;}
p{line-height:1.7;color:#555;margin:0;}
</style>
</head>
<body>
<div class="box">
    <h1>⚠️ Application Error</h1>
    <p><?php echo $message; ?></p>
</div>
</body>
</html>
