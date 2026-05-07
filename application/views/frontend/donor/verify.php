<div class="container py-4" style="max-width:680px;">
  <div class="d-flex align-items-center gap-3 mb-4">
    <div style="width:44px;height:44px;background:var(--g-l);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;">✅</div>
    <div>
      <h4 class="fw-800 mb-0">ยืนยันการรับของ</h4>
      <p class="text-muted small mb-0"><?= htmlspecialchars($listing['title']) ?></p>
    </div>
  </div>

  <?php if (empty($pending)): ?>
    <div class="kl-empty py-5 kl-card">
      <i class="bi bi-inbox"></i>
      <h6>ไม่มีการจองที่รอยืนยัน</h6>
      <a href="<?= site_url('donor/listings') ?>" class="btn btn-outline-secondary mt-2">กลับ</a>
    </div>
  <?php else: ?>

    <!-- Manual booking ref entry -->
    <div class="kl-card p-4 mb-4">
      <h6 class="fw-700 mb-3">🔍 ค้นหาจาก Booking ID</h6>
      <div class="input-group">
        <input type="text" id="searchRef" class="form-control" placeholder="KL-XXXXXX" style="text-transform:uppercase;letter-spacing:2px;">
        <button type="button" id="btnSearch" class="btn btn-primary fw-600">ค้นหา</button>
      </div>
    </div>

    <!-- Pending list -->
    <div class="kl-card overflow-hidden">
      <div class="kl-card-head">รายการจองที่รอรับของ (<?= count($pending) ?>)</div>
      <?php foreach ($pending as $i => $res): ?>
      <div id="row_<?= $res['id'] ?>" class="d-flex gap-3 p-3 align-items-center <?= $i > 0 ? 'border-top' : '' ?>">
        <div class="flex-grow-1">
          <div class="fw-700"><?= htmlspecialchars($res['receiver_name']) ?></div>
          <div class="small text-muted">
            <?= $res['receiver_phone'] ?? '' ?>
          </div>
          <div class="mt-1">
            <span class="badge rounded-pill px-3" style="background:var(--g-l);color:var(--g);font-size:.78rem;letter-spacing:2px;">
              <?= $res['booking_ref'] ?>
            </span>
          </div>
        </div>
        <form method="post" action="<?= site_url('donor/verify/'.$listing['id']) ?>">
          <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
          <input type="hidden" name="reservation_id" value="<?= $res['id'] ?>">
          <button type="submit" class="btn btn-primary fw-700" style="border-radius:9px;white-space:nowrap;"
                  onclick="return confirm('ยืนยันว่า <?= addslashes($res['receiver_name']) ?> มารับของแล้วใช่ไหม?')">
            <i class="bi bi-check2 me-1"></i>ยืนยันรับ
          </button>
        </form>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<script>
// Highlight row matching booking ref input
document.getElementById('btnSearch')?.addEventListener('click', function() {
  const ref = document.getElementById('searchRef').value.trim().toUpperCase();
  if (!ref) return;
  document.querySelectorAll('[id^="row_"]').forEach(r => r.style.background = '');
  const badges = document.querySelectorAll('.badge');
  badges.forEach(b => {
    if (b.textContent.trim() === ref) {
      const row = b.closest('[id^="row_"]');
      if (row) {
        row.style.background = 'var(--g-l)';
        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    }
  });
});
</script>
