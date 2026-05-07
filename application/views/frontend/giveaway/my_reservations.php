<div class="container py-4" style="max-width:700px;">
  <div class="d-flex align-items-center gap-3 mb-4">
    <div style="width:44px;height:44px;background:var(--g-l);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">🎁</div>
    <div>
      <h4 class="fw-800 mb-0">การจองของฉัน</h4>
      <p class="text-muted small mb-0">ประวัติการจองรับของฟรีทั้งหมด</p>
    </div>
  </div>

  <?php if (empty($reservations)): ?>
    <div class="kl-empty py-5 kl-card">
      <i class="bi bi-gift"></i>
      <h6>ยังไม่มีการจอง</h6>
      <a href="<?= site_url('giveaway') ?>" class="btn btn-primary px-4 mt-2">เลือกรับของฟรี</a>
    </div>
  <?php else: ?>
  <div class="kl-card overflow-hidden">
    <?php
    $status_cfg = [
      'pending'   => ['bg-warning text-dark','รอรับ'],
      'confirmed' => ['bg-success text-white','รับแล้ว'],
      'cancelled' => ['bg-secondary text-white','ยกเลิก'],
      'no_show'   => ['bg-danger text-white','ไม่มารับ'],
    ];
    foreach ($reservations as $i => $r):
      [$sc, $sl] = $status_cfg[$r['status']] ?? ['bg-light','?'];
    ?>
    <div class="d-flex gap-3 p-3 <?= $i > 0 ? 'border-top' : '' ?>">
      <!-- Image -->
      <div class="flex-shrink-0">
        <img src="<?= $r['listing_image'] ? base_url($r['listing_image']) : base_url('assets/img/no-image.svg') ?>"
             class="rounded" width="70" height="62" style="object-fit:cover;border:1.5px solid var(--border);" alt="">
      </div>
      <!-- Content -->
      <div class="flex-grow-1 min-w-0">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
          <a href="<?= site_url('giveaway/view/'.$r['listing_id']) ?>" class="fw-700 small text-decoration-none" style="color:var(--text);">
            <?= htmlspecialchars(mb_substr($r['listing_title'],0,40)) ?>
          </a>
          <span class="badge <?= $sc ?>" style="font-size:.68rem;"><?= $sl ?></span>
        </div>
        <div class="small text-muted mt-1">
          <i class="bi bi-shop me-1"></i><?= htmlspecialchars($r['donor_business'] ?? '') ?>
        </div>
        <div class="small text-muted">
          <i class="bi bi-clock me-1"></i>
          รับ <?= date('d M H:i', strtotime($r['pickup_start'])) ?> – <?= date('H:i', strtotime($r['pickup_end'])) ?>
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
          <span class="badge rounded-pill px-3" style="background:var(--g-l);color:var(--g);font-size:.72rem;">
            <?= htmlspecialchars($r['booking_ref']) ?>
          </span>
          <?php if ($r['status'] === 'pending'): ?>
            <a href="<?= site_url('giveaway/qr/'.$r['booking_ref']) ?>" class="btn btn-primary btn-sm" style="border-radius:7px;font-size:.75rem;padding:2px 10px;">
              <i class="bi bi-qr-code me-1"></i>QR
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
