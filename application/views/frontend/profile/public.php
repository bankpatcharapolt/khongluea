<div class="container py-4">
    <div class="row g-4">
        <!-- Profile Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm text-center p-4 mb-3">
                <?php if ($profile['avatar']): ?>
                    <img src="<?= base_url($profile['avatar']) ?>" class="rounded-circle mx-auto mb-3"
                         width="90" height="90" alt="" style="object-fit:cover;">
                <?php else: ?>
                    <div class="rounded-circle bg-primary text-white mx-auto mb-3 d-flex align-items-center justify-content-center fw-bold fs-2"
                         style="width:90px;height:90px;">
                        <?= strtoupper(substr($profile['name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <h5 class="fw-bold mb-0"><?= htmlspecialchars($profile['name']) ?></h5>
                <?php if ($profile['city']): ?>
                    <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($profile['city']) ?></div>
                <?php endif; ?>
                <?php if ($profile['premium_status']): ?>
                    <span class="badge bg-warning text-dark mt-2"><i class="bi bi-star-fill me-1"></i>Premium</span>
                <?php endif; ?>
                <div class="text-muted small mt-2">Member since <?= date('M Y', strtotime($profile['created_at'])) ?></div>
            </div>

            <?php if ($is_own_profile): ?>
            <div class="card border-0 shadow-sm">
                <div class="list-group list-group-flush">
                    <a href="<?= site_url('profile/my-listings') ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-grid me-2"></i>My Listings
                    </a>
                    <a href="<?= site_url('account/settings') ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-gear me-2"></i>Settings
                    </a>
                    <a href="<?= site_url('credits') ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-coin me-2"></i>Credits
                    </a>
                    <a href="<?= site_url('premium') ?>" class="list-group-item list-group-item-action">
                        <i class="bi bi-star me-2"></i>Premium
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Listings -->
        <div class="col-lg-9">
            <h5 class="fw-bold mb-3">
                <?= $is_own_profile ? 'My Listings' : htmlspecialchars($profile['name']) . '\'s Listings' ?>
                <span class="badge bg-secondary"><?= count($items) ?></span>
            </h5>

            <?php if (empty($items)): ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-grid display-4"></i>
                    <p class="mt-2">No listings yet.</p>
                    <?php if ($is_own_profile): ?>
                        <a href="<?= site_url('items/create') ?>" class="btn btn-primary btn-sm">Post Your First Item</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-md-3 g-3">
                    <?php foreach ($items as $item): ?>
                    <div class="col">
                        <?= $this->load->view('partials/item_card', ['item' => $item], TRUE) ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
