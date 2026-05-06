<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'ของเหลือ') ?> | ของเหลือ</title>
    <?= csrf_meta_tag() ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <style>
      body { background: linear-gradient(135deg, #e8f7ef 0%, #f0f8f0 100%); }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center py-4">
        <div class="col-md-5 col-lg-4">

            <!-- Brand -->
            <div class="text-center mb-4">
                <a href="<?= site_url('/') ?>" class="text-decoration-none d-inline-flex flex-column align-items-center gap-1">
                    <div style="width:56px;height:56px;background:var(--kl-green);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:26px;color:#fff;margin-bottom:4px;">
                        🏷️
                    </div>
                    <span style="font-size:1.5rem;font-weight:700;color:var(--kl-green);line-height:1;">ของเหลือ</span>
                    <span style="font-size:.7rem;color:var(--kl-text-muted);letter-spacing:.5px;">KHONG LUEA</span>
                </a>
            </div>

            <?= $this->load->view('partials/flash_message', [], TRUE) ?>
            <?= $this->load->view($content_view, [], TRUE) ?>

            <p class="text-center mt-3" style="font-size:.75rem;color:var(--kl-text-muted);">
                &copy; <?= date('Y') ?> ของเหลือ · Khong Luea
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
