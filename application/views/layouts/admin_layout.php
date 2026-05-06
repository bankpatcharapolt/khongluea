<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Dashboard') ?> — ของเหลือ Admin</title>
    <?= csrf_meta_tag() ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body>

<div class="d-flex" id="admin-wrapper">

    <!-- Sidebar -->
    <div id="admin-sidebar">
        <div class="sidebar-brand">
            <a href="<?= site_url('/') ?>" class="brand-name">
                <div class="brand-logo-sm">🏷️</div>
                <div>
                    <div>ของเหลือ</div>
                    <div class="brand-sub">ADMIN PANEL</div>
                </div>
            </a>
        </div>

        <ul class="nav flex-column mt-1">
            <li><a class="nav-link <?= (uri_string() === 'admin' || uri_string() === 'admin/dashboard') ? 'active' : '' ?>"
                   href="<?= site_url('admin') ?>"><i class="bi bi-speedometer2"></i>แดชบอร์ด</a></li>
            <li><a class="nav-link <?= strpos(uri_string(),'admin/users') !== FALSE ? 'active' : '' ?>"
                   href="<?= site_url('admin/users') ?>"><i class="bi bi-people"></i>ผู้ใช้</a></li>
            <li><a class="nav-link <?= strpos(uri_string(),'admin/items') !== FALSE ? 'active' : '' ?>"
                   href="<?= site_url('admin/items') ?>"><i class="bi bi-grid"></i>สินค้า / ของ</a></li>
            <li><a class="nav-link <?= strpos(uri_string(),'admin/categories') !== FALSE ? 'active' : '' ?>"
                   href="<?= site_url('admin/categories') ?>"><i class="bi bi-tags"></i>หมวดหมู่</a></li>
            <li><a class="nav-link <?= strpos(uri_string(),'admin/reports') !== FALSE ? 'active' : '' ?>"
                   href="<?= site_url('admin/reports') ?>"><i class="bi bi-flag"></i>รายงาน</a></li>
            <li><a class="nav-link <?= strpos(uri_string(),'admin/packages') !== FALSE ? 'active' : '' ?>"
                   href="<?= site_url('admin/packages') ?>"><i class="bi bi-star"></i>แพ็กเกจพรีเมียม</a></li>
            <li><a class="nav-link <?= strpos(uri_string(),'admin/credits') !== FALSE ? 'active' : '' ?>"
                   href="<?= site_url('admin/credits') ?>"><i class="bi bi-coin"></i>เครดิต</a></li>
        </ul>

        <div class="sidebar-footer">
            <a href="<?= site_url('logout') ?>" class="btn btn-sm w-100"
               style="background:rgba(255,255,255,.12);color:rgba(255,255,255,.75);border:none;border-radius:8px;font-size:.82rem;">
                <i class="bi bi-box-arrow-right me-1"></i>ออกจากระบบ
            </a>
        </div>
    </div>

    <!-- Main content -->
    <div id="admin-content" class="flex-grow-1 d-flex flex-column">
        <!-- Top bar -->
        <div id="admin-topbar">
            <div class="page-title"><?= htmlspecialchars($title ?? '') ?></div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted small"><?= htmlspecialchars(current_user()['name'] ?? '') ?></span>
                <a href="<?= site_url('/') ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-1"></i>ดูหน้าเว็บ
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="admin-body flex-grow-1">
            <?= $this->load->view('partials/flash_message', [], TRUE) ?>
            <?= $this->load->view($content_view, [], TRUE) ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
</body>
</html>
