<div class="container-fluid px-3 px-lg-4 py-3" style="max-width:1400px;margin:0 auto;">
  <div class="row g-3">

    <!-- Filter sidebar -->
    <div class="col-lg-2 col-md-3 d-none d-md-block">
      <div class="kl-filter-card">
        <div class="kl-filter-head"><i class="bi bi-sliders2"></i>กรองผลลัพธ์</div>
        <div class="kl-filter-body">
          <form method="get" action="<?= site_url('items') ?>">
            <?php if (!empty($filters['search'])): ?>
              <input type="hidden" name="q" value="<?= htmlspecialchars($filters['search']) ?>">
            <?php endif; ?>

            <div class="kl-filter-section">
              <div class="kl-filter-label">หมวดหมู่</div>
              <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">ทั้งหมด</option>
                <?php foreach ($categories as $c): ?>
                  <option value="<?= $c['id'] ?>" <?= $filters['category_id']==$c['id']?'selected':'' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="kl-filter-section">
              <div class="kl-filter-label">ประเภท</div>
              <?php foreach ([''=> 'ทั้งหมด','1'=>'🎁 แจกฟรี','0'=>'💰 มีราคา'] as $v=>$l): ?>
              <div class="form-check mb-1">
                <input class="form-check-input" type="radio" name="is_free" value="<?= $v ?>" id="t<?= $v ?>"
                       <?= (string)$filters['is_free']===(string)$v && ($v!=='' || $filters['is_free']===''||$filters['is_free']===NULL) ? 'checked' : ($v===''&&($filters['is_free']===NULL||$filters['is_free']==='')&&true?'checked':'') ?>
                       onchange="this.form.submit()">
                <label class="form-check-label small" for="t<?= $v ?>"><?= $l ?></label>
              </div>
              <?php endforeach; ?>
            </div>

            <div class="kl-filter-section">
              <div class="kl-filter-label">สภาพ</div>
              <select name="condition" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">ทุกสภาพ</option>
                <?php foreach (['new'=>'ใหม่','like_new'=>'เหมือนใหม่','good'=>'ดี','fair'=>'พอใช้','poor'=>'ผ่านศึก'] as $v=>$l): ?>
                  <option value="<?= $v ?>" <?= $filters['condition']===$v?'selected':'' ?>><?= $l ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="kl-filter-section">
              <div class="kl-filter-label">ราคา (฿)</div>
              <div class="row g-1">
                <div class="col-6"><input type="number" name="min_price" class="form-control form-control-sm" placeholder="ต่ำสุด" value="<?= htmlspecialchars($filters['min_price']??'') ?>"></div>
                <div class="col-6"><input type="number" name="max_price" class="form-control form-control-sm" placeholder="สูงสุด" value="<?= htmlspecialchars($filters['max_price']??'') ?>"></div>
              </div>
              <button type="submit" class="btn btn-primary btn-sm w-100 mt-2 fw-600">ค้นหา</button>
            </div>

            <div class="kl-filter-section">
              <a href="<?= site_url('items') ?>" class="btn btn-outline-secondary btn-sm w-100">ล้างตัวกรอง</a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Items -->
    <div class="col-lg-10 col-md-9">
      <!-- Topbar -->
      <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="text-muted small">
          <?php if (!empty($filters['search'])): ?>
            ผลลัพธ์: "<strong class="text-dark"><?= htmlspecialchars($filters['search']) ?></strong>" —
          <?php endif; ?>
          พบ <strong class="text-dark"><?= number_format($total) ?></strong> รายการ
        </div>
        <div class="d-flex gap-2 align-items-center">
          <button class="btn btn-outline-secondary btn-sm d-md-none" data-bs-toggle="offcanvas" data-bs-target="#mFilter">
            <i class="bi bi-sliders2 me-1"></i>กรอง
          </button>
          <select class="form-select form-select-sm w-auto" onchange="location.href=this.value" style="font-size:.83rem;">
            <?php $sorts=['newest'=>'ล่าสุด','price_asc'=>'ราคาน้อย→มาก','price_desc'=>'ราคามาก→น้อย'];
            foreach ($sorts as $v=>$l):
              // array_filter โดยปกติกิน "0" และ "" → ต้องเก็บ is_free=1 ไว้
              $_f = array_filter($filters, function($v){ return $v !== '' && $v !== NULL; });
              $url=site_url('items?').http_build_query(array_merge($_f,['sort'=>$v])); ?>
            <option value="<?= $url ?>" <?= $filters['sort']===$v?'selected':'' ?>><?= $l ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <?php if (empty($items)): ?>
        <div class="kl-empty" style="padding:4rem 1rem;">
          <i class="bi bi-search"></i>
          <h6>ไม่พบสินค้า</h6>
          <p class="mb-3">ลองเปลี่ยนเงื่อนไขการค้นหา</p>
          <a href="<?= site_url('items/create') ?>" class="btn btn-primary px-4">ลงประกาศเป็นคนแรก!</a>
        </div>
      <?php else: ?>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 g-3">
          <?php foreach ($items as $item): ?>
          <div class="col"><?= $this->load->view('partials/item_card',['item'=>$item],TRUE) ?></div>
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
<div class="offcanvas offcanvas-start" tabindex="-1" id="mFilter" style="max-width:280px;">
  <div class="offcanvas-header" style="background:var(--g);color:#fff;">
    <h6 class="offcanvas-title fw-700"><i class="bi bi-sliders2 me-2"></i>กรองผลลัพธ์</h6>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <form method="get" action="<?= site_url('items') ?>">
      <?php if (!empty($filters['search'])): ?><input type="hidden" name="q" value="<?= htmlspecialchars($filters['search']) ?>"><?php endif; ?>
      <div class="mb-3"><label class="form-label fw-600 small">หมวดหมู่</label>
        <select name="category_id" class="form-select">
          <option value="">ทั้งหมด</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $filters['category_id']==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3"><label class="form-label fw-600 small">สภาพ</label>
        <select name="condition" class="form-select">
          <option value="">ทุกสภาพ</option>
          <?php foreach (['new'=>'ใหม่','like_new'=>'เหมือนใหม่','good'=>'ดี','fair'=>'พอใช้','poor'=>'ผ่านศึก'] as $v=>$l): ?>
            <option value="<?= $v ?>" <?= $filters['condition']===$v?'selected':'' ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3"><label class="form-label fw-600 small">ราคา (฿)</label>
        <div class="row g-2">
          <div class="col"><input type="number" name="min_price" class="form-control" placeholder="ต่ำสุด" value="<?= $filters['min_price']??'' ?>"></div>
          <div class="col"><input type="number" name="max_price" class="form-control" placeholder="สูงสุด" value="<?= $filters['max_price']??'' ?>"></div>
        </div>
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary fw-700">ค้นหา</button>
        <a href="<?= site_url('items') ?>" class="btn btn-outline-secondary">ล้างตัวกรอง</a>
      </div>
    </form>
  </div>
</div>
