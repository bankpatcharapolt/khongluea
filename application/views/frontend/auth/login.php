<div class="card shadow border-0" style="border-radius:14px;">
    <div class="card-body p-4">
        <h4 class="fw-bold text-center mb-1">ยินดีต้อนรับกลับ</h4>
        <p class="text-center text-muted small mb-4">เข้าสู่ระบบเพื่อซื้อขายของมือสอง</p>
        <?php echo validation_errors('<div class="alert alert-danger py-2 small">', '</div>'); ?>

        <form method="post" action="<?= site_url('login') ?>">
            <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
            <div class="mb-3">
                <label class="form-label fw-semibold small">อีเมล</label>
                <input type="email" name="email" class="form-control"
                       value="<?= set_value('email') ?>" required autofocus placeholder="your@email.com">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small" for="remember">จดจำฉัน</label>
                </div>
                <a href="<?= site_url('forgot-password') ?>" class="small text-decoration-none text-green">
                    ลืมรหัสผ่าน?
                </a>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold" style="height:42px;border-radius:9px;">
                เข้าสู่ระบบ
            </button>
        </form>
        <hr>
        <p class="text-center text-muted small mb-0">
            ยังไม่มีบัญชี?
            <a href="<?= site_url('register') ?>" class="fw-semibold text-decoration-none text-green">
                สมัครสมาชิกฟรี
            </a>
        </p>
    </div>
</div>
