<div class="container py-4">
  <div class="row g-4">

    <!-- Profile Sidebar -->
    <div class="col-lg-3">
      <div class="kl-card text-center p-4 mb-3">
        <?php if ($profile['avatar']): ?>
          <img src="<?= base_url($profile['avatar']) ?>" class="rounded-circle mb-3"
               width="88" height="88" style="object-fit:cover;border:3px solid var(--g-m);" alt="">
        <?php else: ?>
          <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center fw-800"
               style="width:88px;height:88px;background:var(--g);color:#fff;font-size:32px;">
            <?= strtoupper(mb_substr($profile['name'],0,1)) ?>
          </div>
        <?php endif; ?>

        <h5 class="fw-800 mb-0"><?= htmlspecialchars($profile['name']) ?></h5>
        <?php if ($profile['city']): ?>
          <div class="text-muted small mt-1">
            <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($profile['city']) ?>
          </div>
        <?php endif; ?>
        <?php if ($profile['premium_status']): ?>
          <span class="badge mt-2 px-3 py-1" style="background:linear-gradient(135deg,#f97316,#fbbf24);font-size:.78rem;">
            ⭐ Premium
          </span>
        <?php endif; ?>
        <div class="text-muted small mt-2">
          สมาชิกตั้งแต่ <?= date('M Y', strtotime($profile['created_at'])) ?>
        </div>
        <?php if ($profile['bio']): ?>
          <p class="text-muted small mt-3 mb-0 text-start"><?= nl2br(htmlspecialchars($profile['bio'])) ?></p>
        <?php endif; ?>
      </div>

      <?php if ($is_own_profile): ?>
      <div class="kl-card overflow-hidden">
        <a href="<?= site_url('profile/my-listings') ?>"
           class="d-flex align-items-center gap-2 p-3 text-decoration-none border-bottom"
           style="color:var(--text);<?= strpos(uri_string(),'my-listings') !== FALSE ? 'background:var(--g-l);color:var(--g);font-weight:600;' : '' ?>">
          <i class="bi bi-grid"></i> ของที่ลงไว้
          <span class="badge ms-auto" style="background:var(--g);"><?= count($items) ?></span>
        </a>
        <a href="<?= site_url('account/settings') ?>"
           class="d-flex align-items-center gap-2 p-3 text-decoration-none border-bottom"
           style="color:var(--text);">
          <i class="bi bi-gear"></i> ตั้งค่าบัญชี
        </a>
        <a href="<?= site_url('credits') ?>"
           class="d-flex align-items-center gap-2 p-3 text-decoration-none border-bottom"
           style="color:var(--text);">
          <i class="bi bi-coin text-warning"></i> เครดิต
          <span class="fw-700 ms-auto text-primary"><?= number_format($profile['credits']) ?></span>
        </a>
        <a href="<?= site_url('premium') ?>"
           class="d-flex align-items-center gap-2 p-3 text-decoration-none"
           style="color:var(--text);">
          <i class="bi bi-star text-warning"></i> แพ็กเกจพรีเมียม
        </a>
      </div>
      <?php endif; ?>
    </div>

    <!-- Listings -->
    <div class="col-lg-9">
      <div class="kl-section-head mb-3">
        <h5 class="fw-800 mb-0">
          <?= $is_own_profile ? 'ของที่ลงไว้' : ('สินค้าของ ' . htmlspecialchars($profile['name'])) ?>
          <span class="badge ms-2 rounded-pill" style="background:var(--g);font-size:.72rem;">
            <?= count($items) ?>
          </span>
        </h5>
        <?php if ($is_own_profile): ?>
          <a href="<?= site_url('items/create') ?>" class="btn btn-primary btn-sm" style="border-radius:9px;">
            <i class="bi bi-plus-lg me-1"></i>ลงของใหม่
          </a>
        <?php endif; ?>
      </div>

      <?php if (empty($items)): ?>
        <div class="kl-empty py-5 kl-card">
          <i class="bi bi-grid"></i>
          <h6><?= $is_own_profile ? 'คุณยังไม่มีของลงไว้' : 'ยังไม่มีสินค้า' ?></h6>
          <?php if ($is_own_profile): ?>
            <p class="small text-muted mb-3">เริ่มลงของชิ้นแรกได้เลย!</p>
            <a href="<?= site_url('items/create') ?>" class="btn btn-primary px-4">ลงของเลย</a>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
          <?php foreach ($items as $item): ?>
          <div class="col">
            <?php
            // แสดงทุก status รวมถึง reserved และ sold
            echo $this->load->view('partials/item_card', ['item' => $item], TRUE);
            ?>
          </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
