<div class="container py-4" style="max-width:760px;">
  <div class="d-flex align-items-center gap-3 mb-4">
    <div style="width:44px;height:44px;background:var(--g-l);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">🏪</div>
    <div>
      <h4 class="fw-800 mb-0">ลงประกาศแจกของ</h4>
      <p class="text-muted small mb-0">แจกของเหลือให้ชุมชน ลดการสูญเสีย</p>
    </div>
  </div>

  <?= validation_errors('<div class="alert alert-danger py-2 small rounded-3">','</div>') ?>

  <form method="post" action="<?= site_url('donor/create') ?>" enctype="multipart/form-data">
    <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>

    <!-- ── รายละเอียด ── -->
    <div class="kl-card mb-3">
      <div class="kl-card-head">รายละเอียดของที่แจก</div>
      <div class="p-4">
        <div class="mb-3">
          <label class="form-label fw-600 small">ชื่อของที่แจก <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control" required maxlength="200"
                 value="<?= set_value('title') ?>" placeholder="เช่น ข้าวกล่องเหลือ 10 กล่อง, ขนมปังใกล้หมดอายุ">
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-600 small">หมวดหมู่</label>
            <select name="category_id" class="form-select">
              <option value="">-- เลือกหมวดหมู่ --</option>
              <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= set_select('category_id', $c['id']) ?>>
                  <?= htmlspecialchars($c['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-600 small">จำนวน (ชิ้น/กล่อง) <span class="text-danger">*</span></label>
            <input type="number" name="quantity" class="form-control" required min="1" max="999"
                   value="<?= set_value('quantity','1') ?>">
          </div>
        </div>
        <div class="mt-3">
          <label class="form-label fw-600 small">รายละเอียดเพิ่มเติม</label>
          <textarea name="description" class="form-control" rows="4"
                    placeholder="บอกรายละเอียดเพิ่มเติม เช่น วัตถุดิบ วันหมดอายุ เงื่อนไขการรับ..."
          ><?= set_value('description') ?></textarea>
        </div>
      </div>
    </div>

    <!-- ── เวลารับ ── -->
    <div class="kl-card mb-3">
      <div class="kl-card-head">ช่วงเวลารับของ</div>
      <div class="p-4">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-600 small">เริ่มรับ <span class="text-danger">*</span></label>
            <input type="datetime-local" name="pickup_start" class="form-control" required
                   value="<?= set_value('pickup_start') ?>" min="<?= date('Y-m-d\TH:i') ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-600 small">สิ้นสุด <span class="text-danger">*</span></label>
            <input type="datetime-local" name="pickup_end" class="form-control" required
                   value="<?= set_value('pickup_end') ?>">
          </div>
        </div>
        <div class="form-text mt-2"><i class="bi bi-info-circle me-1"></i>กำหนดเวลาที่คุณพร้อมให้มารับของที่ร้าน</div>
      </div>
    </div>

    <!-- ── สถานที่ ── -->
    <div class="kl-card mb-3">
      <div class="kl-card-head">สถานที่รับของ</div>
      <div class="p-4">
        <div class="mb-3">
          <label class="form-label fw-600 small">ที่อยู่ร้าน <span class="text-danger">*</span></label>
          <input type="text" name="pickup_address" class="form-control" required
                 value="<?= set_value('pickup_address') ?>"
                 placeholder="เช่น ร้านกาแฟ XYZ ซอยสุขุมวิท 11 กรุงเทพฯ">
        </div>
        <div class="row g-2">
          <div class="col-6">
            <label class="form-label fw-600 small">Latitude</label>
            <input type="number" name="pickup_lat" id="pickupLat" class="form-control form-control-sm"
                   step="any" value="<?= set_value('pickup_lat') ?>" placeholder="13.7563">
          </div>
          <div class="col-6">
            <label class="form-label fw-600 small">Longitude</label>
            <input type="number" name="pickup_lng" id="pickupLng" class="form-control form-control-sm"
                   step="any" value="<?= set_value('pickup_lng') ?>" placeholder="100.5018">
          </div>
        </div>
        <button type="button" id="btnGetLoc" class="btn btn-outline-secondary btn-sm mt-2" style="border-radius:7px;">
          <i class="bi bi-geo-alt me-1"></i>ใช้ตำแหน่งปัจจุบัน
        </button>
      </div>
    </div>

    <!-- ── รูปภาพ ── -->
    <div class="kl-card mb-4">
      <div class="kl-card-head">รูปภาพ <span style="font-weight:400;opacity:.8;font-size:.8rem;">(ไม่บังคับ สูงสุด 5 รูป)</span></div>
      <div class="p-4">
        <input type="file" name="images[]" class="form-control" multiple
               accept="image/jpeg,image/png,image/webp" data-preview="#imgPreview">
        <div id="imgPreview" class="mt-3 d-flex flex-wrap gap-2"></div>
      </div>
    </div>

    <div class="d-flex gap-3">
      <button type="submit" class="btn btn-primary px-5 fw-700" style="border-radius:9px;height:44px;">
        <i class="bi bi-gift me-2"></i>ลงประกาศแจกของ
      </button>
      <a href="<?= site_url('donor/listings') ?>" class="btn btn-outline-secondary" style="border-radius:9px;height:44px;line-height:1.8;">ยกเลิก</a>
    </div>
  </form>
</div>

<script>
// Geolocation
document.getElementById('btnGetLoc')?.addEventListener('click', function() {
  if (!navigator.geolocation) return;
  this.disabled = true;
  navigator.geolocation.getCurrentPosition(
    pos => {
      document.getElementById('pickupLat').value = pos.coords.latitude.toFixed(6);
      document.getElementById('pickupLng').value = pos.coords.longitude.toFixed(6);
      this.disabled = false;
    },
    () => { this.disabled = false; }
  );
});

// Auto set pickup_end min
document.querySelector('[name="pickup_start"]')?.addEventListener('change', function() {
  document.querySelector('[name="pickup_end"]').min = this.value;
});
</script>
