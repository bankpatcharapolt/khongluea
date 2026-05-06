<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="kl-stat-card">
            <div class="stat-icon" style="background:#e7f0fd;color:#1877f2;"><i class="bi bi-people-fill"></i></div>
            <div><div class="stat-label">ผู้ใช้ทั้งหมด</div><div class="stat-value" style="color:#1877f2;"><?= number_format($total_users) ?></div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kl-stat-card">
            <div class="stat-icon" style="background:#e7f0fd;color:#1877f2;"><i class="bi bi-grid-fill"></i></div>
            <div><div class="stat-label">ประกาศที่เปิดอยู่</div><div class="stat-value" style="color:#1877f2;"><?= number_format($active_items) ?></div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kl-stat-card">
            <div class="stat-icon" style="background:#fff4ed;color:#f97316;"><i class="bi bi-collection-fill"></i></div>
            <div><div class="stat-label">ประกาศทั้งหมด</div><div class="stat-value" style="color:#f97316;"><?= number_format($total_items) ?></div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="kl-stat-card">
            <div class="stat-icon" style="background:#fef2f2;color:#ef4444;"><i class="bi bi-flag-fill"></i></div>
            <div><div class="stat-label">รายงานรอตรวจสอบ</div><div class="stat-value" style="color:#ef4444;"><?= number_format($pending_reports) ?></div></div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="kl-table-card">
            <div class="table-header fw-bold">เมนูหลัก</div>
            <div class="list-group list-group-flush">
                <?php
                $links = [
                    ['admin/users',      'bi-people',      '#1877f2', 'จัดการผู้ใช้'],
                    ['admin/items',      'bi-grid',        '#1877f2', 'จัดการประกาศ'],
                    ['admin/reports',    'bi-flag',        '#ef4444', 'ตรวจสอบรายงาน'],
                    ['admin/categories', 'bi-tags',        '#f97316', 'หมวดหมู่'],
                    ['admin/packages',   'bi-star',        '#fbbf24', 'แพ็กเกจพรีเมียม'],
                    ['admin/credits',    'bi-coin',        '#1877f2', 'ประวัติเครดิต'],
                ];
                foreach ($links as [$url, $icon, $color, $label]):
                ?>
                <a href="<?= site_url($url) ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                   style="font-size:.88rem;">
                    <span><i class="bi <?= $icon ?> me-2" style="color:<?= $color ?>;"></i><?= $label ?></span>
                    <?php if ($url === 'admin/reports' && $pending_reports > 0): ?>
                        <span class="badge rounded-pill" style="background:#ef4444;"><?= $pending_reports ?></span>
                    <?php else: ?>
                        <i class="bi bi-chevron-right text-muted" style="font-size:.75rem;"></i>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="kl-table-card">
            <div class="table-header fw-bold">ข้อมูลแพลตฟอร์ม</div>
            <div class="p-3">
                <?php
                $info = [
                    ['จำกัดประกาศฟรี',    FREE_LISTING_LIMIT . ' ประกาศ'],
                    ['รูปภาพสูงสุด/ประกาศ', MAX_ITEM_IMAGES . ' รูป'],
                    ['รายการต่อหน้า',      ITEMS_PER_PAGE . ' รายการ'],
                    ['ชื่อเว็บไซต์',        'ของเหลือ (Khong Luea)'],
                ];
                foreach ($info as [$label, $val]): ?>
                <div class="d-flex justify-content-between py-2 border-bottom" style="font-size:.86rem;">
                    <span class="text-muted"><?= $label ?></span>
                    <strong><?= $val ?></strong>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
