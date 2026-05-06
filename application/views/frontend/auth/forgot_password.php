<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <h4 class="fw-bold text-center mb-2">Reset Password</h4>
        <p class="text-muted text-center small mb-4">Enter your email and we'll send a reset link.</p>
        <?php echo validation_errors('<div class="alert alert-danger py-2 small">', '</div>'); ?>

        <form method="post" action="<?= site_url('forgot-password') ?>">
            <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary w-100 fw-semibold">Send Reset Link</button>
        </form>
        <hr>
        <p class="text-center text-muted small mb-0">
            <a href="<?= site_url('login') ?>" class="text-decoration-none">Back to Login</a>
        </p>
    </div>
</div>
