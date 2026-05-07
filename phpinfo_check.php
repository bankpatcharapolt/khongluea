<?php
// ของเหลือ — PHP Version Check
// เข้าถึงได้ที่: https://khongluea.com/phpinfo_check.php
// ⚠️ ลบออกทันทีหลังดูแล้ว!

echo '<pre style="font-family:monospace;padding:20px;">';
echo 'PHP Version: ' . PHP_VERSION . "\n";
echo 'PHP Major: ' . PHP_MAJOR_VERSION . "\n";
echo 'PHP Minor: ' . PHP_MINOR_VERSION . "\n\n";

echo 'Error reporting: ' . error_reporting() . "\n";
echo 'Display errors: ' . ini_get('display_errors') . "\n\n";

// ตรวจว่า deprecated จะเกิดไหม
$test = new stdClass();
$test->dynamic_prop = 'test'; // จะ deprecated ใน PHP 8.2+
echo "Dynamic property test: OK\n\n";

echo 'Server: ' . ($_SERVER['SERVER_SOFTWARE'] ?? 'unknown') . "\n";
echo '</pre>';
// ⚠️ ลบไฟล์นี้ออกทันที!
