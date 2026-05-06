<?php $user = current_user(); ?>
<div class="container py-4" style="max-width:720px;">
    <h4 class="fw-bold mb-4"><i class="bi bi-coin me-2 text-warning"></i>Credits</h4>

    <!-- Balance Card -->
    <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
        <div class="card-body p-4 d-flex align-items-center justify-content-between">
            <div>
                <div class="small text-white-50 mb-1">Available Balance</div>
                <div class="display-5 fw-bold"><?= number_format($user['credits']) ?></div>
                <div class="text-white-50 small">credits</div>
            </div>
            <i class="bi bi-coin display-2 text-white-50"></i>
        </div>
    </div>

    <!-- How to Get Credits -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold border-0 pt-3">
            <i class="bi bi-info-circle me-2 text-primary"></i>How to Get Credits
        </div>
        <div class="card-body">
            <p class="text-muted mb-3">Credits are used to activate premium packages and boost your listings. Since this platform has no payment gateway, credits are added manually by an admin.</p>
            <ol class="text-muted mb-0">
                <li class="mb-2">Contact the platform admin (email / LINE / WhatsApp).</li>
                <li class="mb-2">Agree on the credit amount and transfer payment off-platform.</li>
                <li>Admin will add credits to your account within 24 hours.</li>
            </ol>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold border-0 pt-3">Transaction History</div>
        <?php if (empty($transactions)): ?>
            <div class="card-body text-center text-muted py-4">No transactions yet.</div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th><th>Type</th><th>Note</th>
                        <th class="text-end">Amount</th><th class="text-end">Balance</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($transactions as $tx): ?>
                <tr>
                    <td class="small text-muted"><?= date('d M Y H:i', strtotime($tx['created_at'])) ?></td>
                    <td>
                        <?php $badges = ['purchase'=>'success','spend'=>'danger','refund'=>'info','bonus'=>'warning','admin_adjustment'=>'secondary'];
                              $color = $badges[$tx['type']] ?? 'secondary'; ?>
                        <span class="badge bg-<?= $color ?>"><?= ucfirst(str_replace('_', ' ', $tx['type'])) ?></span>
                    </td>
                    <td class="small"><?= htmlspecialchars($tx['note'] ?? '—') ?></td>
                    <td class="text-end fw-semibold <?= $tx['amount'] > 0 ? 'text-success' : 'text-danger' ?>">
                        <?= $tx['amount'] > 0 ? '+' : '' ?><?= number_format($tx['amount']) ?>
                    </td>
                    <td class="text-end text-muted small"><?= number_format($tx['balance_after']) ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
