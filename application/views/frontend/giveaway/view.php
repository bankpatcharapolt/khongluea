<?php
$user   = current_user();
$left   = (int)$listing['quantity_left'];
$ended  = strtotime($listing['pickup_end']) < time();
$active = ($listing['status'] === 'active' && $left > 0 && !$ended);
$img    = !empty($listing['primary_image'])
    ? base_url($listing['primary_image'])
    : base_url('assets/img/no-image.svg');
?>
<div class="container py-4" style="max-width:900px;">
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">หน้าหลัก</a></li>
      <li class="breadcrumb-item"><a href="<?= site_url('giveaway') ?>">รับของฟรี</a></li>
      <li class="breadcrumb-item active text-truncate" style="max-width:200px;"><?= htmlspecialchars($listing['title']) ?></li>
    </ol>
  </nav>

  <div class="row g-4">

    <!-- Images -->
    <div class="col-md-6">
      <?php if (!empty($listing['images'])): ?>
        <div id="gwCarousel" class="carousel slide rounded-3 overflow-hidden" style="border:1.5px solid var(--border);">
          <div class="carousel-inner">
            <?php foreach ($listing['images'] as $i => $img_row): ?>
              <div class="carousel-item <?= $i===0?'active':'' ?>">
                <img src="<?= base_url($img_row['image_path']) ?>" class="d-block w-100"
                     style="height:320px;object-fit:cover;" alt="">
              </div>
            <?php endforeach; ?>
          </div>
          <?php if (count($listing['images']) > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#gwCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#gwCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
            </button>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <img src="<?= $img ?>" class="rounded-3 w-100" style="height:320px;object-fit:cover;border:1.5px solid var(--border);" alt="">
      <?php endif; ?>
    </div>

    <!-- Info -->
    <div class="col-md-6">
      <div class="d-flex gap-2 mb-2 flex-wrap">
        <span class="badge-free">ฟรี!</span>
        <?php if ($listing['category_name']): ?>
          <span class="badge rounded-pill" style="background:var(--g-l);color:var(--g);font-size:.75rem;">
            <?= htmlspecialchars($listing['category_name']) ?>
          </span>
        <?php endif; ?>
      </div>

      <h4 class="fw-800 mb-2"><?= htmlspecialchars($listing['title']) ?></h4>

      <!-- Donor info -->
      <div class="kl-card p-3 mb-3">
        <div class="d-flex align-items-center gap-3">
          <?php if ($listing['donor_avatar']): ?>
            <img src="<?= base_url($listing['donor_avatar']) ?>" class="rounded-circle"
                 width="44" height="44" style="object-fit:cover;">
          <?php else: ?>
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-800"
                 style="width:44px;height:44px;background:var(--g);color:#fff;font-size:18px;flex-shrink:0;">
              <?= strtoupper(mb_substr($listing['donor_name'],0,1)) ?>
            </div>
          <?php endif; ?>
          <div>
            <div class="fw-700 small"><?= htmlspecialchars($listing['business_name'] ?: $listing['donor_name']) ?></div>
            <?php if ($listing['business_type']): ?>
              <div class="text-muted" style="font-size:.75rem;"><?= htmlspecialchars($listing['business_type']) ?></div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Qty + status -->
      <div class="mb-3">
        <?php $pct = $listing['quantity_total'] > 0 ? round($left/$listing['quantity_total']*100) : 0; ?>
        <div class="d-flex justify-content-between small mb-1">
          <span class="fw-600">จำนวนที่เหลือ</span>
          <span class="fw-700 text-primary"><?= $left ?> / <?= $listing['quantity_total'] ?></span>
        </div>
        <div class="progress" style="height:8px;border-radius:99px;">
          <div class="progress-bar" style="width:<?= $pct ?>%;background:var(--g);"></div>
        </div>
      </div>

      <!-- Time window -->
      <div class="kl-card p-3 mb-3">
        <div class="fw-700 small mb-2"><i class="bi bi-clock me-2 text-primary"></i>เวลารับของ</div>
        <div class="small">
          <strong>เริ่ม:</strong> <?= date('d M Y H:i', strtotime($listing['pickup_start'])) ?> น.<br>
          <strong>สิ้นสุด:</strong> <?= date('d M Y H:i', strtotime($listing['pickup_end'])) ?> น.
        </div>
      </div>

      <!-- Location -->
      <div class="kl-card p-3 mb-3">
        <div class="fw-700 small mb-1"><i class="bi bi-geo-alt-fill text-danger me-2"></i>สถานที่รับ</div>
        <div class="small text-muted"><?= htmlspecialchars($listing['pickup_address']) ?></div>
        <?php if ($listing['pickup_lat'] && $listing['pickup_lng']): ?>
          <a href="https://maps.google.com/?q=<?= $listing['pickup_lat'] ?>,<?= $listing['pickup_lng'] ?>"
             target="_blank" class="btn btn-sm mt-2"
             style="background:#4285f4;color:#fff;border-radius:7px;font-size:.78rem;">
            <i class="bi bi-map me-1"></i>ดูแผนที่
          </a>
        <?php endif; ?>
      </div>

      <!-- Action -->
      <?php if ($my_res && in_array($my_res['status'], ['pending','confirmed'])): ?>
        <div class="alert" style="background:var(--g-l);border:1.5px solid var(--g-m);border-radius:9px;">
          <div class="fw-700 small text-primary mb-1">✅ คุณจองแล้ว</div>
          <div class="small">Booking ID: <strong><?= $my_res['booking_ref'] ?></strong></div>
          <a href="<?= site_url('giveaway/qr/'.$my_res['booking_ref']) ?>" class="btn btn-sm btn-primary mt-2" style="border-radius:7px;">
            <i class="bi bi-qr-code me-1"></i>ดู QR Code
          </a>
        </div>

      <?php elseif (!$active): ?>
        <div class="alert alert-secondary text-center fw-600" style="border-radius:9px;">
          <?= $ended ? 'หมดเวลารับของแล้ว' : ($left === 0 ? 'ของหมดแล้ว' : 'ไม่พร้อมให้รับ') ?>
        </div>

      <?php elseif (!is_logged_in()): ?>
        <a href="<?= site_url('login') ?>" class="btn btn-primary w-100 fw-700" style="border-radius:9px;height:46px;font-size:1rem;">
          <i class="bi bi-box-arrow-in-right me-2"></i>เข้าสู่ระบบเพื่อจอง
        </a>

      <?php elseif ($user && (int)$user['id'] === (int)$listing['donor_user_id']): ?>
        <div class="alert alert-info text-center fw-600" style="border-radius:9px;">ของที่คุณลงไว้</div>

      <?php else: ?>
        <form method="post" action="<?= site_url('giveaway/reserve/'.$listing['id']) ?>">
          <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
          <button type="submit" class="btn btn-primary w-100 fw-800"
                  style="border-radius:9px;height:48px;font-size:1.05rem;"
                  onclick="this.disabled=true;this.form.submit();">
            <i class="bi bi-gift me-2"></i>จองรับฟรี!
          </button>
        </form>
        <p class="text-muted text-center mt-2" style="font-size:.75rem;">
          จำกัด 2 รายการต่อวัน · ต้องมารับเองที่ร้าน
        </p>
      <?php endif; ?>

    </div>
  </div>

  <!-- Description -->
  <?php if ($listing['description']): ?>
  <div class="kl-card p-4 mt-4">
    <h5 class="fw-700 mb-3">รายละเอียด</h5>
    <div class="text-muted" style="white-space:pre-line;line-height:1.8;">
      <?= htmlspecialchars($listing['description']) ?>
    </div>
  </div>
  <?php endif; ?>
</div>
