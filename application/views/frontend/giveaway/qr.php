<div class="container py-5" style="max-width:480px;">
  <div class="kl-card text-center p-4 p-md-5">

    <!-- Status badge -->
    <?php
    $status_map = [
      'pending'   => ['🕐','รอรับของ','#ff9800'],
      'confirmed' => ['✅','รับของแล้ว','#00a046'],
      'cancelled' => ['❌','ยกเลิกแล้ว','#e53935'],
      'no_show'   => ['⏰','ไม่มารับ','#e53935'],
    ];
    [$icon, $label, $color] = $status_map[$res['status']] ?? ['❓','ไม่ทราบสถานะ','#888'];
    ?>
    <div class="fw-800 mb-1" style="font-size:1.5rem;"><?= $icon ?></div>
    <span class="badge px-3 py-2 mb-4" style="background:<?= $color ?>;font-size:.85rem;">
      <?= $label ?>
    </span>

    <!-- Listing info -->
    <h5 class="fw-800 mb-1"><?= htmlspecialchars($res['listing_title']) ?></h5>
    <p class="text-muted small mb-4"><?= htmlspecialchars($res['donor_business'] ?: $res['donor_name']) ?></p>

    <!-- QR Code (rendered by qrcode.js) -->
    <?php if ($res['status'] === 'pending'): ?>
    <div id="qrcode" class="mx-auto mb-3"
         style="width:180px;height:180px;border:3px solid var(--g);border-radius:12px;
                display:flex;align-items:center;justify-content:center;padding:8px;"></div>
    <?php endif; ?>

    <!-- Booking ref -->
    <div class="kl-card p-3 mb-3" style="background:var(--g-l);border-color:var(--g-m);">
      <div class="text-muted small mb-1">Booking ID</div>
      <div class="fw-800" style="font-size:1.5rem;letter-spacing:3px;color:var(--g);">
        <?= htmlspecialchars($res['booking_ref']) ?>
      </div>
    </div>

    <!-- Details -->
    <ul class="list-unstyled text-start small mb-4">
      <li class="py-2 border-bottom"><i class="bi bi-geo-alt me-2 text-danger"></i><?= htmlspecialchars($res['pickup_address']) ?></li>
      <li class="py-2 border-bottom">
        <i class="bi bi-clock me-2 text-primary"></i>
        รับได้: <?= date('d M H:i', strtotime($res['pickup_start'])) ?> – <?= date('H:i', strtotime($res['pickup_end'])) ?>
      </li>
      <li class="py-2"><i class="bi bi-person me-2"></i><?= htmlspecialchars($res['donor_name']) ?>
        <?php if ($res['donor_phone']): ?>
          · <a href="tel:<?= $res['donor_phone'] ?>"><?= $res['donor_phone'] ?></a>
        <?php endif; ?>
      </li>
    </ul>

    <!-- Actions -->
    <div class="d-grid gap-2">
      <a href="<?= site_url('giveaway/my-reservations') ?>" class="btn btn-outline-secondary" style="border-radius:9px;">
        <i class="bi bi-arrow-left me-1"></i>การจองทั้งหมด
      </a>
      <?php if ($res['status'] === 'pending'): ?>
        <form method="post" action="<?= site_url('giveaway/cancel/'.$res['id']) ?>">
          <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
          <button type="submit" class="btn btn-outline-danger w-100" style="border-radius:9px;"
                  onclick="return confirm('ยกเลิกการจองนี้ใช่ไหม?')">
            ยกเลิกการจอง
          </button>
        </form>
      <?php endif; ?>
    </div>

  </div>
</div>

<!-- QR code generation -->
<?php if ($res['status'] === 'pending'): ?>
<script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
<script>
QRCode.toCanvas(document.createElement('canvas'), <?= json_encode($res['qr_token']) ?>, {
  width: 164, margin: 1,
  color: { dark: '#1a2e1a', light: '#e6f7ee' }
}, function(err, canvas) {
  if (!err) {
    canvas.style.borderRadius = '6px';
    document.getElementById('qrcode').innerHTML = '';
    document.getElementById('qrcode').appendChild(canvas);
  }
});
</script>
<?php endif; ?>
