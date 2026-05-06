<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Reports <span class="badge bg-danger"><?= number_format($total) ?></span></h5>
    <div class="d-flex gap-2">
        <?php foreach (['pending','reviewed','resolved','dismissed'] as $s): ?>
            <a href="?status=<?= $s ?>" class="btn btn-sm <?= ($this->input->get('status') ?: 'pending') === $s ? 'btn-primary' : 'btn-outline-secondary' ?>">
                <?= ucfirst($s) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>Reporter</th><th>Type</th><th>Reason</th>
                    <th>Target</th><th>Status</th><th>Date</th><th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($reports as $r): ?>
            <tr>
                <td class="text-muted small"><?= $r['id'] ?></td>
                <td class="small"><?= htmlspecialchars($r['reporter_name'] ?? '—') ?></td>
                <td>
                    <?php if ($r['item_id']): ?>
                        <span class="badge bg-info text-dark">Item</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">User</span>
                    <?php endif; ?>
                </td>
                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($r['reason']) ?></span></td>
                <td class="small">
                    <?= $r['item_id'] ? '<a href="' . site_url('items/' . $r['item_id']) . '" target="_blank">' . htmlspecialchars(truncate_text($r['item_title'] ?? '', 30)) . '</a>' : htmlspecialchars($r['reported_user_name'] ?? '—') ?>
                </td>
                <td>
                    <?php $colors = ['pending'=>'warning','reviewed'=>'info','resolved'=>'success','dismissed'=>'secondary']; ?>
                    <span class="badge bg-<?= $colors[$r['status']] ?? 'secondary' ?> text-dark"><?= ucfirst($r['status']) ?></span>
                </td>
                <td class="text-muted small"><?= date('d M Y', strtotime($r['created_at'])) ?></td>
                <td>
                    <?php if ($r['status'] === 'pending'): ?>
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#resolveModal" data-report-id="<?= $r['id'] ?>">
                        Review
                    </button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($reports)): ?>
                <tr><td colspan="8" class="text-center text-muted py-4">No reports found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Resolve Modal -->
<div class="modal fade" id="resolveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold">Review Report</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="resolveForm" method="post">
                <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Action</label>
                        <select name="status" class="form-select">
                            <option value="resolved">Resolved — Action taken</option>
                            <option value="dismissed">Dismissed — No action needed</option>
                            <option value="reviewed">Mark as Reviewed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Admin Note</label>
                        <textarea name="note" class="form-control" rows="3" placeholder="Optional internal note"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('resolveModal').addEventListener('show.bs.modal', function(e) {
    const id = e.relatedTarget.dataset.reportId;
    document.getElementById('resolveForm').action = '<?= site_url('admin/Reports/resolve/') ?>' + id;
});
</script>
