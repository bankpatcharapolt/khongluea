<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Items <span class="badge bg-secondary"><?= number_format($total) ?></span></h5>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 d-flex gap-2 flex-wrap">
        <form class="d-flex gap-2 flex-grow-1" method="get">
            <input type="search" name="q" class="form-control form-control-sm" placeholder="Search title…"
                   value="<?= htmlspecialchars($this->input->get('q', TRUE) ?? '') ?>" style="max-width:280px;">
            <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <?php foreach (['active','reserved','sold','deleted'] as $s): ?>
                    <option value="<?= $s ?>" <?= $this->input->get('status') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-primary btn-sm">Search</button>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>Image</th><th>Title</th><th>Seller</th>
                    <th>Category</th><th>Price</th><th>Status</th>
                    <th>Featured</th><th>Views</th><th>Date</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td class="text-muted small"><?= $item['id'] ?></td>
                <td>
                    <?php if (!empty($item['primary_image'])): ?>
                        <img src="<?= base_url($item['primary_image']) ?>" class="rounded" width="48" height="40" style="object-fit:cover;" alt="">
                    <?php else: ?>
                        <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width:48px;height:40px;">
                            <i class="bi bi-image small"></i>
                        </div>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?= site_url('items/' . $item['id']) ?>" target="_blank" class="text-decoration-none fw-semibold text-dark small">
                        <?= htmlspecialchars(truncate_text($item['title'], 40)) ?>
                    </a>
                </td>
                <td class="small text-muted"><?= htmlspecialchars($item['seller_name']) ?></td>
                <td class="small"><?= htmlspecialchars($item['category_name']) ?></td>
                <td class="small fw-semibold"><?= format_price((float)$item['price']) ?></td>
                <td><?= item_status_badge($item['status']) ?></td>
                <td>
                    <form method="post" action="<?= site_url('admin/items/toggle_feature/' . $item['id']) ?>" class="d-inline">
                        <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
                        <button type="submit" class="btn btn-sm <?= $item['is_featured'] ? 'btn-warning' : 'btn-outline-secondary' ?>" title="Toggle Featured">
                            <i class="bi bi-star<?= $item['is_featured'] ? '-fill' : '' ?>"></i>
                        </button>
                    </form>
                </td>
                <td class="small text-muted"><?= number_format($item['view_count']) ?></td>
                <td class="small text-muted"><?= date('d M Y', strtotime($item['created_at'])) ?></td>
                <td>
                    <form method="post" action="<?= site_url('admin/items/delete/' . $item['id']) ?>"
                          onsubmit="return confirm('Delete this item?')" class="d-inline">
                        <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($items)): ?>
                <tr><td colspan="11" class="text-center text-muted py-4">No items found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (!empty($pagination)): ?>
    <div class="card-footer bg-white border-0 d-flex justify-content-center">
        <?= $pagination ?>
    </div>
    <?php endif; ?>
</div>
