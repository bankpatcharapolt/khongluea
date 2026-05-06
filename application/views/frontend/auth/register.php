<h4 class="fw-800 text-center mb-1">สมัครสมาชิกฟรี</h4>
<p class="text-center text-muted small mb-4">เริ่มต้นซื้อขายของมือสองได้เลย</p>
<?= validation_errors('<div class="alert alert-danger py-2 small rounded-3">', '</div>') ?>
<form method="post" action="<?= site_url('register') ?>">
  <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
  <div class="mb-3">
    <label class="form-label fw-600 small">ชื่อ-นามสกุล</label>
    <input type="text" name="name" class="form-control" value="<?= set_value('name') ?>" required placeholder="เช่น สมชาย ใจดี">
  </div>
  <div class="mb-3">
    <label class="form-label fw-600 small">อีเมล</label>
    <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" required placeholder="your@email.com">
  </div>
  <div class="mb-3">
    <label class="form-label fw-600 small">รหัสผ่าน <span class="text-muted fw-400">(อย่างน้อย 8 ตัว)</span></label>
    <input type="password" name="password" class="form-control" minlength="8" required placeholder="••••••••">
  </div>
  <div class="mb-3">
    <label class="form-label fw-600 small">ยืนยันรหัสผ่าน</label>
    <input type="password" name="password_confirm" class="form-control" required placeholder="พิมพ์อีกครั้ง">
  </div>
  <button type="submit" class="btn btn-primary w-100 fw-700 mt-1" style="height:44px;font-size:1rem;">
    สมัครสมาชิกเลย
  </button>
</form>
<div class="text-center mt-3 pt-3 border-top small text-muted">
  มีบัญชีแล้ว? <a href="<?= site_url('login') ?>" class="fw-700 text-primary">เข้าสู่ระบบ</a>
</div>
