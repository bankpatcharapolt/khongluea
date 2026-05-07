<div class="container py-4" style="max-width:900px;">
  <div class="d-flex align-items-center gap-3 mb-4">
    <div style="width:44px;height:44px;background:var(--g-l);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">🏪</div>
    <div><h4 class="fw-800 mb-0">รายการแจกของของฉัน</h4></div>
    <a href="<?= site_url('donor/create') ?>" class="btn btn-primary ms-auto fw-700" style="border-radius:9px;">
      <i class="bi bi-plus-lg me-1"></i>ลงประกาศใหม่
    </a>
  </div>

  <?php if (empty($listings)): ?>
    <div class="kl-empty py-5 kl-card">
      <i class="bi bi-gift"></i>
      <h6>ยังไม่มีประกาศ</h6>
      <a href="<?= site_url('donor/create') ?>" class="btn btn-primary px-4 mt-2">ลงประกาศแจกของแรก</a>
    </div>
  <?php else: ?>
  <div class="kl-card overflow-hidden">
    <?php
    $status_cfg = [
      'active'    => ['bg-success','active'],
      'paused'    => ['bg-warning text-dark','หยุดชั่วคราว'],
      'completed' => ['bg-info text-dark','ครบแล้ว'],
      'expired'   => ['bg-secondary','หมดเวลา'],
    ];
    foreach ($listings as $i => $l):
      [$sc,$sl] = $status_cfg[$l['status']] ?? ['bg-light','?'];
      $img = !empty($l['primary_image'])
          ? base_url($l['primary_image']) : base_url('assets/img/no-image.svg');
    ?>
    <div class="d-flex gap-3 p-3 <?= $i > 0 ? 'border-top' : '' ?>">
      <img src="<?= $img ?>" class="rounded flex-shrink-0"
           width="80" height="70" style="object-fit:cover;border:1.5px solid var(--border);" alt="">
      <div class="flex-grow-1 min-w-0">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-1">
          <a href="<?= site_url('giveaway/view/'.$l['id']) ?>" class="fw-700 text-decoration-none" style="color:var(--text);">
            <?= htmlspecialchars($l['title']) ?>
          </a>
          <span class="badge <?= $sc ?>" style="font-size:.7rem;"><?= $sl ?></span>
        </div>
        <div class="small text-muted mt-1">
          เหลือ <strong><?= $l['quantity_left'] ?></strong>/<?= $l['quantity_total'] ?> ·
          รับ <?= date('d M H:i', strtotime($l['pickup_start'])) ?>–<?= date('H:i', strtotime($l['pickup_end'])) ?> ·
          จอง <strong><?= $l['reservation_count'] ?></strong> รายการ
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
          <a href="<?= site_url('donor/verify/'.$l['id']) ?>" class="btn btn-sm btn-primary" style="border-radius:7px;font-size:.78rem;">
            <i class="bi bi-check2-circle me-1"></i>ยืนยันรับของ
          </a>
          <a href="<?= site_url('donor/edit/'.$l['id']) ?>" class="btn btn-sm btn-outline-secondary" style="border-radius:7px;font-size:.78rem;">
            <i class="bi bi-pencil me-1"></i>แก้ไข
          </a>
          <form method="post" action="<?= site_url('donor/delete/'.$l['id']) ?>" class="d-inline">
            <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
            <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:7px;font-size:.78rem;"
                    onclick="return confirm('ลบประกาศนี้ถาวรใช่ไหม?')">
              <i class="bi bi-trash me-1"></i>ลบ
            </button>
          </form>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
