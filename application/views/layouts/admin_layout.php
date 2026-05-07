<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title??'Dashboard') ?> — ของเหลือ Admin</title>
  <?= csrf_meta_tag() ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body>
<div id="admin-wrapper">
  <!-- Sidebar -->
  <div id="admin-sidebar">
    <div class="sidebar-brand">
      <a href="<?= site_url('/') ?>" class="brand-name">
        <div class="brand-logo-sm">🏷️</div>
        <div><div>ของเหลือ</div><div class="brand-sub">ADMIN PANEL</div></div>
      </a>
    </div>
    <ul class="nav flex-column">
      <?php
      $nav = [
        ['admin','bi-speedometer2','แดชบอร์ด'],
        ['admin/users','bi-people','ผู้ใช้'],
        ['admin/items','bi-grid','สินค้า / ประกาศ'],
        ['admin/categories','bi-tags','หมวดหมู่'],
        ['admin/reports','bi-flag','รายงาน'],
        ['admin/packages','bi-star','แพ็กเกจ'],
        ['admin/credits','bi-coin','เครดิต'],
      ];
      foreach ($nav as [$url,$icon,$label]):
        $active = (strpos(uri_string(), $url) !== FALSE && $url !== 'admin') || uri_string() === $url;
      ?>
      <li><a class="nav-link <?= $active?'active':'' ?>" href="<?= site_url($url) ?>">
        <i class="bi <?= $icon ?>"></i><?= $label ?>
      </a></li>
      <?php endforeach; ?>
    </ul>
    <div class="sidebar-footer">
      <a href="<?= site_url('logout') ?>" class="btn btn-sm w-100 fw-600"
         style="background:rgba(255,255,255,.1);color:rgba(255,255,255,.65);border:none;border-radius:8px;font-size:.82rem;">
        <i class="bi bi-box-arrow-right me-1"></i>ออกจากระบบ
      </a>
    </div>
  </div>
  <!-- Content -->
  <div id="admin-content">
    <div id="admin-topbar">
      <div class="page-title"><?= htmlspecialchars($title??'') ?></div>
      <div class="d-flex align-items-center gap-3">
        <span class="text-muted small"><?= htmlspecialchars(current_user()['name']??'') ?></span>
        <a href="<?= site_url('/') ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
          <i class="bi bi-box-arrow-up-right me-1"></i>ดูหน้าเว็บ
        </a>
      </div>
    </div>
    <div class="admin-body">
      <?= $this->load->view('partials/flash_message',[],TRUE) ?>
      <?= $this->load->view($content_view,[],TRUE) ?>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/admin.js') ?>"></script>
</body>
</html>
