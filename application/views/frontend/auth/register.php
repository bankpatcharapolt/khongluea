<div class="card shadow border-0" style="border-radius:14px;">
    <div class="card-body p-4">
        <h4 class="fw-bold text-center mb-1">สมัครสมาชิกฟรี</h4>
        <p class="text-center text-muted small mb-4">เริ่มซื้อขายของมือสองกับชุมชน</p>
        <?php echo validation_errors('<div class="alert alert-danger py-2 small">', '</div>'); ?>

        <form method="post" action="<?= site_url('register') ?>">
            <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
            <div class="mb-3">
                <label class="form-label fw-semibold small">ชื่อ-นามสกุล</label>
                <input type="text" name="name" class="form-control"
                       value="<?= set_value('name') ?>" required placeholder="เช่น สมชาย ใจดี">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">อีเมล</label>
                <input type="email" name="email" class="form-control"
                       value="<?= set_value('email') ?>" required placeholder="your@email.com">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">รหัสผ่าน</label>
                <input type="password" name="password" class="form-control" minlength="8" required placeholder="อย่างน้อย 8 ตัวอักษร">
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">ยืนยันรหัสผ่าน</label>
                <input type="password" name="password_confirm" class="form-control" required placeholder="พิมพ์รหัสผ่านอีกครั้ง">
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-bold" style="height:42px;border-radius:9px;">
                สมัครสมาชิก
            </button>
        </form>
        <hr>
        <p class="text-center text-muted small mb-0">
            มีบัญชีแล้ว?
            <a href="<?= site_url('login') ?>" class="fw-semibold text-decoration-none text-green">
                เข้าสู่ระบบ
            </a>
        </p>
    </div>
</div>
