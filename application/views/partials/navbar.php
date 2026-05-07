<nav class="kl-navbar navbar navbar-expand-lg">
  <div class="container-fluid px-3 px-lg-4 d-flex align-items-center gap-2 gap-lg-3" style="max-width:1400px;margin:0 auto;">

    <!-- Brand -->
    <a class="navbar-brand me-0" href="<?= site_url('/') ?>">
      <div class="brand-logo">
        <img src="<?= base_url('assets/img/logo-icon.png') ?>" alt="ของเหลือ"
             style="width:34px;height:34px;object-fit:contain;">
      </div>
      <div class="brand-th">ของเหลือ<span>KHONG LUEA</span></div>
    </a>

    <!-- Search (desktop) -->
    <div class="kl-search d-none d-lg-flex flex-grow-1 position-relative" style="max-width:420px;">
      <form action="<?= site_url('items') ?>" method="get" style="width:100%;">
        <input class="form-control" type="search" name="q" placeholder="ค้นหาของที่ต้องการ…"
               value="<?= htmlspecialchars($this->input->get('q', TRUE) ?? '') ?>">
        <button type="submit" class="btn-search"><i class="bi bi-search"></i></button>
      </form>
    </div>

    <button class="navbar-toggler border-0 ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#klNav"
            style="color:rgba(255,255,255,.9);font-size:1.4rem;padding:.2rem .4rem;">
      <i class="bi bi-list"></i>
    </button>

    <div class="collapse navbar-collapse" id="klNav">
      <!-- Mobile search -->
      <div class="kl-search d-flex d-lg-none position-relative my-2">
        <form action="<?= site_url('items') ?>" method="get" style="width:100%;">
          <input class="form-control" type="search" name="q" placeholder="ค้นหา…"
                 value="<?= htmlspecialchars($this->input->get('q', TRUE) ?? '') ?>">
          <button type="submit" class="btn-search"><i class="bi bi-search"></i></button>
        </form>
      </div>

      <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
        <li class="nav-item d-none d-lg-block">
          <a class="nav-link" href="<?= site_url('items') ?>">เลือกชม</a>
        </li>

        <?php if (is_logged_in()): ?>
          <?php $user = current_user(); ?>

          <li class="nav-item">
            <a class="btn-post ms-1" href="<?= site_url('items/create') ?>">
              <i class="bi bi-plus-lg"></i>ลงของ
            </a>
          </li>

          <!-- Chat + Unread badge -->
          <li class="nav-item position-relative">
            
            <a class="nav-link" href="<?= site_url('chat') ?>" title="ข้อความ">
              <i class="bi bi-chat-dots fs-5"></i>
              <?php
              $_unread = 0;
              try {
                  $_unread = (int)$this->Message_model->unread_count((int)$user['id']);
              } catch (Throwable $e) { $_unread = 0; }
              if ($_unread > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill"
                    style="background:var(--orange);font-size:.6rem;min-width:16px;padding:2px 5px;margin-left:-8px;margin-top:4px;">
                <?= $_unread > 99 ? '99+' : $_unread ?>
              </span>
              <?php endif; ?>
            </a>
          </li>

          <!-- Favorites -->
          <li class="nav-item">
            <a class="nav-link" href="<?= site_url('favorites') ?>" title="ของที่ถูกใจ">
              <i class="bi bi-heart fs-5"></i>
            </a>
          </li>

          <!-- User -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 pe-0" href="#" data-bs-toggle="dropdown">
              <?php if ($user['avatar']): ?>
                <img src="<?= base_url($user['avatar']) ?>" class="rounded-circle"
                     width="30" height="30" style="object-fit:cover;border:2px solid rgba(255,255,255,.5);" alt="">
              <?php else: ?>
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-800"
                     style="width:30px;height:30px;background:rgba(255,255,255,.25);color:#fff;font-size:13px;">
                  <?= strtoupper(mb_substr($user['name'],0,1)) ?>
                </div>
              <?php endif; ?>
              <span class="d-none d-xl-inline" style="font-size:.88rem;"><?= htmlspecialchars(explode(' ',$user['name'])[0]) ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li class="px-3 pt-2 pb-1">
                <div class="fw-700" style="font-size:.88rem;"><?= htmlspecialchars($user['name']) ?></div>
                <div class="text-muted" style="font-size:.73rem;"><?= htmlspecialchars($user['email']) ?></div>
              </li>
              <li><hr class="dropdown-divider my-1"></li>
              <li><a class="dropdown-item" href="<?= site_url('profile/'.urlencode($user['name'])) ?>"><i class="bi bi-person me-2 text-muted"></i>โปรไฟล์ของฉัน</a></li>
              <li><a class="dropdown-item" href="<?= site_url('profile/my-listings') ?>"><i class="bi bi-grid me-2 text-muted"></i>ของที่ลงไว้</a></li>
              <li><a class="dropdown-item" href="<?= site_url('credits') ?>">
                <i class="bi bi-coin me-2 text-warning"></i>เครดิต: <strong class="text-primary"><?= number_format($user['credits']) ?></strong></a></li>
              <li><a class="dropdown-item" href="<?= site_url('premium') ?>"><i class="bi bi-star me-2 text-warning"></i>แพ็กเกจพรีเมียม</a></li>
              <li><hr class="dropdown-divider my-1"></li>
              <?php if (is_admin()): ?>
                <li><a class="dropdown-item text-danger" href="<?= site_url('admin') ?>"><i class="bi bi-shield-lock me-2"></i>Admin Panel</a></li>
                <li><hr class="dropdown-divider my-1"></li>
              <?php endif; ?>
              <li><a class="dropdown-item" href="<?= site_url('logout') ?>"><i class="bi bi-box-arrow-right me-2 text-muted"></i>ออกจากระบบ</a></li>
            </ul>
          </li>

        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= site_url('login') ?>">เข้าสู่ระบบ</a></li>
          <li class="nav-item"><a class="btn-post" href="<?= site_url('register') ?>"><i class="bi bi-person-plus"></i>สมัครฟรี</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Category bar -->
<div class="kl-catbar">
  <div class="kl-catbar-inner">
    <a href="<?= site_url('items') ?>" class="<?= !$this->input->get('category_id') && !$this->input->get('is_free') ? 'active' : '' ?>">
      <i class="bi bi-grid-3x3-gap"></i> ทั้งหมด
    </a>
    <a href="<?= site_url('items?is_free=1') ?>" class="<?= $this->input->get('is_free')==='1' ? 'active' : '' ?>"
       style="<?= $this->input->get('is_free')==='1' ? '' : 'color:#00b14f;font-weight:600;' ?>">
      🎁 แจกฟรี
    </a>
    <?php
    try {
        $_cats = $this->Category_model->get_all_active();
    } catch (Throwable $e) {
        $_cats = [];
    }
    foreach ($_cats as $c): ?>
    <a href="<?= site_url('items?category_id='.$c['id']) ?>"
       class="<?= $this->input->get('category_id') == $c['id'] ? 'active' : '' ?>">
      <i class="bi <?= htmlspecialchars($c['icon']) ?>"></i> <?= htmlspecialchars($c['name']) ?>
    </a>
    <?php endforeach; ?>
  </div>
</div>
