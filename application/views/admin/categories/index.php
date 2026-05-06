<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Categories</h5>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCatModal">
        <i class="bi bi-plus-lg me-1"></i>Add Category
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>#</th><th>Icon</th><th>Name</th><th>Slug</th><th>Order</th><th>Active</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach ($categories as $cat): ?>
            <tr>
                <td class="text-muted small"><?= $cat['id'] ?></td>
                <td><i class="bi <?= htmlspecialchars($cat['icon'] ?? '') ?> fs-5 text-primary"></i></td>
                <td class="fw-semibold"><?= htmlspecialchars($cat['name']) ?></td>
                <td class="small text-muted"><?= htmlspecialchars($cat['slug']) ?></td>
                <td class="small"><?= $cat['sort_order'] ?></td>
                <td><?= $cat['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Hidden</span>' ?></td>
                <td>
                    <button class="btn btn-sm btn-outline-secondary edit-cat-btn"
                            data-id="<?= $cat['id'] ?>"
                            data-name="<?= htmlspecialchars($cat['name']) ?>"
                            data-slug="<?= htmlspecialchars($cat['slug']) ?>"
                            data-icon="<?= htmlspecialchars($cat['icon'] ?? '') ?>"
                            data-order="<?= $cat['sort_order'] ?>"
                            data-active="<?= $cat['is_active'] ?>"
                            data-bs-toggle="modal" data-bs-target="#editCatModal">
                        Edit
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCatModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" action="<?= site_url('admin/categories/create') ?>">
            <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
            <div class="modal-content">
                <div class="modal-header"><h6 class="modal-title fw-bold">Add Category</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Name</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Slug</label><input type="text" name="slug" class="form-control" required placeholder="e.g. electronics"></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Bootstrap Icon Class</label><input type="text" name="icon" class="form-control" placeholder="bi-cpu"></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Sort Order</label><input type="number" name="sort_order" class="form-control" value="0"></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary btn-sm">Save</button></div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCatModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="editCatForm" method="post">
            <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
            <div class="modal-content">
                <div class="modal-header"><h6 class="modal-title fw-bold">Edit Category</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label fw-semibold">Name</label><input type="text" name="name" id="editName" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Slug</label><input type="text" name="slug" id="editSlug" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Icon Class</label><input type="text" name="icon" id="editIcon" class="form-control"></div>
                    <div class="mb-3"><label class="form-label fw-semibold">Sort Order</label><input type="number" name="sort_order" id="editOrder" class="form-control"></div>
                    <div class="form-check"><input class="form-check-input" type="checkbox" name="is_active" id="editActive" value="1"><label class="form-check-label" for="editActive">Active</label></div>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary btn-sm">Update</button></div>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.edit-cat-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        document.getElementById('editName').value  = this.dataset.name;
        document.getElementById('editSlug').value  = this.dataset.slug;
        document.getElementById('editIcon').value  = this.dataset.icon;
        document.getElementById('editOrder').value = this.dataset.order;
        document.getElementById('editActive').checked = this.dataset.active == '1';
        document.getElementById('editCatForm').action = '<?= site_url('admin/categories/update/') ?>' + id;
    });
});
</script>
