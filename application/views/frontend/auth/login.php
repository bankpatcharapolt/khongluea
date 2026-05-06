<h4 class="fw-800 text-center mb-1">ยินดีต้อนรับกลับ</h4>
<p class="text-center text-muted small mb-4">เข้าสู่ระบบเพื่อซื้อขายของมือสอง</p>
<?= validation_errors('<div class="alert alert-danger py-2 small rounded-3">', '</div>') ?>
<form method="post" action="<?= site_url('login') ?>">
  <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
  <div class="mb-3">
    <label class="form-label fw-600 small">อีเมล</label>
    <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" required autofocus placeholder="your@email.com">
  </div>
  <div class="mb-3">
    <label class="form-label fw-600 small">รหัสผ่าน</label>
    <input type="password" name="password" class="form-control" required placeholder="••••••••">
    <div class="text-end mt-1">
      <a href="<?= site_url('forgot-password') ?>" class="small text-primary">ลืมรหัสผ่าน?</a>
    </div>
  </div>
  <button type="submit" class="btn btn-primary w-100 fw-700 mt-1" style="height:44px;font-size:1rem;">
    เข้าสู่ระบบ
  </button>
</form>
<div class="text-center mt-3 pt-3 border-top small text-muted">
  ยังไม่มีบัญชี? <a href="<?= site_url('register') ?>" class="fw-700 text-primary">สมัครสมาชิกฟรี</a>
</div>
