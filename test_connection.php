<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo '<pre style="font:14px monospace;padding:20px;">';
echo "PHP: " . PHP_VERSION . "\n";

// ทดสอบ localhost
echo "\n--- Testing localhost ---\n";
$c1 = @new mysqli('localhost', 'root', '', 'khongluea');
echo $c1->connect_error ? "❌ localhost: " . $c1->connect_error . "\n" : "✅ localhost: OK\n";

// ทดสอบ 127.0.0.1
echo "\n--- Testing 127.0.0.1 ---\n";
$c2 = @new mysqli('127.0.0.1', 'root', '', 'khongluea');
echo $c2->connect_error ? "❌ 127.0.0.1: " . $c2->connect_error . "\n" : "✅ 127.0.0.1: OK\n";

if (!$c2->connect_error) {
    $r = $c2->query("SELECT COUNT(*) c FROM categories");
    echo "categories: " . $r->fetch_assoc()['c'] . " rows\n";
    
    // ตรวจ items columns
    $r2 = $c2->query("SHOW COLUMNS FROM items");
    $cols = array_column($r2->fetch_all(MYSQLI_ASSOC), 'Field');
    echo "\nitems columns: " . implode(', ', $cols) . "\n";
    
    $need = ['is_free','is_bumped','is_featured','is_highlighted','view_count'];
    foreach ($need as $col) {
        echo (in_array($col,$cols) ? "✅" : "❌ MISSING") . " $col\n";
    }
}
echo "\n⚠️ ลบไฟล์นี้หลังดูแล้ว!\n</pre>";
