<!-- ===== HERO ===== -->
<section class="kl-hero">
  <div class="container-fluid px-3 px-lg-4 kl-hero-inner" style="max-width:1400px;margin:0 auto;">
    <div class="row align-items-center">
      <div class="col-lg-8">
        <h1>ซื้อ ขาย แจกของมือสอง ของกิน<br class="d-none d-md-block">ง่ายๆ ในชุมชน</h1>
        <p class="tagline">ไม่มีค่าธรรมเนียม · นัดรับส่งกันเอง · ปลอดภัย · เริ่มใช้ได้เลยฟรี</p>

        <div class="hero-search">
          <form action="<?= site_url('items') ?>" method="get" class="d-contents" style="display:contents;">
            <input type="search" name="q" placeholder="ค้นหาของที่ต้องการ เช่น iPhone, โซฟา, จักรยาน…"
                   value="<?= htmlspecialchars($this->input->get('q',TRUE) ?? '') ?>">
            <button type="submit" class="btn-search">
              <i class="bi bi-search"></i> ค้นหา
            </button>
          </form>
        </div>

        <div class="hero-chips">
          <a href="<?= site_url('items?is_free=1') ?>">🎁 แจกฟรี</a>
          <a href="<?= site_url('items?category_id=1') ?>">📱 มือถือ/IT</a>
          <a href="<?= site_url('items?category_id=3') ?>">🪑 เฟอร์นิเจอร์</a>
          <a href="<?= site_url('items?category_id=4') ?>">📚 หนังสือ</a>
          <a href="<?= site_url('items/create') ?>">➕ ลงของเลย!</a>
        </div>

        <div class="hero-stats">
          <span><i class="bi bi-shield-check"></i> ฟรีตลอด ไม่มีค่าธรรมเนียม</span>
          <span><i class="bi bi-chat-dots"></i> แชทตรงกับผู้ขาย</span>
          <span><i class="bi bi-geo-alt"></i> ทั่วประเทศไทย</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== CATEGORIES ===== -->
<div class="kl-section">
  <div class="container-fluid px-3 px-lg-4" style="max-width:1400px;margin:0 auto;">
    <div class="kl-section-head">
      <h4><span class="sec-bar"></span>หมวดหมู่ยอดนิยม</h4>
      <a href="<?= site_url('items') ?>" class="see-all">ดูทั้งหมด <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row row-cols-3 row-cols-sm-4 row-cols-md-6 row-cols-xl-11 g-2">
      <?php foreach ($categories as $cat): ?>
      <div class="col">
        <a href="<?= site_url('items?category_id='.$cat['id']) ?>" class="kl-cat-card">
          <div class="cat-icon"><i class="bi <?= htmlspecialchars($cat['icon']) ?>"></i></div>
          <div class="cat-name"><?= htmlspecialchars($cat['name']) ?></div>
        </a>
      </div>
      <?php endforeach; ?>
      <div class="col">
        <a href="<?= site_url('items?is_free=1') ?>" class="kl-cat-card" style="border-color:var(--g);">
          <div class="cat-icon" style="background:var(--g);color:#fff;"><i class="bi bi-gift"></i></div>
          <div class="cat-name" style="color:var(--g);">แจกฟรี</div>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- ===== FEATURED ===== -->
<?php if (!empty($featured)): ?>
<div class="kl-featured-bg">
  <div class="container-fluid px-3 px-lg-4" style="max-width:1400px;margin:0 auto;">
    <div class="kl-section-head">
      <h4><span class="sec-bar"></span><i class="bi bi-star-fill text-warning me-1"></i>ของแนะนำ</h4>
      <a href="<?= site_url('items') ?>" class="see-all">ดูทั้งหมด <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 g-3">
      <?php foreach ($featured as $item): ?>
      <div class="col"><?= $this->load->view('partials/item_card',['item'=>$item],TRUE) ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- ===== RECENT ===== -->
<div class="kl-section">
  <div class="container-fluid px-3 px-lg-4" style="max-width:1400px;margin:0 auto;">
    <div class="kl-section-head">
      <h4><span class="sec-bar"></span>ลงใหม่ล่าสุด</h4>
      <a href="<?= site_url('items') ?>" class="see-all">ดูทั้งหมด <i class="bi bi-arrow-right"></i></a>
    </div>
    <?php if (empty($recent)): ?>
      <div class="kl-empty">
        <i class="bi bi-box-seam"></i>
        <h6>ยังไม่มีของลงประกาศ</h6>
        <p class="mb-3">เป็นคนแรกที่ลงประกาศในชุมชน!</p>
        <a href="<?= site_url('items/create') ?>" class="btn btn-primary px-4">ลงของเลย</a>
      </div>
    <?php else: ?>
      <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 g-3">
        <?php foreach ($recent as $item): ?>
        <div class="col"><?= $this->load->view('partials/item_card',['item'=>$item],TRUE) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- ===== HOW IT WORKS ===== -->
<div style="background:#fff;padding:2.5rem 0;border-top:1.5px solid var(--border);">
  <div class="container-fluid px-3 px-lg-4" style="max-width:1400px;margin:0 auto;">
    <div class="text-center mb-4">
      <h4 class="fw-800">วิธีใช้ ของเหลือ ง่ายมาก</h4>
    </div>
    <div class="row g-4 text-center">
      <?php
      $steps = [
        ['bi-person-plus','1. สมัครฟรี','สมัครสมาชิกใช้เวลา 1 นาที ไม่มีค่าใช้จ่าย'],
        ['bi-camera','2. ลงประกาศ','ถ่ายรูป ตั้งราคา กดลงได้เลย'],
        ['bi-chat-dots','3. แชทตกลง','คุยกับผู้ซื้อ/ขายผ่านแชทในแอป'],
        ['bi-box2-heart','4. นัดรับของ','นัดรับส่งกันเอง ไม่ผ่านระบบ'],
      ];
      foreach ($steps as [$icon,$title,$desc]): ?>
      <div class="col-6 col-md-3">
        <div style="width:56px;height:56px;background:var(--g-l);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;font-size:1.5rem;color:var(--g);">
          <i class="bi <?= $icon ?>"></i>
        </div>
        <div class="fw-700 mb-1" style="font-size:.9rem;"><?= $title ?></div>
        <div class="text-muted" style="font-size:.8rem;"><?= $desc ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ===== CTA ===== -->
<div class="kl-cta">
  <h3>มีของจะขาย หรืออยากแจกฟรี?</h3>
  <p style="opacity:.88;margin-bottom:1.5rem;">ลงประกาศได้ฟรีทันที ใช้เวลาไม่ถึง 2 นาที</p>
  <a href="<?= site_url('items/create') ?>" class="btn-cta">
    <i class="bi bi-plus-circle-fill"></i> ลงของเลย!
  </a>
</div>
