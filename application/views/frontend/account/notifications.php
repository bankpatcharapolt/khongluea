<div class="container py-4" style="max-width:700px;">
  <div class="d-flex align-items-center gap-3 mb-4">
    <div style="width:44px;height:44px;background:var(--g-l);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">🔔</div>
    <div>
      <h4 class="fw-800 mb-0">การแจ้งเตือน</h4>
      <p class="text-muted small mb-0">ข้อความและการแจ้งเตือนทั้งหมด</p>
    </div>
  </div>

  <div class="kl-card overflow-hidden">
    <?php if (empty($notifs)): ?>
      <div class="kl-empty py-5">
        <i class="bi bi-bell-slash"></i>
        <h6>ยังไม่มีการแจ้งเตือน</h6>
        <p class="small text-muted">เมื่อมีกิจกรรมใหม่จะแสดงที่นี่</p>
      </div>
    <?php else: ?>
      <?php foreach ($notifs as $i => $n): ?>
      <div class="d-flex gap-3 p-3 <?= $i > 0 ? 'border-top' : '' ?> <?= !$n['is_read'] ? 'bg-green-l' : '' ?>">
        <div style="width:40px;height:40px;background:var(--g-l);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:18px;">
          <?php
          $icons = ['new_message'=>'💬','item_reserved'=>'📦','report_resolved'=>'✅','credit_added'=>'🪙'];
          echo $icons[$n['type']] ?? '🔔';
          ?>
        </div>
        <div class="flex-grow-1">
          <div class="fw-600 small"><?= htmlspecialchars($n['title']) ?></div>
          <?php if ($n['body']): ?>
            <div class="text-muted" style="font-size:.82rem;"><?= htmlspecialchars($n['body']) ?></div>
          <?php endif; ?>
          <div class="text-muted mt-1" style="font-size:.72rem;"><?= time_ago($n['created_at']) ?></div>
        </div>
        <?php if (!$n['is_read']): ?>
          <div style="width:8px;height:8px;background:var(--g);border-radius:50%;flex-shrink:0;margin-top:6px;"></div>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</div>
