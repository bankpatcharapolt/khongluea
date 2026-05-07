<div class="container-fluid px-3 px-lg-4 py-4" style="max-width:1400px;margin:0 auto;">

  <!-- Header -->
  <div class="d-flex align-items-center gap-3 mb-4">
    <div style="width:48px;height:48px;background:var(--g-l);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;">🎁</div>
    <div>
      <h4 class="fw-800 mb-0">รับของแจกฟรี</h4>
      <p class="text-muted small mb-0">ของเหลือจากร้านค้า ร้านอาหาร ที่รอการส่งต่อ</p>
    </div>
    <a href="<?= site_url('donor/create') ?>" class="btn btn-primary ms-auto fw-700" style="border-radius:9px;">
      <i class="bi bi-plus-lg me-1"></i>ลงแจกของ
    </a>
  </div>

  <!-- Filters -->
  <form method="get" action="<?= site_url('giveaway') ?>" class="kl-card p-3 mb-4">
    <div class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label fw-600 small">ค้นหา</label>
        <input type="search" name="q" class="form-control" placeholder="ชื่อของ, ร้านค้า..."
               value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-600 small">หมวดหมู่</label>
        <select name="category_id" class="form-select">
          <option value="">ทุกหมวด</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($filters['category_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-600 small">รัศมี (กม.)</label>
        <select name="radius" class="form-select">
          <?php foreach ([5,10,20,50] as $r): ?>
            <option value="<?= $r ?>" <?= ($filters['radius_km'] ?? 10) == $r ? 'selected' : '' ?>><?= $r ?> กม.</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100 fw-600">
          <i class="bi bi-search me-1"></i>ค้นหา
        </button>
      </div>
    </div>
    <!-- Hidden location fields (populated by geolocation JS) -->
    <input type="hidden" name="lat" id="userLat" value="<?= htmlspecialchars($filters['lat'] ?? '') ?>">
    <input type="hidden" name="lng" id="userLng" value="<?= htmlspecialchars($filters['lng'] ?? '') ?>">
  </form>

  <!-- Stats bar -->
  <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div class="text-muted small">พบ <strong class="text-dark"><?= number_format($total) ?></strong> รายการ</div>
    <button id="btnLocate" class="btn btn-outline-secondary btn-sm" style="border-radius:9px;">
      <i class="bi bi-geo-alt me-1"></i>ใช้ตำแหน่งของฉัน
    </button>
  </div>

  <!-- Listings grid -->
  <?php if (empty($listings)): ?>
    <div class="kl-empty py-5">
      <i class="bi bi-gift"></i>
      <h6>ยังไม่มีของแจกในขณะนี้</h6>
      <p class="small text-muted mb-3">ลองขยายรัศมีการค้นหา หรือกลับมาใหม่ในภายหลัง</p>
    </div>
  <?php else: ?>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 g-3 mb-4">
      <?php foreach ($listings as $listing): ?>
      <div class="col">
        <?= $this->load->view('partials/giveaway_card', ['listing' => $listing], TRUE) ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php if ($pagination): ?>
      <div class="d-flex justify-content-center"><?= $pagination ?></div>
    <?php endif; ?>
  <?php endif; ?>

</div>

<script>
// Geolocation button
document.getElementById('btnLocate')?.addEventListener('click', function() {
  if (!navigator.geolocation) { alert('Browser ไม่รองรับ Geolocation'); return; }
  this.disabled = true;
  this.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i>กำลังหาตำแหน่ง...';
  navigator.geolocation.getCurrentPosition(
    pos => {
      document.getElementById('userLat').value = pos.coords.latitude.toFixed(6);
      document.getElementById('userLng').value = pos.coords.longitude.toFixed(6);
      this.closest('form').submit();
    },
    () => { this.disabled=false; this.innerHTML='<i class="bi bi-geo-alt me-1"></i>ใช้ตำแหน่งของฉัน'; }
  );
});
</script>
