<div class="container py-4" style="max-width:700px;">
  <div class="d-flex align-items-center gap-3 mb-4">
    <div style="width:44px;height:44px;background:var(--g-l);border-radius:12px;
                display:flex;align-items:center;justify-content:center;font-size:20px;">💬</div>
    <div>
      <h4 class="fw-800 mb-0">กล่องข้อความ</h4>
      <p class="text-muted small mb-0">การสนทนาทั้งหมดของคุณ</p>
    </div>
  </div>

  <?php if (empty($conversations)): ?>
    <div class="kl-empty py-5">
      <i class="bi bi-chat-square-dots"></i>
      <h6>ยังไม่มีการสนทนา</h6>
      <p class="small text-muted mb-3">เมื่อคุณติดต่อผู้ขาย การสนทนาจะแสดงที่นี่</p>
      <a href="<?= site_url('items') ?>" class="btn btn-primary px-4">เลือกชมสินค้า</a>
    </div>
  <?php else: ?>
    <div class="kl-card overflow-hidden">
      <?php
      $me = current_user();
      foreach ($conversations as $i => $conv):
        $is_buyer     = ((int)$conv['buyer_id']  === (int)$me['id']);
        $other_name   = $is_buyer ? $conv['seller_name']  : $conv['buyer_name'];
        $other_avatar = $is_buyer ? $conv['seller_avatar'] : $conv['buyer_avatar'];
        $unread       = (int)($conv['unread_count'] ?? 0);
      ?>
      <a href="<?= site_url('chat/'.$conv['id']) ?>"
         class="d-flex gap-3 p-3 text-decoration-none <?= $i > 0 ? 'border-top' : '' ?>"
         style="color:var(--text);background:<?= $unread > 0 ? 'var(--g-l)' : '#fff' ?>;
                transition:background .15s;"
         onmouseover="this.style.background='var(--g-l)'"
         onmouseout="this.style.background='<?= $unread > 0 ? 'var(--g-l)' : '#fff' ?>'">

        <!-- Avatar -->
        <div class="flex-shrink-0 position-relative">
          <?php if ($other_avatar): ?>
            <img src="<?= base_url($other_avatar) ?>" class="rounded-circle"
                 width="48" height="48" style="object-fit:cover;" alt="">
          <?php else: ?>
            <div class="rounded-circle d-flex align-items-center justify-content-center fw-800 text-white"
                 style="width:48px;height:48px;background:var(--g);font-size:18px;">
              <?= strtoupper(mb_substr($other_name, 0, 1)) ?>
            </div>
          <?php endif; ?>
          <?php if ($unread > 0): ?>
            <span class="position-absolute top-0 end-0 badge rounded-pill"
                  style="background:var(--orange);font-size:.62rem;min-width:16px;padding:2px 5px;">
              <?= $unread > 99 ? '99+' : $unread ?>
            </span>
          <?php endif; ?>
        </div>

        <!-- Content -->
        <div class="flex-grow-1 min-w-0">
          <div class="d-flex justify-content-between align-items-start">
            <span class="fw-700 small"><?= htmlspecialchars($other_name) ?></span>
            <?php if ($conv['last_message_at']): ?>
              <span class="text-muted flex-shrink-0 ms-2" style="font-size:.7rem;">
                <?= time_ago($conv['last_message_at']) ?>
              </span>
            <?php endif; ?>
          </div>

          <!-- Item tag -->
          <div class="d-flex align-items-center gap-1 mt-1">
            <span class="badge rounded-pill px-2 py-1"
                  style="background:var(--g-l);color:var(--g);border:1px solid var(--g-m);
                         font-size:.68rem;font-weight:600;max-width:140px;overflow:hidden;
                         text-overflow:ellipsis;white-space:nowrap;">
              <?= htmlspecialchars(mb_substr($conv['item_title'] ?? '', 0, 20)) ?>
            </span>
          </div>

          <!-- Last message preview -->
          <div class="text-muted mt-1 text-truncate <?= $unread > 0 ? 'fw-600' : '' ?>"
               style="font-size:.82rem;max-width:100%;">
            <?= htmlspecialchars(mb_substr($conv['last_message'] ?? 'เริ่มการสนทนา...', 0, 55)) ?>
          </div>
        </div>

      </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
