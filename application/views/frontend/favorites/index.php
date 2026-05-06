<div class="container py-4">
    <h4 class="fw-bold mb-4"><i class="bi bi-heart me-2"></i>My Favourites</h4>

    <?php if (empty($items)): ?>
        <div class="text-center py-5">
            <i class="bi bi-heart display-1 text-muted"></i>
            <h5 class="mt-3 text-muted">No saved items yet</h5>
            <p class="text-muted">Tap the heart on any listing to save it here.</p>
            <a href="<?= site_url('items') ?>" class="btn btn-primary">Browse Items</a>
        </div>
    <?php else: ?>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
            <?php foreach ($items as $item): ?>
            <div class="col">
                <?= $this->load->view('partials/item_card', ['item' => $item], TRUE) ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
