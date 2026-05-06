<div class="container py-4" style="max-width:760px;">

  <!-- Page header -->
  <div class="d-flex align-items-center gap-3 mb-4">
    <div style="width:44px;height:44px;background:var(--g-l);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">⚙️</div>
    <div>
      <h4 class="fw-800 mb-0">ตั้งค่าบัญชี</h4>
      <p class="text-muted small mb-0">แก้ไขข้อมูลส่วนตัวและรหัสผ่าน</p>
    </div>
  </div>

  <form method="post" action="<?= site_url('account/settings/save') ?>" enctype="multipart/form-data">
    <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>

    <!-- ── ข้อมูลส่วนตัว ── -->
    <div class="kl-card mb-4">
      <div class="kl-card-head">ข้อมูลส่วนตัว</div>
      <div class="p-4">

        <!-- Avatar -->
        <div class="d-flex align-items-center gap-4 mb-4">
          <div id="avatarPreview" style="width:72px;height:72px;border-radius:50%;overflow:hidden;border:3px solid var(--g-m);flex-shrink:0;">
            <?php if (!empty($user['avatar'])): ?>
              <img src="<?= base_url($user['avatar']) ?>" style="width:100%;height:100%;object-fit:cover;" id="avatarImg">
            <?php else: ?>
              <div style="width:100%;height:100%;background:var(--g);color:#fff;display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;">
                <?= strtoupper(mb_substr($user['name'],0,1)) ?>
              </div>
            <?php endif; ?>
          </div>
          <div>
            <label class="btn btn-outline-primary btn-sm mb-0" style="cursor:pointer;border-radius:9px;">
              <i class="bi bi-camera me-1"></i>เปลี่ยนรูปโปรไฟล์
              <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp"
                     style="display:none;" id="avatarInput">
            </label>
            <p class="text-muted mb-0 mt-1" style="font-size:.75rem;">JPG, PNG, WebP · ไม่เกิน 1MB</p>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-600 small">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                   value="<?= htmlspecialchars($user['name']) ?>" required maxlength="100">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-600 small">อีเมล <span class="text-muted fw-400">(ไม่สามารถแก้ไขได้)</span></label>
            <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($user['email']) ?>" disabled>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-600 small">เบอร์โทรศัพท์</label>
            <input type="tel" name="phone" class="form-control"
                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>" maxlength="20" placeholder="0812345678">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-600 small">จังหวัด / เมือง</label>
            <input type="text" name="city" class="form-control"
                   value="<?= htmlspecialchars($user['city'] ?? '') ?>" maxlength="100" placeholder="กรุงเทพฯ">
          </div>
          <div class="col-12">
            <label class="form-label fw-600 small">แนะนำตัว</label>
            <textarea name="bio" class="form-control" rows="3" maxlength="500"
                      placeholder="เขียนแนะนำตัวสั้นๆ เช่น ขายของมือสองแถวสุขุมวิท..."><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            <div class="form-text">ไม่เกิน 500 ตัวอักษร</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ── เปลี่ยนรหัสผ่าน ── -->
    <div class="kl-card mb-4">
      <div class="kl-card-head">เปลี่ยนรหัสผ่าน</div>
      <div class="p-4">
        <p class="text-muted small mb-3">กรอกเฉพาะเมื่อต้องการเปลี่ยนรหัสผ่าน</p>
        <div class="row g-3">
          <div class="col-md-12">
            <label class="form-label fw-600 small">รหัสผ่านปัจจุบัน</label>
            <input type="password" name="current_password" class="form-control" placeholder="••••••••">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-600 small">รหัสผ่านใหม่</label>
            <input type="password" name="new_password" class="form-control" minlength="8" placeholder="อย่างน้อย 8 ตัวอักษร">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-600 small">ยืนยันรหัสผ่านใหม่</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="พิมพ์รหัสผ่านใหม่อีกครั้ง">
          </div>
        </div>
      </div>
    </div>

    <!-- ── ข้อมูลบัญชี ── -->
    <div class="kl-card mb-4">
      <div class="kl-card-head">ข้อมูลบัญชี</div>
      <div class="p-4">
        <div class="row g-3">
          <div class="col-sm-4">
            <div class="text-muted small mb-1">ระดับบัญชี</div>
            <?php if ($user['premium_status']): ?>
              <span class="badge px-3 py-2" style="background:linear-gradient(135deg,#f97316,#fbbf24);font-size:.82rem;">⭐ Premium</span>
            <?php else: ?>
              <span class="badge px-3 py-2 bg-secondary">Free</span>
            <?php endif; ?>
          </div>
          <div class="col-sm-4">
            <div class="text-muted small mb-1">เครดิต</div>
            <div class="fw-700 text-primary fs-5"><?= number_format($user['credits']) ?> <span class="small fw-400 text-muted">เครดิต</span></div>
          </div>
          <div class="col-sm-4">
            <div class="text-muted small mb-1">สมัครสมาชิกเมื่อ</div>
            <div class="fw-600"><?= date('d M Y', strtotime($user['created_at'])) ?></div>
          </div>
        </div>
        <div class="mt-3 d-flex gap-2 flex-wrap">
          <a href="<?= site_url('credits') ?>" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-coin me-1"></i>ดูประวัติเครดิต
          </a>
          <a href="<?= site_url('premium') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-star me-1"></i>อัปเกรด Premium
          </a>
        </div>
      </div>
    </div>

    <div class="d-flex gap-3">
      <button type="submit" class="btn btn-primary px-5 fw-700" style="border-radius:9px;height:44px;">
        <i class="bi bi-check-lg me-2"></i>บันทึกการเปลี่ยนแปลง
      </button>
      <a href="<?= site_url('profile/'.urlencode($user['name'])) ?>" class="btn btn-outline-secondary" style="border-radius:9px;height:44px;line-height:1.8;">
        ดูโปรไฟล์
      </a>
    </div>
  </form>
</div>

<script>
// Avatar preview
document.getElementById('avatarInput').addEventListener('change', function() {
  var file = this.files[0];
  if (!file) return;
  var reader = new FileReader();
  reader.onload = function(e) {
    var preview = document.getElementById('avatarPreview');
    preview.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;">';
  };
  reader.readAsDataURL(file);
});

// Password match check
document.querySelector('form').addEventListener('submit', function(e) {
  var np = document.querySelector('[name="new_password"]').value;
  var cp = document.querySelector('[name="confirm_password"]').value;
  if (np && np !== cp) {
    e.preventDefault();
    alert('รหัสผ่านใหม่ไม่ตรงกัน กรุณาตรวจสอบอีกครั้ง');
  }
});
</script>
