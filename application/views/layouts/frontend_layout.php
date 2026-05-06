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
</head>
<body>

<!-- NAVBAR -->
<?= $this->load->view('partials/navbar', [], TRUE) ?>

<!-- FLASH MESSAGES -->
<div class="container-fluid px-3 px-md-4" style="max-width:1300px;margin:0 auto;">
    <div class="mt-2">
        <?= $this->load->view('partials/flash_message', [], TRUE) ?>
    </div>
</div>

<!-- MAIN CONTENT -->
<main>
    <?= $this->load->view($content_view, [], TRUE) ?>
</main>

<!-- FOOTER -->
<?= $this->load->view('partials/footer', [], TRUE) ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/js/app.js') ?>"></script>
</body>
</html>
