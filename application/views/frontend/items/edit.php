<div class="container py-4" style="max-width:760px;">

  <div class="d-flex align-items-center gap-3 mb-4">
    <div style="width:44px;height:44px;background:var(--g-l);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">✏️</div>
    <div>
      <h4 class="fw-800 mb-0">แก้ไขประกาศ</h4>
      <p class="text-muted small mb-0">แก้ไขรายละเอียดสินค้าของคุณ</p>
    </div>
  </div>

  <?php echo validation_errors('<div class="alert alert-danger py-2 small rounded-3">', '</div>'); ?>

  <form method="post" action="<?= site_url('items/edit/'.$item['id']) ?>" enctype="multipart/form-data">
    <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>

    <!-- ── รายละเอียดสินค้า ── -->
    <div class="kl-card mb-3">
      <div class="kl-card-head">รายละเอียดสินค้า</div>
      <div class="p-4">
        <div class="mb-3">
          <label class="form-label fw-600 small">ชื่อสินค้า <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control" maxlength="200" required
                 value="<?= set_value('title', htmlspecialchars($item['title'])) ?>">
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-600 small">หมวดหมู่</label>
            <select name="category_id" class="form-select" required>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"
                  <?= set_select('category_id', $cat['id'], (string)$item['category_id'] === (string)$cat['id']) ?>>
                  <?= htmlspecialchars($cat['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-600 small">สภาพสินค้า</label>
            <select name="condition" class="form-select" required>
              <?php foreach (['new'=>'ใหม่','like_new'=>'เหมือนใหม่','good'=>'ดี','fair'=>'พอใช้','poor'=>'ผ่านศึก'] as $v=>$l): ?>
                <option value="<?= $v ?>" <?= set_select('condition', $v, $item['condition'] === $v) ?>>
                  <?= $l ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="mt-3">
          <label class="form-label fw-600 small">รายละเอียด</label>
          <textarea name="description" class="form-control" rows="5" required
          ><?= set_value('description', htmlspecialchars($item['description'])) ?></textarea>
        </div>
      </div>
    </div>

    <!-- ── ราคาและสถานะ ── -->
    <div class="kl-card mb-3">
      <div class="kl-card-head">ราคาและสถานะ</div>
      <div class="p-4">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-600 small">ราคา (บาท)</label>
            <div class="input-group">
              <span class="input-group-text fw-700">฿</span>
              <input type="number" name="price" id="priceInput" class="form-control"
                     min="0" step="1"
                     value="<?= set_value('price', (string)(int)$item['price'] === (string)$item['price'] ? (int)$item['price'] : $item['price']) ?>">
            </div>
            <div class="form-check form-switch mt-2">
              <input class="form-check-input" type="checkbox" id="freeToggleEdit" role="switch"
                     <?= ((float)$item['price'] <= 0) ? 'checked' : '' ?>>
              <label class="form-check-label small" for="freeToggleEdit">
                <span style="background:var(--g);color:#fff;padding:2px 10px;border-radius:20px;font-size:.78rem;">🎁 แจกฟรี</span>
              </label>
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-600 small">สถานะประกาศ</label>
            <select name="status" class="form-select">
              <?php foreach (['active'=>'เปิดรับ (Active)','reserved'=>'จองแล้ว (Reserved)','sold'=>'ขายแล้ว (Sold)'] as $v=>$l): ?>
                <option value="<?= $v ?>" <?= set_select('status', $v, $item['status'] === $v) ?>><?= $l ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- ── สถานที่ ── -->
    <div class="kl-card mb-3">
      <div class="kl-card-head">สถานที่</div>
      <div class="p-4">
        <div class="mb-3">
          <label class="form-label fw-600 small">ที่อยู่/บริเวณ</label>
          <input type="text" name="location_text" class="form-control"
                 value="<?= set_value('location_text', htmlspecialchars($item['location_text'] ?? '')) ?>"
                 placeholder="เช่น สุขุมวิท กรุงเทพฯ">
        </div>
        <div>
          <label class="form-label fw-600 small">
            <i class="bi bi-geo-alt-fill text-danger me-1"></i>ลิงก์ Google Maps
            <span class="text-muted fw-400">(ไม่บังคับ)</span>
          </label>
          <input type="url" name="map_url" class="form-control"
                 value="<?= set_value('map_url', htmlspecialchars($item['map_url'] ?? '')) ?>"
                 placeholder="https://maps.app.goo.gl/...">
          <div class="form-text"><i class="bi bi-info-circle me-1"></i>เปิด Google Maps → แชร์ → คัดลอกลิงก์</div>
        </div>
      </div>
    </div>

    <!-- ── รูปภาพปัจจุบัน ── -->
    <?php if (!empty($item['images'])): ?>
    <div class="kl-card mb-3">
      <div class="kl-card-head">รูปภาพปัจจุบัน</div>
      <div class="p-4">
        <div class="d-flex flex-wrap gap-3">
          <?php foreach ($item['images'] as $img): ?>
          <div class="position-relative">
            <img src="<?= base_url($img['image_path']) ?>" class="rounded"
                 style="width:100px;height:88px;object-fit:cover;border:2px solid <?= $img['is_primary'] ? 'var(--g)' : 'var(--border)' ?>;"
                 alt="" onerror="this.src='<?= base_url('assets/img/no-image.svg') ?>'">
            <?php if ($img['is_primary']): ?>
              <span class="badge position-absolute top-0 start-0 m-1"
                    style="background:var(--g);font-size:.65rem;">หน้าปก</span>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <p class="text-muted small mt-2 mb-0"><i class="bi bi-info-circle me-1"></i>
          รูปแรกในการอัปโหลดจะเป็นหน้าปก หากต้องการเปลี่ยน ให้อัปโหลดรูปใหม่ด้านล่าง</p>
      </div>
    </div>
    <?php endif; ?>

    <!-- ── เพิ่มรูปภาพ ── -->
    <div class="kl-card mb-4">
      <div class="kl-card-head">
        เพิ่มรูปภาพ
        <span style="font-weight:400;opacity:.8;font-size:.8rem;">
          (รวมสูงสุด <?= MAX_ITEM_IMAGES ?> รูป · ไม่เกิน <?= MAX_UPLOAD_KB ?>KB/รูป)
        </span>
      </div>
      <div class="p-4">
        <input type="file" name="images[]" class="form-control" multiple
               accept="image/jpeg,image/png,image/webp"
               data-preview="#editImgPreview">
        <div id="editImgPreview" class="mt-3 d-flex flex-wrap gap-2"></div>
        <div class="form-text">รองรับ JPG, PNG, WebP · รูปแรกที่เลือกจะกลายเป็นหน้าปกใหม่</div>
      </div>
    </div>

    <!-- ── ปุ่ม ── -->
    <div class="d-flex gap-3 align-items-center">
      <button type="submit" class="btn btn-primary px-5 fw-700" style="border-radius:9px;height:44px;">
        <i class="bi bi-check-lg me-2"></i>บันทึกการแก้ไข
      </button>
      <a href="<?= site_url('items/'.$item['id']) ?>" class="btn btn-outline-secondary"
         style="border-radius:9px;height:44px;line-height:1.9;">
        ยกเลิก
      </a>
      <a href="<?= site_url('items/delete/'.$item['id']) ?>"
         class="btn btn-outline-danger ms-auto" style="border-radius:9px;height:44px;line-height:1.9;"
         onclick="return confirm('ลบประกาศนี้ถาวรเลยใช่ไหม?')">
        <i class="bi bi-trash me-1"></i>ลบประกาศ
      </a>
    </div>
  </form>
</div>

<script>
// Free toggle
const priceInput = document.getElementById('priceInput');
const freeToggle = document.getElementById('freeToggleEdit');

function applyFreeToggle() {
  if (freeToggle.checked) {
    priceInput.value = '0';
    priceInput.setAttribute('readonly', true);
    priceInput.style.opacity = '0.5';
  } else {
    priceInput.removeAttribute('readonly');
    priceInput.style.opacity = '1';
    if (priceInput.value === '0') priceInput.value = '';
  }
}

freeToggle.addEventListener('change', function() {
  applyFreeToggle();
  if (!freeToggle.checked) priceInput.focus();
});

priceInput.addEventListener('input', function() {
  freeToggle.checked = (this.value === '0' || this.value === '');
});

// Apply on page load
applyFreeToggle();
</script>
