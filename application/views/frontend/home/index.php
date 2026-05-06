<!-- HERO -->
<section class="kl-hero">
    <div class="container-fluid px-3" style="max-width:1300px;margin:0 auto;position:relative;z-index:1;">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1>ซื้อ ขาย แจกของมือสอง<br>ง่ายๆ ในชุมชน</h1>
                <p class="tagline">ไม่มีค่าธรรมเนียม · นัดรับส่งกันเอง · ปลอดภัย</p>
                <form class="hero-search d-flex" action="<?= site_url('items') ?>" method="get"
                      style="max-width:500px;">
                    <input class="form-control" type="search" name="q"
                           placeholder="ค้นหาของที่ต้องการ เช่น iPhone, โซฟา, หนังสือ…">
                    <button type="submit" class="btn-search btn">
                        <i class="bi bi-search me-1"></i>ค้นหา
                    </button>
                </form>
                <div class="hero-quick d-flex flex-wrap gap-2 mt-3">
                    <a href="<?= site_url('items?is_free=1') ?>"><i class="bi bi-gift me-1"></i>แจกฟรี</a>
                    <a href="<?= site_url('items?category_id=1') ?>"><i class="bi bi-cpu me-1"></i>อุปกรณ์ไอที</a>
                    <a href="<?= site_url('items?category_id=3') ?>"><i class="bi bi-house me-1"></i>เฟอร์นิเจอร์</a>
                    <a href="<?= site_url('items/create') ?>"><i class="bi bi-plus-circle me-1"></i>ลงของเลย</a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-end align-items-center" style="font-size:8rem;opacity:.15;letter-spacing:-10px;">
                🏷️🛍️📦
            </div>
        </div>
    </div>
</section>

<!-- CATEGORIES -->
<section class="py-4">
    <div class="container-fluid px-3" style="max-width:1300px;margin:0 auto;">
        <div class="kl-section-head">
            <h4><i class="bi bi-tags me-1 text-green"></i>หมวดหมู่</h4>
            <a href="<?= site_url('items') ?>" class="see-all">ดูทั้งหมด →</a>
        </div>
        <div class="row row-cols-3 row-cols-sm-4 row-cols-md-6 row-cols-lg-10 g-2">
            <?php foreach ($categories as $cat): ?>
            <div class="col">
                <a href="<?= site_url('items?category_id=' . $cat['id']) ?>" class="kl-cat-card">
                    <div class="cat-icon"><i class="bi <?= htmlspecialchars($cat['icon']) ?>"></i></div>
                    <div class="cat-name"><?= htmlspecialchars($cat['name']) ?></div>
                </a>
            </div>
            <?php endforeach; ?>
            <div class="col">
                <a href="<?= site_url('items?is_free=1') ?>" class="kl-cat-card"
                   style="border-color:var(--kl-green);">
                    <div class="cat-icon" style="background:var(--kl-green);color:#fff;">
                        <i class="bi bi-gift"></i>
                    </div>
                    <div class="cat-name" style="color:var(--kl-green);">แจกฟรี</div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED ITEMS -->
<?php if (!empty($featured)): ?>
<section class="py-3" style="background:var(--kl-green-light);">
    <div class="container-fluid px-3" style="max-width:1300px;margin:0 auto;">
        <div class="kl-section-head">
            <h4><i class="bi bi-star-fill text-warning me-1"></i>ของแนะนำ</h4>
            <a href="<?= site_url('items') ?>" class="see-all">ดูทั้งหมด →</a>
        </div>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 g-3">
            <?php foreach ($featured as $item): ?>
            <div class="col"><?= $this->load->view('partials/item_card', ['item' => $item], TRUE) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- RECENT ITEMS -->
<section class="py-4">
    <div class="container-fluid px-3" style="max-width:1300px;margin:0 auto;">
        <div class="kl-section-head">
            <h4>ลงใหม่ล่าสุด</h4>
            <a href="<?= site_url('items') ?>" class="see-all">ดูทั้งหมด →</a>
        </div>
        <?php if (empty($recent)): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-box-seam display-4"></i>
                <p class="mt-2">ยังไม่มีของลงประกาศ <a href="<?= site_url('items/create') ?>">ลงของเป็นคนแรก!</a></p>
            </div>
        <?php else: ?>
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 g-3">
                <?php foreach ($recent as $item): ?>
                <div class="col"><?= $this->load->view('partials/item_card', ['item' => $item], TRUE) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA BANNER -->
<section style="background:linear-gradient(135deg,var(--kl-green) 0%,#0d7a44 100%);padding:3rem 0;">
    <div class="container text-center">
        <h3 class="fw-bold text-white mb-2">มีของจะขาย หรืออยากแจกฟรี?</h3>
        <p class="text-white mb-4" style="opacity:.88;">ลงประกาศได้ฟรีทันที ใช้เวลาไม่ถึง 2 นาที</p>
        <a href="<?= site_url('items/create') ?>" class="btn fw-bold px-5 py-2"
           style="background:var(--kl-orange);color:#fff;border-radius:10px;font-size:1.05rem;">
            <i class="bi bi-plus-circle me-2"></i>ลงของเลย!
        </a>
    </div>
</section>
