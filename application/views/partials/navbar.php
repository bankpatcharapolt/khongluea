<!-- MAIN NAVBAR -->
<nav class="kl-navbar navbar navbar-expand-lg">
    <div class="container-fluid px-3" style="max-width:1300px;margin:0 auto;gap:.75rem;">

        <!-- Brand -->
        <a class="navbar-brand" href="<?= site_url('/') ?>">
            <div class="brand-logo">🏷️</div>
            <div class="brand-th">
                ของเหลือ
                <span>Khong Luea</span>
            </div>
        </a>

        <!-- Search (desktop) -->
        <form class="kl-search d-none d-lg-flex flex-grow-1 mx-3" action="<?= site_url('items') ?>" method="get"
              style="max-width:480px;">
            <input class="form-control" type="search" name="q" placeholder="ค้นหาของที่ต้องการ…"
                   value="<?= htmlspecialchars($this->input->get('q', TRUE) ?? '') ?>">
            <button type="submit"><i class="bi bi-search"></i></button>
        </form>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#klNav"
                style="color:rgba(255,255,255,.8);">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="collapse navbar-collapse" id="klNav">
            <!-- Mobile search -->
            <form class="kl-search d-flex d-lg-none my-2 mx-1" action="<?= site_url('items') ?>" method="get">
                <input class="form-control" type="search" name="q" placeholder="ค้นหา…"
                       value="<?= htmlspecialchars($this->input->get('q', TRUE) ?? '') ?>">
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link" href="<?= site_url('items') ?>">
                        <i class="bi bi-grid me-1"></i>เลือกชม
                    </a>
                </li>

                <?php if (is_logged_in()): ?>
                    <?php $user = current_user(); ?>

                    <!-- Post button -->
                    <li class="nav-item">
                        <a class="btn-post btn rounded-2 ms-1" href="<?= site_url('items/create') ?>">
                            <i class="bi bi-plus-lg me-1"></i>ลงของ
                        </a>
                    </li>

                    <!-- Chat -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="<?= site_url('chat') ?>" title="ข้อความ">
                            <i class="bi bi-chat-dots fs-5"></i>
                        </a>
                    </li>

                    <!-- Favorites -->
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('favorites') ?>" title="ของที่ถูกใจ">
                            <i class="bi bi-heart fs-5"></i>
                        </a>
                    </li>

                    <!-- User dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                           data-bs-toggle="dropdown" style="color:#fff;">
                            <?php if ($user['avatar']): ?>
                                <img src="<?= base_url($user['avatar']) ?>" class="rounded-circle"
                                     width="28" height="28" style="object-fit:cover;" alt="">
                            <?php else: ?>
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold"
                                     style="width:28px;height:28px;font-size:13px;background:rgba(255,255,255,.22);color:#fff;">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <span class="d-none d-lg-inline"><?= htmlspecialchars($user['name']) ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg" style="min-width:200px;border-radius:12px;">
                            <li class="px-3 pt-2 pb-1">
                                <div class="fw-semibold small"><?= htmlspecialchars($user['name']) ?></div>
                                <div class="text-muted" style="font-size:.75rem;"><?= htmlspecialchars($user['email']) ?></div>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li><a class="dropdown-item" href="<?= site_url('profile/' . urlencode($user['name'])) ?>">
                                <i class="bi bi-person me-2 text-muted"></i>โปรไฟล์ของฉัน</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('profile/my-listings') ?>">
                                <i class="bi bi-grid me-2 text-muted"></i>ของที่ลงไว้</a></li>
                            <li><a class="dropdown-item" href="<?= site_url('credits') ?>">
                                <i class="bi bi-coin me-2 text-warning"></i>เครดิต:
                                <strong class="text-green"><?= number_format($user['credits']) ?></strong></a></li>
                            <li><a class="dropdown-item" href="<?= site_url('premium') ?>">
                                <i class="bi bi-star me-2 text-warning"></i>แพ็กเกจพรีเมียม</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <?php if (is_admin()): ?>
                                <li><a class="dropdown-item text-danger" href="<?= site_url('admin') ?>">
                                    <i class="bi bi-shield-lock me-2"></i>Admin Panel</a></li>
                                <li><hr class="dropdown-divider my-1"></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item text-muted" href="<?= site_url('logout') ?>">
                                <i class="bi bi-box-arrow-right me-2"></i>ออกจากระบบ</a></li>
                        </ul>
                    </li>

                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= site_url('login') ?>">เข้าสู่ระบบ</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn-post btn rounded-2" href="<?= site_url('register') ?>">สมัครสมาชิก</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- CATEGORY BAR -->
<?php
$this->load->model('Category_model');
$_cats = $this->Category_model->get_all_active();
if (!empty($_cats)):
?>
<div class="kl-catbar">
    <div style="display:inline-flex;padding:0 .5rem;gap:0;">
        <a href="<?= site_url('items') ?>" class="<?= !$this->input->get('category_id') ? 'active' : '' ?>">
            <i class="bi bi-grid-3x3-gap me-1"></i>ทั้งหมด
        </a>
        <a href="<?= site_url('items?is_free=1') ?>">
            <i class="bi bi-gift me-1"></i>แจกฟรี
        </a>
        <?php foreach ($_cats as $c): ?>
        <a href="<?= site_url('items?category_id=' . $c['id']) ?>"
           class="<?= $this->input->get('category_id') == $c['id'] ? 'active' : '' ?>">
            <i class="bi <?= htmlspecialchars($c['icon']) ?> me-1"></i><?= htmlspecialchars($c['name']) ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
