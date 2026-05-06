<div class="container py-5">
    <div class="text-center mb-5">
        <span style="background:var(--kl-blue-light);color:var(--kl-blue);font-size:.8rem;font-weight:700;padding:4px 14px;border-radius:20px;border:1px solid var(--kl-blue-mid);">PREMIUM</span>
        <h2 class="fw-bold mt-2 mb-1">อัปเกรดการขายของคุณ</h2>
        <p class="text-muted">บูสต์ประกาศ ขายได้เร็วขึ้น เข้าถึงผู้ซื้อมากขึ้น</p>
        <?php if (is_logged_in()): $user = current_user(); ?>
            <div class="d-inline-flex align-items-center gap-2 px-4 py-2 mt-1 rounded-pill"
                 style="background:var(--kl-blue-light);border:1px solid var(--kl-blue-mid);">
                <i class="bi bi-coin text-warning"></i>
                <span class="fw-semibold text-primary">เครดิตของคุณ: <?= number_format($user['credits']) ?> เครดิต</span>
            </div>
        <?php endif; ?>
    </div>

    <div class="row g-4 justify-content-center">
        <?php foreach ($packages as $pkg):
            $is_free_tier = ($pkg['price_in_credits'] == 0);
            $is_popular   = ($pkg['name'] === 'Pro Seller');
        ?>
        <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="kl-card h-100 overflow-hidden <?= $is_popular ? '' : '' ?>"
                 style="<?= $is_popular ? 'border:2px solid var(--kl-blue);border-radius:var(--kl-radius-lg);' : 'border-radius:var(--kl-radius-lg);' ?>">

                <?php if ($is_popular): ?>
                    <div class="text-center py-2 fw-bold small"
                         style="background:var(--kl-blue);color:#fff;">
                        <i class="bi bi-star-fill me-1"></i>ยอดนิยม
                    </div>
                <?php endif; ?>

                <div class="p-4">
                    <h5 class="fw-bold mb-1"><?= htmlspecialchars($pkg['name']) ?></h5>
                    <p class="text-muted small mb-3"><?= htmlspecialchars($pkg['description'] ?? '') ?></p>

                    <div class="mb-4">
                        <?php if ($pkg['price_in_credits'] == 0): ?>
                            <span class="fw-bold text-primary" style="font-size:2rem;">ฟรี</span>
                        <?php else: ?>
                            <span class="fw-bold text-primary" style="font-size:2rem;">
                                <?= number_format($pkg['price_in_credits']) ?>
                            </span>
                            <span class="text-muted small"> เครดิต</span>
                        <?php endif; ?>
                        <div class="text-muted" style="font-size:.78rem;">
                            <?= $pkg['duration_days'] ? $pkg['duration_days'] . ' วัน' : 'ครั้งเดียว' ?>
                        </div>
                    </div>

                    <ul class="list-unstyled mb-4" style="font-size:.85rem;">
                        <li class="d-flex align-items-center gap-2 py-1 border-bottom">
                            <i class="bi bi-check-circle-fill text-primary"></i>
                            <?= $pkg['max_listings'] ? number_format($pkg['max_listings']) . ' ประกาศ' : 'ประกาศไม่จำกัด' ?>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-1 border-bottom">
                            <?php if ($pkg['can_bump']): ?>
                                <i class="bi bi-check-circle-fill text-primary"></i>ปักหมุดขึ้นบน <?= $pkg['bump_quota'] ?> ครั้ง
                            <?php else: ?>
                                <i class="bi bi-x-circle text-muted"></i><span class="text-muted">ปักหมุดไม่ได้</span>
                            <?php endif; ?>
                        </li>
                        <li class="d-flex align-items-center gap-2 py-1">
                            <?php if ($pkg['can_highlight']): ?>
                                <i class="bi bi-check-circle-fill text-primary"></i>ไฮไลต์ประกาศ <?= $pkg['highlight_quota'] ?> ครั้ง
                            <?php else: ?>
                                <i class="bi bi-x-circle text-muted"></i><span class="text-muted">ไฮไลต์ไม่ได้</span>
                            <?php endif; ?>
                        </li>
                    </ul>

                    <?php if (!$is_free_tier): ?>
                        <?php if (is_logged_in()): ?>
                            <form method="post" action="<?= site_url('premium/activate') ?>">
                                <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
                                <input type="hidden" name="package_id" value="<?= $pkg['id'] ?>">
                                <button type="submit" class="btn btn-primary w-100 fw-bold"
                                        style="border-radius:9px;"
                                        onclick="return confirm('ยืนยันเปิดใช้ <?= htmlspecialchars($pkg['name']) ?> ใช้ <?= $pkg['price_in_credits'] ?> เครดิต?')">
                                    เปิดใช้งาน — <?= number_format($pkg['price_in_credits']) ?> เครดิต
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?= site_url('login') ?>" class="btn btn-outline-primary w-100 fw-bold"
                               style="border-radius:9px;">
                                เข้าสู่ระบบเพื่อเปิดใช้
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                        <button class="btn w-100 fw-semibold" disabled
                                style="background:var(--kl-blue-light);color:var(--kl-blue);border-radius:9px;">
                            แผนปัจจุบัน
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="mt-5 p-4 text-center rounded-3" style="background:var(--kl-blue-light);border:1px solid var(--kl-blue-mid);">
        <h5 class="fw-bold text-primary mb-2"><i class="bi bi-coin me-2 text-warning"></i>วิธีรับเครดิต</h5>
        <p class="text-muted mb-0 small">
            เนื่องจากแพลตฟอร์มนี้ไม่มีระบบชำระเงินออนไลน์ กรุณาติดต่อผู้ดูแลระบบเพื่อซื้อเครดิต<br>
            แอดมินจะเพิ่มเครดิตให้ภายใน 24 ชั่วโมง
        </p>
    </div>
</div>
