<div class="container py-4" style="max-width:760px;">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil me-2"></i>Edit Item</h4>

    <?php echo validation_errors('<div class="alert alert-danger py-2 small">', '</div>'); ?>

    <form method="post" action="<?= site_url('items/edit/' . $item['id']) ?>" enctype="multipart/form-data">
        <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pt-3">Item Details</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" maxlength="200"
                           value="<?= set_value('title', $item['title']) ?>" required>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category_id" class="form-select" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"
                                    <?= set_select('category_id', $cat['id'], $item['category_id'] == $cat['id']) ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Condition</label>
                        <select name="condition" class="form-select" required>
                            <?php foreach (['new'=>'New','like_new'=>'Like New','good'=>'Good','fair'=>'Fair','poor'=>'Poor'] as $v=>$l): ?>
                                <option value="<?= $v ?>"
                                    <?= set_select('condition', $v, $item['condition'] === $v) ?>><?= $l ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" rows="5" required><?= set_value('description', htmlspecialchars($item['description'])) ?></textarea>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pt-3">Pricing & Status</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Price (THB)</label>
                        <div class="input-group">
                            <span class="input-group-text">฿</span>
                            <input type="number" name="price" class="form-control"
                                   min="0" step="1" value="<?= set_value('price', $item['price']) ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <?php foreach (['active'=>'Active','reserved'=>'Reserved','sold'=>'Sold'] as $v=>$l): ?>
                                <option value="<?= $v ?>"
                                    <?= set_select('status', $v, $item['status'] === $v) ?>><?= $l ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pt-3">Location</div>
            <div class="card-body">
                <input type="text" name="location_text" class="form-control"
                       value="<?= set_value('location_text', $item['location_text']) ?>"
                       placeholder="e.g. Sukhumvit, Bangkok">
            </div>
        </div>

        <!-- Existing Images -->
        <?php if (!empty($item['images'])): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pt-3">Current Photos</div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    <?php foreach ($item['images'] as $img): ?>
                    <div class="position-relative">
                        <img src="<?= base_url($img['image_path']) ?>" class="rounded border"
                             style="width:90px;height:80px;object-fit:cover;" alt="">
                        <?php if ($img['is_primary']): ?>
                            <span class="badge bg-primary position-absolute top-0 start-0" style="font-size:10px;">Cover</span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pt-3">Add More Photos</div>
            <div class="card-body">
                <input type="file" name="images[]" class="form-control" multiple
                       accept="image/jpeg,image/png,image/webp" data-preview="#imgPreview">
                <div id="imgPreview" class="mt-3 d-flex flex-wrap gap-2"></div>
            </div>
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary px-5 fw-semibold">
                <i class="bi bi-check-lg me-2"></i>Save Changes
            </button>
            <a href="<?= site_url('items/' . $item['id']) ?>" class="btn btn-outline-secondary">Cancel</a>
            <a href="<?= site_url('items/delete/' . $item['id']) ?>" class="btn btn-outline-danger ms-auto"
               onclick="return confirm('Delete this item permanently?')">
                <i class="bi bi-trash me-1"></i>Delete
            </a>
        </div>
    </form>
</div>
