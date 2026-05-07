<?php
/**
 * ของเหลือ — PHP Diagnostic Tool
 * http://localhost/khongluea/diag.php
 * ⚠️ ลบออกหลังแก้ปัญหา!
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo '<h2 style="font-family:monospace;color:#00a046;">🔧 ของเหลือ Diagnostic</h2>';
echo '<pre style="font-family:monospace;font-size:13px;background:#f5f5f5;padding:20px;">';

// PHP Info
echo "PHP Version: " . PHP_VERSION . "\n";
echo "OS: " . PHP_OS . "\n";
echo "FCPATH (index.php dir): " . __DIR__ . "\n\n";

// ตรวจ extensions
$required = ['mysqli','pdo','json','mbstring','fileinfo'];
echo "Extensions:\n";
foreach ($required as $ext) {
    echo "  " . ($ext . ': ') . (extension_loaded($ext) ? "✅" : "❌ MISSING") . "\n";
}

// ตรวจ folders
echo "\nFolders:\n";
$folders = [
    'system'              => 'system/',
    'application'         => 'application/',
    'uploads/items'       => 'uploads/items/',
    'uploads/avatars'     => 'uploads/avatars/',
    'assets/css'          => 'assets/css/',
    'error views'         => 'application/views/errors/html/',
];
foreach ($folders as $name => $path) {
    $full = __DIR__ . '/' . $path;
    echo "  $name: " . (is_dir($full) ? "✅ exists" : "❌ MISSING ($full)") . "\n";
}

// ตรวจไฟล์สำคัญ
echo "\nCritical files:\n";
$files = [
    'index.php'              => 'index.php',
    '.htaccess'              => '.htaccess',
    'error_php.php'          => 'application/views/errors/html/error_php.php',
    'error_404.php'          => 'application/views/errors/html/error_404.php',
    'error_exception.php'    => 'application/views/errors/html/error_exception.php',
    'Welcome.php controller' => 'application/controllers/Welcome.php',
    'config.php'             => 'application/config/config.php',
    'database.php'           => 'application/config/database.php',
    'app.css'                => 'assets/css/app.css',
];
foreach ($files as $name => $path) {
    $full = __DIR__ . '/' . $path;
    $size = file_exists($full) ? number_format(filesize($full)) . ' bytes' : '';
    echo "  $name: " . (file_exists($full) ? "✅ $size" : "❌ MISSING") . "\n";
}

// ตรวจ DB connection
echo "\nDatabase:\n";
try {
    $cfg = include __DIR__ . '/application/config/database.php';
    $db = $db ?? null;
    if (isset($db['default'])) {
        $d = $db['default'];
        $conn = @new mysqli($d['hostname'], $d['username'], $d['password'], $d['database']);
        if ($conn->connect_error) {
            echo "  ❌ Connection failed: " . $conn->connect_error . "\n";
        } else {
            echo "  ✅ Connected to " . $d['database'] . "\n";
            $r = $conn->query("SELECT COUNT(*) as c FROM categories");
            $row = $r ? $r->fetch_assoc() : null;
            echo "  Categories count: " . ($row ? $row['c'] : '?') . "\n";
        }
    }
} catch (Throwable $e) {
    echo "  ❌ Error: " . $e->getMessage() . "\n";
}

echo "\n✅ Diagnostic complete.\n";
echo "⚠️  ลบไฟล์ diag.php นี้หลังแก้ปัญหา!\n";
echo '</pre>';
