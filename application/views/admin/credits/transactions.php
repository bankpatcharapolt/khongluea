<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Credit Transactions</h5>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th><th>User</th><th>Type</th><th>Note</th>
                    <th class="text-end">Amount</th><th class="text-end">Balance After</th><th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($transactions as $tx): ?>
            <tr>
                <td class="text-muted small"><?= $tx['id'] ?></td>
                <td>
                    <div class="fw-semibold small"><?= htmlspecialchars($tx['user_name']) ?></div>
                    <div class="text-muted" style="font-size:11px;"><?= htmlspecialchars($tx['user_email']) ?></div>
                </td>
                <td>
                    <?php $badges = ['purchase'=>'success','spend'=>'danger','refund'=>'info','bonus'=>'warning','admin_adjustment'=>'secondary'];
                          $color = $badges[$tx['type']] ?? 'secondary'; ?>
                    <span class="badge bg-<?= $color ?>"><?= ucfirst(str_replace('_', ' ', $tx['type'])) ?></span>
                </td>
                <td class="small text-muted"><?= htmlspecialchars($tx['note'] ?? '—') ?></td>
                <td class="text-end fw-semibold <?= $tx['amount'] > 0 ? 'text-success' : 'text-danger' ?>">
                    <?= $tx['amount'] > 0 ? '+' : '' ?><?= number_format($tx['amount']) ?>
                </td>
                <td class="text-end text-muted small"><?= number_format($tx['balance_after']) ?></td>
                <td class="text-muted small"><?= date('d M Y H:i', strtotime($tx['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($transactions)): ?>
                <tr><td colspan="7" class="text-center text-muted py-4">No transactions yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
