<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Premium Packages</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPkgModal">
        <i class="bi bi-plus-lg me-1"></i>Add Package
    </button>
</div>

<div class="row g-4">
<?php foreach ($packages as $pkg): ?>
<div class="col-md-6 col-xl-4">
    <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3">
            <h6 class="fw-bold mb-0"><?= htmlspecialchars($pkg['name']) ?></h6>
            <?= $pkg['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?>
        </div>
        <div class="card-body">
            <p class="text-muted small"><?= htmlspecialchars($pkg['description'] ?? '') ?></p>
            <dl class="row small mb-0">
                <dt class="col-7 text-muted">Price</dt>
                <dd class="col-5 fw-semibold"><?= number_format($pkg['price_in_credits']) ?> credits</dd>
                <dt class="col-7 text-muted">Duration</dt>
                <dd class="col-5"><?= $pkg['duration_days'] ? $pkg['duration_days'] . ' days' : 'One-time' ?></dd>
                <dt class="col-7 text-muted">Max Listings</dt>
                <dd class="col-5"><?= $pkg['max_listings'] ?? 'Unlimited' ?></dd>
                <dt class="col-7 text-muted">Bumps</dt>
                <dd class="col-5"><?= $pkg['can_bump'] ? $pkg['bump_quota'] : '—' ?></dd>
                <dt class="col-7 text-muted">Highlights</dt>
                <dd class="col-5"><?= $pkg['can_highlight'] ? $pkg['highlight_quota'] : '—' ?></dd>
            </dl>
        </div>
        <div class="card-footer bg-white border-0 pb-3">
            <button class="btn btn-sm btn-outline-secondary edit-pkg-btn"
                    data-bs-toggle="modal" data-bs-target="#editPkgModal"
                    data-pkg='<?= htmlspecialchars(json_encode($pkg)) ?>'>
                Edit
            </button>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>

<!-- Add Package Modal -->
<div class="modal fade" id="addPkgModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="post" action="<?= site_url('admin/packages/create') ?>">
            <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
            <div class="modal-content">
                <div class="modal-header"><h6 class="modal-title fw-bold">Add Package</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Price (Credits)</label><input type="number" name="price_in_credits" class="form-control" value="0" min="0" required></div>
                        <div class="col-12"><label class="form-label fw-semibold">รายละเอียด</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Duration (days)</label><input type="number" name="duration_days" class="form-control" placeholder="Leave blank = one-time"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Max Listings</label><input type="number" name="max_listings" class="form-control" placeholder="Leave blank = unlimited"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Sort Order</label><input type="number" name="sort_order" class="form-control" value="0"></div>
                        <div class="col-md-3"><div class="form-check mt-3"><input class="form-check-input" type="checkbox" name="can_bump" value="1"><label class="form-check-label fw-semibold">Can Bump</label></div></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Bump Quota</label><input type="number" name="bump_quota" class="form-control" value="0" min="0"></div>
                        <div class="col-md-3"><div class="form-check mt-3"><input class="form-check-input" type="checkbox" name="can_highlight" value="1"><label class="form-check-label fw-semibold">Can Highlight</label></div></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Highlight Quota</label><input type="number" name="highlight_quota" class="form-control" value="0" min="0"></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary btn-sm">Save</button></div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Package Modal -->
<div class="modal fade" id="editPkgModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="editPkgForm" method="post">
            <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
            <div class="modal-content">
                <div class="modal-header"><h6 class="modal-title fw-bold">Edit Package</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label fw-semibold">Name</label><input type="text" name="name" id="epName" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold">Price (Credits)</label><input type="number" name="price_in_credits" id="epPrice" class="form-control" min="0" required></div>
                        <div class="col-12"><label class="form-label fw-semibold">รายละเอียด</label><textarea name="description" id="epDesc" class="form-control" rows="2"></textarea></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Duration (days)</label><input type="number" name="duration_days" id="epDuration" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Max Listings</label><input type="number" name="max_listings" id="epMaxList" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label fw-semibold">Sort Order</label><input type="number" name="sort_order" id="epOrder" class="form-control"></div>
                        <div class="col-md-3"><div class="form-check mt-3"><input class="form-check-input" type="checkbox" name="can_bump" id="epBump" value="1"><label class="form-check-label fw-semibold">Can Bump</label></div></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Bump Quota</label><input type="number" name="bump_quota" id="epBumpQ" class="form-control"></div>
                        <div class="col-md-3"><div class="form-check mt-3"><input class="form-check-input" type="checkbox" name="can_highlight" id="epHL" value="1"><label class="form-check-label fw-semibold">Can Highlight</label></div></div>
                        <div class="col-md-3"><label class="form-label fw-semibold">Highlight Quota</label><input type="number" name="highlight_quota" id="epHLQ" class="form-control"></div>
                        <div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="epActive" value="1"><label class="form-check-label fw-semibold">Active</label></div></div>
                    </div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary btn-sm">Update</button></div>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.edit-pkg-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const p = JSON.parse(this.dataset.pkg);
        document.getElementById('epName').value     = p.name;
        document.getElementById('epPrice').value    = p.price_in_credits;
        document.getElementById('epDesc').value     = p.description || '';
        document.getElementById('epDuration').value = p.duration_days || '';
        document.getElementById('epMaxList').value  = p.max_listings || '';
        document.getElementById('epOrder').value    = p.sort_order;
        document.getElementById('epBump').checked   = p.can_bump == 1;
        document.getElementById('epBumpQ').value    = p.bump_quota;
        document.getElementById('epHL').checked     = p.can_highlight == 1;
        document.getElementById('epHLQ').value      = p.highlight_quota;
        document.getElementById('epActive').checked = p.is_active == 1;
        document.getElementById('editPkgForm').action = '<?= site_url('admin/packages/update/') ?>' + p.id;
    });
});
</script>
