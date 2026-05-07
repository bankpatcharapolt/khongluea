<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'ของเหลือ') ?> | ของเหลือ</title>
  <?= csrf_meta_tag() ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
  <style>
    body { background: linear-gradient(135deg, #e6f7ee 0%, #f5f7f5 60%); min-height:100vh; }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">
      <!-- <div class="text-center mb-4">
        <a href="<?= site_url('/') ?>" class="text-decoration-none d-flex flex-column align-items-center">
          <img src="<?= base_url('assets/img/logo-vertical.png') ?>" alt="ของเหลือ"
               style="width:140px;object-fit:contain;margin-bottom:.2rem;">
        </a>
      </div> -->
      <?= $this->load->view('partials/flash_message',[],TRUE) ?>
      <div class="kl-auth-card"><?= $this->load->view($content_view,[],TRUE) ?></div>
      <p class="text-center mt-3" style="font-size:.75rem;color:var(--muted);">&copy; <?= date('Y') ?> ของเหลือ · Khong Luea</p>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
