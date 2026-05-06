<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Users <span class="badge bg-secondary"><?= number_format($total) ?></span></h5>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0">
        <form class="d-flex gap-2" method="get">
            <input type="search" name="q" class="form-control form-control-sm" placeholder="Search name or email…" value="<?= htmlspecialchars($this->input->get('q', TRUE) ?? '') ?>" style="max-width:280px;">
            <select name="role" class="form-select form-select-sm w-auto">
                <option value="">All Roles</option>
                <option value="user"  <?= $this->input->get('role') === 'user'  ? 'selected' : '' ?>>Users</option>
                <option value="admin" <?= $this->input->get('role') === 'admin' ? 'selected' : '' ?>>Admins</option>
            </select>
            <button class="btn btn-primary btn-sm">Search</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>Name</th><th>Email</th><th>Role</th>
                    <th>Credits</th><th>Premium</th><th>Status</th><th>Joined</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $u): ?>
            <tr class="<?= $u['is_banned'] ? 'table-danger' : '' ?>">
                <td class="text-muted small"><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['name']) ?></td>
                <td class="text-muted small"><?= htmlspecialchars($u['email']) ?></td>
                <td><span class="badge bg-<?= $u['role'] === 'admin' ? 'danger' : 'secondary' ?>"><?= ucfirst($u['role']) ?></span></td>
                <td><?= number_format($u['credits']) ?></td>
                <td><?= $u['premium_status'] ? '<span class="badge bg-warning text-dark">Premium</span>' : '<span class="badge bg-light text-muted border">Free</span>' ?></td>
                <td><?= $u['is_banned'] ? '<span class="badge bg-danger">Banned</span>' : '<span class="badge bg-success">Active</span>' ?></td>
                <td class="text-muted small"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="<?= site_url('admin/users/ban/' . $u['id']) ?>"
                           class="btn btn-sm <?= $u['is_banned'] ? 'btn-outline-success' : 'btn-outline-danger' ?>"
                           onclick="return confirm('<?= $u['is_banned'] ? 'Unban' : 'Ban' ?> this user?')">
                            <?= $u['is_banned'] ? 'Unban' : 'Ban' ?>
                        </a>
                        <button class="btn btn-sm btn-outline-secondary"
                                data-bs-toggle="modal" data-bs-target="#creditModal"
                                data-user-id="<?= $u['id'] ?>" data-user-name="<?= htmlspecialchars($u['name']) ?>">
                            Credits
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pagination): ?>
    <div class="card-footer bg-white d-flex justify-content-center border-0">
        <?= $pagination ?>
    </div>
    <?php endif; ?>
</div>

<!-- Credit Modal -->
<div class="modal fade" id="creditModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold">Adjust Credits</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="creditForm" method="post">
                <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
                <div class="modal-body">
                    <p class="small text-muted mb-2">User: <strong id="creditModalUserName"></strong></p>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Amount (+ add / - deduct)</label>
                        <input type="number" name="amount" class="form-control" required placeholder="e.g. 50 or -20">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Note</label>
                        <input type="text" name="note" class="form-control" placeholder="Reason (optional)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('creditModal').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    const uid = btn.dataset.userId;
    const name = btn.dataset.userName;
    document.getElementById('creditModalUserName').textContent = name;
    document.getElementById('creditForm').action = '<?= site_url('admin/users/credits/') ?>' + uid;
});
</script>
