<div class="container-fluid py-3 px-3" style="max-width:1300px;margin:0 auto;">
    <div class="row g-3">

        <!-- Sidebar Filters -->
        <div class="col-lg-2 col-md-3 d-none d-md-block">
            <div class="kl-filter-card sticky-top" style="top:8px;">
                <div class="filter-head"><i class="bi bi-sliders me-1"></i>กรองผลลัพธ์</div>
                <div class="filter-body">
                    <form method="get" action="<?= site_url('items') ?>">
                        <?php if (!empty($filters['search'])): ?>
                            <input type="hidden" name="q" value="<?= htmlspecialchars($filters['search']) ?>">
                        <?php endif; ?>

                        <div class="filter-section">
                            <div class="filter-label">หมวดหมู่</div>
                            <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">ทั้งหมด</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($cat['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-section">
                            <div class="filter-label">ประเภท</div>
                            <?php foreach (['' => 'ทั้งหมด', '1' => '🎁 แจกฟรี', '0' => '💰 มีราคา'] as $v => $l): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="is_free" value="<?= $v ?>"
                                       id="ft<?= $v ?>"
                                       <?= ($filters['is_free'] === $v || ($v === '' && $filters['is_free'] === NULL)) ? 'checked' : '' ?>
                                       onchange="this.form.submit()">
                                <label class="form-check-label small" for="ft<?= $v ?>"><?= $l ?></label>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="filter-section">
                            <div class="filter-label">สภาพสินค้า</div>
                            <select name="condition" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">ทุกสภาพ</option>
                                <?php foreach (['new'=>'ใหม่','like_new'=>'เหมือนใหม่','good'=>'ดี','fair'=>'พอใช้','poor'=>'ผ่านศึกมาเยอะ'] as $v=>$l): ?>
                                    <option value="<?= $v ?>" <?= ($filters['condition'] === $v) ? 'selected' : '' ?>>
                                        <?= $l ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="filter-section">
                            <div class="filter-label">ช่วงราคา (บาท)</div>
                            <div class="row g-1">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control form-control-sm"
                                           placeholder="ต่ำสุด" value="<?= htmlspecialchars($filters['min_price'] ?? '') ?>">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control form-control-sm"
                                           placeholder="สูงสุด" value="<?= htmlspecialchars($filters['max_price'] ?? '') ?>">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100 mt-2">ค้นหา</button>
                        </div>

                        <div class="filter-section">
                            <a href="<?= site_url('items') ?>" class="btn btn-outline-secondary btn-sm w-100">
                                ล้างตัวกรอง
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Items Grid -->
        <div class="col-lg-10 col-md-9">
            <!-- Top bar -->
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div class="text-muted small">
                    <?php if (!empty($filters['search'])): ?>
                        ผลลัพธ์สำหรับ "<strong><?= htmlspecialchars($filters['search']) ?></strong>" —
                    <?php endif; ?>
                    พบ <strong><?= number_format($total) ?></strong> รายการ
                </div>
                <div class="d-flex align-items-center gap-2">
                    <!-- Mobile filter -->
                    <button class="btn btn-outline-secondary btn-sm d-md-none"
                            data-bs-toggle="offcanvas" data-bs-target="#mobileFilter">
                        <i class="bi bi-sliders me-1"></i>กรอง
                    </button>
                    <select class="form-select form-select-sm w-auto" onchange="location.href=this.value">
                        <?php
                        $sorts = ['newest'=>'ล่าสุด','price_asc'=>'ราคาน้อย→มาก','price_desc'=>'ราคามาก→น้อย'];
                        foreach ($sorts as $val=>$label):
                            $url = site_url('items?') . http_build_query(array_merge(array_filter($filters), ['sort'=>$val]));
                        ?>
                        <option value="<?= $url ?>" <?= ($filters['sort']===$val) ? 'selected' : '' ?>>
                            <?= $label ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <?php if (empty($items)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-search display-3 text-muted"></i>
                    <h5 class="mt-3 text-muted">ไม่พบสินค้าที่ค้นหา</h5>
                    <p class="text-muted">ลองเปลี่ยนเงื่อนไขการค้นหา หรือ
                        <a href="<?= site_url('items/create') ?>">ลงประกาศเป็นคนแรก!</a>
                    </p>
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 row-cols-xl-5 g-3">
                    <?php foreach ($items as $item): ?>
                    <div class="col"><?= $this->load->view('partials/item_card', ['item' => $item], TRUE) ?></div>
                    <?php endforeach; ?>
                </div>
                <?php if ($pagination): ?>
                    <div class="d-flex justify-content-center mt-4"><?= $pagination ?></div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Mobile filter offcanvas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileFilter">
    <div class="offcanvas-header" style="background:var(--kl-green);color:#fff;">
        <h6 class="offcanvas-title fw-bold mb-0"><i class="bi bi-sliders me-2"></i>กรองผลลัพธ์</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-3">
        <form method="get" action="<?= site_url('items') ?>">
            <?php if (!empty($filters['search'])): ?>
                <input type="hidden" name="q" value="<?= htmlspecialchars($filters['search']) ?>">
            <?php endif; ?>
            <div class="mb-3">
                <label class="form-label fw-semibold small">หมวดหมู่</label>
                <select name="category_id" class="form-select">
                    <option value="">ทั้งหมด</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">สภาพสินค้า</label>
                <select name="condition" class="form-select">
                    <option value="">ทุกสภาพ</option>
                    <?php foreach (['new'=>'ใหม่','like_new'=>'เหมือนใหม่','good'=>'ดี','fair'=>'พอใช้','poor'=>'ผ่านศึกมาเยอะ'] as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= ($filters['condition']===$v)?'selected':'' ?>><?= $l ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold small">ช่วงราคา (฿)</label>
                <div class="row g-2">
                    <div class="col"><input type="number" name="min_price" class="form-control" placeholder="ต่ำสุด" value="<?= $filters['min_price'] ?? '' ?>"></div>
                    <div class="col"><input type="number" name="max_price" class="form-control" placeholder="สูงสุด" value="<?= $filters['max_price'] ?? '' ?>"></div>
                </div>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary fw-bold">ค้นหา</button>
                <a href="<?= site_url('items') ?>" class="btn btn-outline-secondary">ล้างตัวกรอง</a>
            </div>
        </form>
    </div>
</div>
