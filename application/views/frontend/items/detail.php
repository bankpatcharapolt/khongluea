<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= site_url('/') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= site_url('items?category_id=' . $item['category_id']) ?>"><?= htmlspecialchars($item['category_name']) ?></a></li>
            <li class="breadcrumb-item active"><?= htmlspecialchars(truncate_text($item['title'], 40)) ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <!-- Images -->
        <div class="col-lg-7">
            <?php $images = $item['images'] ?? []; ?>
            <?php if (!empty($images)): ?>
                <div id="itemCarousel" class="carousel slide rounded overflow-hidden shadow-sm" data-bs-ride="false">
                    <div class="carousel-inner">
                        <?php foreach ($images as $i => $img): ?>
                        <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                            <img src="<?= base_url($img['image_path']) ?>" class="d-block w-100"
                                 style="height:420px;object-fit:cover;" alt="Item image">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($images) > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#itemCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#itemCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    <?php endif; ?>
                </div>
                <?php if (count($images) > 1): ?>
                <div class="d-flex gap-2 mt-2 overflow-auto">
                    <?php foreach ($images as $i => $img): ?>
                    <img src="<?= base_url($img['image_path']) ?>" class="rounded cursor-pointer border <?= $i === 0 ? 'border-primary border-2' : '' ?>"
                         style="width:70px;height:60px;object-fit:cover;cursor:pointer;"
                         onclick="document.querySelector('#itemCarousel').querySelectorAll('.carousel-item')[<?= $i ?>].classList.add('active');
                                  document.querySelectorAll('[data-carousel-thumb]').forEach(e=>e.classList.remove('border-primary','border-2'));">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            <?php else: ?>
                <img src="<?= base_url(IMG_PLACEHOLDER) ?>" class="img-fluid rounded shadow-sm" style="height:420px;width:100%;object-fit:cover;" alt="No image">
            <?php endif; ?>
        </div>

        <!-- Details -->
        <div class="col-lg-5">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h2 class="fw-bold mb-0"><?= htmlspecialchars($item['title']) ?></h2>
                <?php if (is_logged_in()): ?>
                <button class="btn btn-outline-danger btn-sm favorite-btn" data-item-id="<?= $item['id'] ?>"
                        data-favorited="<?= $is_favorited ? '1' : '0' ?>">
                    <i class="bi <?= $is_favorited ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                </button>
                <?php endif; ?>
            </div>

            <!-- Price & Status -->
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="display-6 fw-bold text-primary"><?= format_price((float)$item['price']) ?></span>
                <?= item_status_badge($item['status']) ?>
                <?= item_condition_badge($item['condition']) ?>
            </div>

            <!-- Premium badges -->
            <div class="mb-3">
                <?php if ($item['is_highlighted']): ?>
                    <span class="badge bg-warning text-dark me-1"><i class="bi bi-lightning-charge"></i> Highlighted</span>
                <?php endif; ?>
                <?php if ($item['is_bumped']): ?>
                    <span class="badge bg-info text-dark"><i class="bi bi-arrow-up-circle"></i> Bumped</span>
                <?php endif; ?>
            </div>

            <!-- รายละเอียด -->
            <div class="card border-0 bg-light p-3 mb-3">
                <h6 class="fw-semibold mb-2">รายละเอียด</h6>
                <p class="mb-0 text-muted"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
            </div>

            <!-- Meta -->
            <ul class="list-unstyled small text-muted mb-3">
                <?php if ($item['location_text']): ?>
                <li>
                    <i class="bi bi-geo-alt me-2"></i><?= htmlspecialchars($item['location_text']) ?>
                    <?php if (!empty($item['map_url'])): ?>
                        <a href="<?= htmlspecialchars($item['map_url']) ?>" target="_blank" rel="noopener"
                           class="ms-2 badge text-decoration-none"
                           style="background:#4285f4;color:#fff;font-size:.7rem;padding:3px 8px;border-radius:5px;">
                            <i class="bi bi-map me-1"></i>ดูแผนที่
                        </a>
                    <?php endif; ?>
                </li>
                <?php elseif (!empty($item['map_url'])): ?>
                <li>
                    <i class="bi bi-geo-alt me-2"></i>
                    <a href="<?= htmlspecialchars($item['map_url']) ?>" target="_blank" rel="noopener"
                       class="text-decoration-none fw-600" style="color:#4285f4;">
                        <i class="bi bi-map me-1"></i>ดูตำแหน่งบน Google Maps
                    </a>
                </li>
                <?php endif; ?>
                <li><i class="bi bi-eye me-2"></i><?= number_format($item['view_count']) ?> ครั้งที่ดู</li>
                <li><i class="bi bi-clock me-2"></i>ลงเมื่อ <?= time_ago($item['created_at']) ?></li>
                <li><i class="bi bi-tag me-2"></i><?= htmlspecialchars($item['category_name']) ?></li>
            </ul>

            <!-- Seller Card -->
            <div class="card border-0 bg-light p-3 mb-3">
                <div class="d-flex align-items-center gap-3">
                    <?php if ($item['seller_avatar']): ?>
                        <img src="<?= base_url($item['seller_avatar']) ?>" class="rounded-circle" width="48" height="48" alt="">
                    <?php else: ?>
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width:48px;height:48px;font-size:20px;">
                            <?= strtoupper(substr($item['seller_name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <div class="fw-semibold"><?= htmlspecialchars($item['seller_name']) ?></div>
                        <?php if ($item['seller_city']): ?>
                            <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($item['seller_city']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <?php $current = current_user(); ?>
            <?php if (in_array($item['status'], ['active', 'reserved'])): ?>
                <?php if (is_logged_in() && $current['id'] != $item['user_id']): ?>
                    <?php if ($item['status'] === 'reserved'): ?>
                        <div class="alert mb-2 py-2 text-center fw-600" style="background:#fff3e0;border:1.5px solid #ffb300;color:#b45309;border-radius:9px;">
                            <i class="bi bi-lock-fill me-2"></i>ของชิ้นนี้ถูกจองแล้ว แต่ยังติดต่อได้
                        </div>
                    <?php endif; ?>
                    <form method="post" action="<?= site_url('chat/start') ?>">
                        <?= form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ?>
                        <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
                        <button type="submit" class="btn btn-primary w-100 btn-lg fw-700" style="border-radius:9px;">
                            <i class="bi bi-chat-dots me-2"></i>ติดต่อผู้ขาย
                        </button>
                    </form>
                <?php elseif (!is_logged_in()): ?>
                    <a href="<?= site_url('login') ?>" class="btn btn-primary w-100 btn-lg fw-700" style="border-radius:9px;">
                        <i class="bi bi-chat-dots me-2"></i>เข้าสู่ระบบเพื่อติดต่อผู้ขาย
                    </a>
                <?php else: ?>
                    <div class="d-flex gap-2">
                        <a href="<?= site_url('items/edit/' . $item['id']) ?>" class="btn btn-outline-primary flex-fill" style="border-radius:9px;">
                            <i class="bi bi-pencil me-1"></i>แก้ไข
                        </a>
                        <a href="<?= site_url('items/delete/' . $item['id']) ?>" class="btn btn-outline-danger" style="border-radius:9px;"
                           onclick="return confirm('ลบประกาศนี้ถาวรเลยใช่ไหม?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-secondary">ของชิ้นนี้ไม่ว่างแล้ว.</div>
            <?php endif; ?>

            <!-- Report -->
            <?php if (is_logged_in() && $current['id'] != $item['user_id']): ?>
            <div class="mt-2 text-center">
                <a href="#" class="text-muted small text-decoration-none">
                    <i class="bi bi-flag me-1"></i>รายงานประกาศนี้
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Related Items -->
    <?php if (!empty($related)): ?>
    <div class="mt-5">
        <h5 class="fw-bold mb-3">More in <?= htmlspecialchars($item['category_name']) ?></h5>
        <div class="row row-cols-2 row-cols-md-4 g-3">
            <?php foreach ($related as $rel):
                if ($rel['id'] == $item['id']) continue; ?>
            <div class="col">
                <?= $this->load->view('partials/item_card', ['item' => $rel], TRUE) ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
// Favorite toggle
const favBtn = document.querySelector('.favorite-btn');
if (favBtn) {
    favBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const itemId = this.dataset.itemId;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const csrfName  = document.querySelector('meta[name="csrf-token"]').dataset.name;

        fetch('<?= site_url('favorites/toggle') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-CSRF-Token': csrfToken },
            body: `item_id=${itemId}&${csrfName}=${csrfToken}`
        })
        .then(r => r.json())
        .then(data => {
            const icon = this.querySelector('i');
            if (data.action === 'added') {
                icon.className = 'bi bi-heart-fill';
                this.dataset.favorited = '1';
            } else {
                icon.className = 'bi bi-heart';
                this.dataset.favorited = '0';
            }
        });
    });
}
</script>
