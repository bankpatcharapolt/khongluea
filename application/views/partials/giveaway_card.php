<?php
$img    = !empty($listing['primary_image'])
    ? base_url($listing['primary_image'])
    : base_url('assets/img/no-image.svg');
$left   = (int)$listing['quantity_left'];
$pct    = $listing['quantity_total'] > 0
    ? round($left / $listing['quantity_total'] * 100) : 0;
$ended  = strtotime($listing['pickup_end']) < time();
?>
<div class="kl-item-card" style="<?= $ended ? 'opacity:.6;' : '' ?>">

  <!-- Image -->
  <div class="card-img-wrap" style="position:relative;">
    <a href="<?= site_url('giveaway/view/'.$listing['id']) ?>" tabindex="-1">
      <img src="<?= $img ?>" alt="<?= htmlspecialchars($listing['title']) ?>" loading="lazy"
           style="width:100%;height:100%;object-fit:cover;">
    </a>
    <span class="badge-free" style="position:absolute;top:8px;left:8px;">ฟรี!</span>
    <?php if ($ended): ?>
      <div class="badge-sold">หมดเวลา</div>
    <?php elseif ($left === 0): ?>
      <div class="badge-sold" style="background:rgba(229,57,53,.75);">หมดแล้ว</div>
    <?php endif; ?>
  </div>

  <!-- Body -->
  <div class="card-body">
    <a href="<?= site_url('giveaway/view/'.$listing['id']) ?>" class="item-title">
      <?= htmlspecialchars($listing['title']) ?>
    </a>

    <!-- Donor -->
    <div style="font-size:.75rem;color:var(--muted);margin:.25rem 0;">
      <i class="bi bi-shop me-1"></i>
      <?= htmlspecialchars(mb_substr($listing['business_name'] ?: $listing['donor_name'], 0, 18)) ?>
    </div>

    <!-- Qty bar -->
    <div class="d-flex align-items-center gap-2 mt-1">
      <div class="progress flex-grow-1" style="height:5px;border-radius:99px;">
        <div class="progress-bar" role="progressbar" style="width:<?= $pct ?>%;background:var(--g);"></div>
      </div>
      <span style="font-size:.72rem;color:var(--muted);white-space:nowrap;">เหลือ <?= $left ?></span>
    </div>

    <!-- Time -->
    <div class="item-meta" style="margin-top:.4rem;">
      <span style="font-size:.72rem;">
        <i class="bi bi-clock me-1"></i>รับ <?= date('d/m H:i', strtotime($listing['pickup_start'])) ?>
        – <?= date('H:i', strtotime($listing['pickup_end'])) ?>
      </span>
    </div>
  </div>
</div>
