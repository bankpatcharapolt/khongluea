<nav id="admin-sidebar" class="bg-dark text-white d-flex flex-column" style="min-width:230px;min-height:100vh;">
    <div class="p-3 border-bottom border-secondary">
        <a href="<?= site_url('/') ?>" class="text-white text-decoration-none fw-bold fs-5">
            <i class="bi bi-shop me-2"></i>Marketplace
        </a>
        <div class="text-muted small mt-1">Admin Panel</div>
    </div>

    <ul class="nav flex-column p-2 flex-grow-1">
        <li class="nav-item">
            <a class="nav-link text-white-50 d-flex align-items-center gap-2 rounded <?= (uri_string() === 'admin' || uri_string() === 'admin/dashboard') ? 'active bg-primary text-white' : '' ?>"
               href="<?= site_url('admin') ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item mt-1">
            <a class="nav-link text-white-50 d-flex align-items-center gap-2 rounded <?= strpos(uri_string(), 'admin/users') !== FALSE ? 'active bg-primary text-white' : '' ?>"
               href="<?= site_url('admin/users') ?>">
                <i class="bi bi-people"></i> Users
            </a>
        </li>
        <li class="nav-item mt-1">
            <a class="nav-link text-white-50 d-flex align-items-center gap-2 rounded <?= strpos(uri_string(), 'admin/items') !== FALSE ? 'active bg-primary text-white' : '' ?>"
               href="<?= site_url('admin/items') ?>">
                <i class="bi bi-grid"></i> Items
            </a>
        </li>
        <li class="nav-item mt-1">
            <a class="nav-link text-white-50 d-flex align-items-center gap-2 rounded <?= strpos(uri_string(), 'admin/categories') !== FALSE ? 'active bg-primary text-white' : '' ?>"
               href="<?= site_url('admin/categories') ?>">
                <i class="bi bi-tags"></i> Categories
            </a>
        </li>
        <li class="nav-item mt-1">
            <a class="nav-link text-white-50 d-flex align-items-center gap-2 rounded <?= strpos(uri_string(), 'admin/reports') !== FALSE ? 'active bg-primary text-white' : '' ?>"
               href="<?= site_url('admin/reports') ?>">
                <i class="bi bi-flag"></i> Reports
            </a>
        </li>
        <li class="nav-item mt-1">
            <a class="nav-link text-white-50 d-flex align-items-center gap-2 rounded <?= strpos(uri_string(), 'admin/packages') !== FALSE ? 'active bg-primary text-white' : '' ?>"
               href="<?= site_url('admin/packages') ?>">
                <i class="bi bi-star"></i> Packages
            </a>
        </li>
        <li class="nav-item mt-1">
            <a class="nav-link text-white-50 d-flex align-items-center gap-2 rounded <?= strpos(uri_string(), 'admin/credits') !== FALSE ? 'active bg-primary text-white' : '' ?>"
               href="<?= site_url('admin/credits') ?>">
                <i class="bi bi-coin"></i> Credits Log
            </a>
        </li>
    </ul>

    <div class="p-3 border-top border-secondary">
        <a href="<?= site_url('logout') ?>" class="btn btn-sm btn-outline-danger w-100">
            <i class="bi bi-box-arrow-right me-1"></i>Logout
        </a>
    </div>
</nav>
