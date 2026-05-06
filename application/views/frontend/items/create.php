<div class="container py-4" style="max-width:740px;">
    <h4 class="fw-bold mb-4"><i class="bi bi-plus-circle text-green me-2"></i>ลงประกาศของ</h4>
    <?php echo validation_errors('<div class="alert alert-danger py-2 small">', '</div>'); ?>

    <form method="post" action="<?= site_url('items/create') ?>" enctype="multipart/form-data">
        <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>

        <div class="kl-card mb-3">
            <div style="background:var(--kl-green);color:#fff;padding:.65rem 1rem;font-weight:700;font-size:.88rem;border-radius:var(--kl-radius) var(--kl-radius) 0 0;">
                รายละเอียดสินค้า
            </div>
            <div class="p-3">
                <div class="mb-3">
                    <label class="form-label fw-semibold small">ชื่อสินค้า / ของที่ลง <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" maxlength="200"
                           value="<?= set_value('title') ?>" placeholder="เช่น iPhone 12 สีดำ 128GB มือสอง" required>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">หมวดหมู่ <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">เลือกหมวดหมู่…</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= set_select('category_id', $cat['id']) ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">สภาพสินค้า <span class="text-danger">*</span></label>
                        <select name="condition" class="form-select" required>
                            <option value="">เลือกสภาพ…</option>
                            <?php foreach (['new'=>'ใหม่','like_new'=>'เหมือนใหม่','good'=>'ดี','fair'=>'พอใช้','poor'=>'ผ่านศึกมาเยอะ'] as $v=>$l): ?>
                                <option value="<?= $v ?>" <?= set_select('condition', $v) ?>><?= $l ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label fw-semibold small">รายละเอียด <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="5"
                              placeholder="บอกรายละเอียดเพิ่มเติม เช่น ขนาด ความครบชุด ประวัติการใช้งาน เหตุผลที่ขาย…"
                              required><?= set_value('description') ?></textarea>
                </div>
            </div>
        </div>

        <div class="kl-card mb-3">
            <div style="background:var(--kl-green);color:#fff;padding:.65rem 1rem;font-weight:700;font-size:.88rem;border-radius:var(--kl-radius) var(--kl-radius) 0 0;">
                ราคา
            </div>
            <div class="p-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold small">ราคา (บาท)</label>
                        <div class="input-group">
                            <span class="input-group-text fw-bold">฿</span>
                            <input type="number" name="price" id="priceInput" class="form-control"
                                   min="0" step="1" value="<?= set_value('price','0') ?>" placeholder="0 = แจกฟรี">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" id="freeToggle" role="switch">
                            <label class="form-check-label fw-semibold" for="freeToggle">
                                <span style="background:var(--kl-green);color:#fff;padding:3px 12px;border-radius:20px;font-size:.82rem;">
                                    🎁 แจกฟรี!
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="kl-card mb-3">
            <div style="background:var(--kl-green);color:#fff;padding:.65rem 1rem;font-weight:700;font-size:.88rem;border-radius:var(--kl-radius) var(--kl-radius) 0 0;">
                สถานที่ / พื้นที่
            </div>
            <div class="p-3">
                <input type="text" name="location_text" class="form-control"
                       value="<?= set_value('location_text') ?>"
                       placeholder="เช่น สุขุมวิท กรุงเทพฯ / เมืองเชียงใหม่">
                <div class="row g-2 mt-2">
                    <div class="col-6">
                        <input type="number" name="location_lat" class="form-control form-control-sm"
                               step="any" value="<?= set_value('location_lat') ?>" placeholder="Latitude (ไม่บังคับ)">
                    </div>
                    <div class="col-6">
                        <input type="number" name="location_lng" class="form-control form-control-sm"
                               step="any" value="<?= set_value('location_lng') ?>" placeholder="Longitude (ไม่บังคับ)">
                    </div>
                </div>
            </div>
        </div>

        <div class="kl-card mb-4">
            <div style="background:var(--kl-green);color:#fff;padding:.65rem 1rem;font-weight:700;font-size:.88rem;border-radius:var(--kl-radius) var(--kl-radius) 0 0;">
                รูปภาพ <span style="font-weight:400;opacity:.8;">(สูงสุด <?= MAX_ITEM_IMAGES ?> รูป, ไม่เกิน <?= MAX_UPLOAD_KB ?>KB/รูป)</span>
            </div>
            <div class="p-3">
                <input type="file" name="images[]" class="form-control" multiple
                       accept="image/jpeg,image/png,image/webp" data-preview="#imgPreview">
                <div id="imgPreview" class="mt-3 d-flex flex-wrap gap-2"></div>
                <div class="form-text">รูปแรกจะเป็นรูปหน้าปก · รองรับ JPG, PNG, WebP</div>
            </div>
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary px-5 fw-bold" style="border-radius:9px;height:44px;">
                <i class="bi bi-check-lg me-2"></i>ลงประกาศ
            </button>
            <a href="<?= site_url('/') ?>" class="btn btn-outline-secondary" style="border-radius:9px;height:44px;line-height:1.8;">ยกเลิก</a>
        </div>
    </form>
</div>

<script>
const priceInput = document.getElementById('priceInput');
const freeToggle = document.getElementById('freeToggle');
freeToggle.addEventListener('change', function() {
    priceInput.value = this.checked ? '0' : '';
    priceInput.disabled = this.checked;
});
if (priceInput.value === '0') { freeToggle.checked = true; priceInput.disabled = true; }
priceInput.addEventListener('input', function() { freeToggle.checked = (this.value === '0' || this.value === ''); });
</script>
