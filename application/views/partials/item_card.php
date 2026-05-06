<?php
$img = !empty($item['primary_image']) ? base_url($item['primary_image']) : base_url(IMG_PLACEHOLDER);
$is_free = ((float)$item['price'] <= 0);
$is_sold = ($item['status'] === 'sold');
$is_reserved = ($item['status'] === 'reserved');
$me = current_user();
$favorited = false; // default; set via JS state
?>
<div class="kl-item-card <?= $item['is_highlighted'] ? 'is-highlighted' : '' ?>">
    <div class="card-img-wrap">
        <a href="<?= site_url('items/' . $item['id']) ?>">
            <img src="<?= $img ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy">
        </a>
        <div class="img-badges">
            <?php if ($is_free): ?>
                <span class="badge-free">แจกฟรี</span>
            <?php endif; ?>
            <?php if ($item['is_bumped']): ?>
                <span class="badge-bump"><i class="bi bi-arrow-up"></i> ปักหมุด</span>
            <?php endif; ?>
        </div>
        <?php if ($is_sold): ?>
            <div class="badge-sold">ขายแล้ว</div>
        <?php elseif ($is_reserved): ?>
            <div class="badge-sold" style="background:rgba(249,115,22,.75);">จองแล้ว</div>
        <?php endif; ?>

        <?php if (is_logged_in() && $me && $me['id'] != $item['user_id']): ?>
            <button class="btn-fav fav-toggle-btn" data-item-id="<?= $item['id'] ?>"
                    title="บันทึกไว้ดูทีหลัง">
                <i class="bi bi-heart"></i>
            </button>
        <?php endif; ?>
    </div>

    <div class="card-body">
        <a href="<?= site_url('items/' . $item['id']) ?>" class="item-title">
            <?= htmlspecialchars($item['title']) ?>
        </a>

        <div>
            <?php
            $cond_labels = ['new'=>'ใหม่','like_new'=>'เหมือนใหม่','good'=>'ดี','fair'=>'พอใช้','poor'=>'ผ่านศึกมาเยอะ'];
            $cond_colors = ['new'=>'#1a9e5c','like_new'=>'#0d6efd','good'=>'#0dcaf0','fair'=>'#ffc107','poor'=>'#dc3545'];
            $cl = $cond_labels[$item['condition']] ?? $item['condition'];
            $cc = $cond_colors[$item['condition']] ?? '#6c757d';
            ?>
            <span class="item-condition" style="background:<?= $cc ?>22;color:<?= $cc ?>;border:1px solid <?= $cc ?>44;">
                <?= $cl ?>
            </span>
        </div>

        <?php if ($is_free): ?>
            <div class="item-price is-free"><span>ฟรี!</span></div>
        <?php else: ?>
            <div class="item-price">฿<?= number_format((float)$item['price'], 0) ?></div>
        <?php endif; ?>

        <div class="item-meta">
            <span><i class="bi bi-person me-1"></i><?= htmlspecialchars(truncate_text($item['seller_name'], 12)) ?></span>
            <span><?= time_ago($item['created_at']) ?></span>
        </div>
    </div>
</div>
