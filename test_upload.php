<?php
/**
 * ของเหลือ — Upload Debug Tool
 * เข้าถึงได้ที่: http://localhost/khongluea/test_upload.php
 * ⚠️ ลบไฟล์นี้ออกเมื่อ upload ทำงานได้แล้ว!
 */

$upload_dir = __DIR__ . '/uploads/items/';
$results = [];

// 1. ตรวจ PHP settings
$results['php_version']       = PHP_VERSION;
$results['upload_max_size']   = ini_get('upload_max_filesize');
$results['post_max_size']     = ini_get('post_max_size');
$results['file_uploads']      = ini_get('file_uploads') ? 'ON' : 'OFF';
$results['tmp_dir']           = ini_get('upload_tmp_dir') ?: sys_get_temp_dir();
$results['tmp_writable']      = is_writable(ini_get('upload_tmp_dir') ?: sys_get_temp_dir()) ? '✅ OK' : '❌ NOT WRITABLE';

// 2. ตรวจ upload folder
$results['upload_dir']        = $upload_dir;
$results['dir_exists']        = is_dir($upload_dir)       ? '✅ EXISTS' : '❌ NOT FOUND';
$results['dir_writable']      = is_writable($upload_dir)  ? '✅ WRITABLE' : '❌ NOT WRITABLE';

// 3. ทดสอบ write จริงๆ
$test_file = $upload_dir . 'write_test_' . time() . '.txt';
$write_ok  = @file_put_contents($test_file, 'test');
$results['write_test'] = ($write_ok !== FALSE) ? '✅ CAN WRITE' : '❌ CANNOT WRITE';
if ($write_ok !== FALSE) @unlink($test_file);

// 4. รับไฟล์ที่ upload มา (ถ้ามี)
if (!empty($_FILES['test_file'])) {
    $f = $_FILES['test_file'];
    $results['uploaded_file']  = $f['name'];
    $results['uploaded_size']  = number_format($f['size']) . ' bytes';
    $results['uploaded_error'] = $f['error'] === 0 ? '✅ No error' : '❌ Error code: ' . $f['error'];
    $results['uploaded_tmp']   = $f['tmp_name'];
    $results['tmp_exists']     = file_exists($f['tmp_name']) ? '✅ EXISTS' : '❌ NOT FOUND';

    if ($f['error'] === 0 && file_exists($f['tmp_name'])) {
        $dest = $upload_dir . 'test_' . time() . '_' . basename($f['name']);
        $moved = move_uploaded_file($f['tmp_name'], $dest);
        $results['move_result'] = $moved ? '✅ SUCCESS → ' . $dest : '❌ FAILED';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Upload Debug — ของเหลือ</title>
<style>
body{font-family:monospace;padding:30px;background:#f5f7f5;}
h2{color:#00a046;}
table{border-collapse:collapse;width:100%;max-width:800px;}
td{padding:8px 12px;border-bottom:1px solid #ddd;}
td:first-child{font-weight:bold;width:220px;color:#555;}
.ok{color:green;} .err{color:red;}
form{margin-top:20px;padding:20px;background:#fff;border-radius:8px;max-width:500px;}
</style>
</head>
<body>
<h2>🔧 Upload Debug Tool</h2>
<table>
<?php foreach ($results as $k => $v): ?>
<tr>
  <td><?= $k ?></td>
  <td class="<?= strpos($v, '✅') !== false ? 'ok' : (strpos($v, '❌') !== false ? 'err' : '') ?>">
    <?= htmlspecialchars((string)$v) ?>
  </td>
</tr>
<?php endforeach; ?>
</table>

<form method="post" enctype="multipart/form-data">
  <h3>ทดสอบอัปโหลดไฟล์จริง</h3>
  <input type="file" name="test_file" accept="image/*" style="margin-bottom:10px;display:block;">
  <button type="submit" style="background:#00a046;color:#fff;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;">
    ทดสอบ Upload
  </button>
</form>

<p style="color:#e53935;margin-top:20px;font-size:12px;">
  ⚠️ ลบไฟล์ test_upload.php ออกเมื่อทดสอบเสร็จแล้ว!
</p>
</body>
</html>
