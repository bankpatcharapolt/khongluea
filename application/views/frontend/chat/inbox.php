<div class="container py-4" style="max-width:700px;">
    <h4 class="fw-bold mb-4"><i class="bi bi-chat-dots text-primary me-2"></i>กล่องข้อความ</h4>

    <?php if (empty($conversations)): ?>
        <div class="text-center py-5">
            <i class="bi bi-chat-square-dots display-3 text-muted"></i>
            <h5 class="mt-3 text-muted">ยังไม่มีการสนทนา</h5>
            <p class="text-muted">เมื่อคุณติดต่อผู้ขาย การสนทนาจะปรากฏที่นี่</p>
            <a href="<?= site_url('items') ?>" class="btn btn-primary">เลือกชมของ</a>
        </div>
    <?php else: ?>
        <div class="kl-card overflow-hidden">
            <?php foreach ($conversations as $i => $conv):
                $me = current_user();
                $other_name  = ($conv['buyer_id'] == $me['id']) ? $conv['seller_name']  : $conv['buyer_name'];
                $other_avatar= ($conv['buyer_id'] == $me['id']) ? $conv['seller_avatar']: $conv['buyer_avatar'];
                $has_unread  = $conv['unread_count'] > 0;
            ?>
            <a href="<?= site_url('chat/' . $conv['id']) ?>"
               class="d-flex gap-3 p-3 text-decoration-none <?= $has_unread ? 'bg-blue-light' : '' ?> <?= $i > 0 ? 'border-top' : '' ?>"
               style="color:var(--kl-text);transition:background .15s;"
               onmouseover="this.style.background='var(--kl-blue-light)'"
               onmouseout="this.style.background='<?= $has_unread ? 'var(--kl-blue-light)' : 'transparent' ?>'">

                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <?php if ($other_avatar): ?>
                        <img src="<?= base_url($other_avatar) ?>" class="rounded-circle"
                             width="48" height="48" style="object-fit:cover;" alt="">
                    <?php else: ?>
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                             style="width:48px;height:48px;background:var(--kl-blue);font-size:18px;">
                            <?= strtoupper(substr($other_name, 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="fw-semibold <?= $has_unread ? '' : 'text-muted' ?>">
                            <?= htmlspecialchars($other_name) ?>
                        </span>
                        <?php if ($has_unread): ?>
                            <span class="badge rounded-pill ms-2"
                                  style="background:var(--kl-blue);font-size:.72rem;">
                                <?= $conv['unread_count'] ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="small text-muted text-truncate">
                        <span class="badge me-1"
                              style="background:var(--kl-blue-light);color:var(--kl-blue);border:1px solid var(--kl-blue-mid);font-size:.7rem;">
                            <?= htmlspecialchars(truncate_text($conv['item_title'] ?? '', 20)) ?>
                        </span>
                        <?= htmlspecialchars(truncate_text($conv['last_message'] ?? '…', 50)) ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
