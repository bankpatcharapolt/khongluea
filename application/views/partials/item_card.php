<?php
$img = !empty($item['primary_image']) ? base_url($item['primary_image']) : base_url('assets/img/no-image.svg');
$price = (float)$item['price'];
$is_free = ($price <= 0);
$is_sold = ($item['status'] === 'sold');
$is_res  = ($item['status'] === 'reserved');
$me = current_user();
$cond_map = ['new'=>['ใหม่','#00b14f','#e6f7ee'],'like_new'=>['เหมือนใหม่','#2196f3','#e3f2fd'],'good'=>['ดี','#ff9800','#fff3e0'],'fair'=>['พอใช้','#9e9e9e','#f5f5f5'],'poor'=>['ผ่านศึก','#e53935','#ffebee']];
[$cl,$cc,$cb] = $cond_map[$item['condition']] ?? [ucfirst($item['condition']),'#9e9e9e','#f5f5f5'];
?>
<div class="kl-item-card<?= $item['is_highlighted'] ? ' is-highlighted' : '' ?>">
  <!-- Image -->
  <div class="card-img-wrap">
    <a href="<?= site_url('items/'.$item['id']) ?>" tabindex="-1">
      <img src="<?= $img ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
    </a>
    <div class="img-badges">
      <?php if ($is_free): ?><span class="badge-free">ฟรี!</span><?php endif; ?>
      <?php if ($item['is_bumped']): ?><span class="badge-bump"><i class="bi bi-arrow-up"></i>ปักหมุด</span><?php endif; ?>
    </div>
    <?php if ($is_sold): ?>
      <div class="badge-sold">ขายแล้ว</div>
    <?php elseif ($is_res): ?>
      <div class="badge-sold" style="background:rgba(249,115,22,.82);">🔒 จองแล้ว</div>
    <?php endif; ?>
    <?php if (is_logged_in() && $me && $me['id'] != $item['user_id']): ?>
      <button class="btn-fav fav-toggle-btn" data-item-id="<?= $item['id'] ?>" title="บันทึก">
        <i class="bi bi-heart"></i>
      </button>
    <?php endif; ?>
  </div>
  <!-- Body -->
  <div class="card-body">
    <a href="<?= site_url('items/'.$item['id']) ?>" class="item-title">
      <?= htmlspecialchars($item['title']) ?>
    </a>
    <?php if (!empty($item['location_text'])): ?>
    <div class="item-location text-truncate">
      <i class="bi bi-geo-alt me-1" style="color:var(--g);font-size:.75rem;"></i><?= htmlspecialchars(mb_substr($item['location_text'],0,22)) ?>
    </div>
    <?php endif; ?>
    <?php if ($is_free): ?>
      <div class="item-price is-free"><span>แจกฟรี!</span></div>
    <?php else: ?>
      <div class="item-price">฿<?= number_format($price, 0) ?></div>
    <?php endif; ?>
    <div class="item-meta">
      <span class="text-truncate" style="max-width:60%;"><i class="bi bi-person me-1"></i><?= htmlspecialchars(mb_substr($item['seller_name'],0,10)) ?></span>
      <span><?= time_ago($item['created_at']) ?></span>
    </div>
  </div>
</div>
