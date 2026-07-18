<section class="stats-grid">
    <article class="stat"><span>Pendapatan Hari Ini</span><strong><?= money($stats['today_revenue']) ?></strong></article>
    <article class="stat"><span>Transaksi</span><strong><?= e($stats['today_transactions']) ?></strong></article>
    <article class="stat amber"><span>Antrian Aktif</span><strong><?= e($stats['active_queue']) ?></strong></article>
    <article class="stat green"><span>Selesai</span><strong><?= e($stats['completed_services']) ?></strong></article>
    <article class="stat"><span>Pendapatan Bulanan</span><strong><?= money($stats['monthly_revenue']) ?></strong></article>
    <article class="stat dark"><span>Laba Bulanan</span><strong><?= money($stats['monthly_profit']) ?></strong></article>
</section>
<section class="content-grid">
    <div class="panel">
        <h2>Pendapatan 7 Hari Terakhir</h2>
        <div class="chart-bars">
            <?php
            try {
                if (empty($dailyRevenue)) {
                    echo '<p class="muted">Belum ada data transaksi.</p>';
                } else {
                    $revenueMap = [];
                    foreach ($dailyRevenue as $row) {
                        $revenueMap[$row['date']] = $row['revenue'];
                    }
                    $endDate = date('Y-m-d');
                    $startDate = date('Y-m-d', strtotime($endDate . ' -6 days'));
                    $last7Days = [];
                    for ($i = 0; $i <= 6; $i++) {
                        $date = date('Y-m-d', strtotime($startDate . " +$i days"));
                        $last7Days[$date] = $revenueMap[$date] ?? 0;
                    }
                    $maxRevenue = max($last7Days) ?: 1;
                    foreach ($last7Days as $date => $revenue):
                        $height = $maxRevenue > 0 ? (int) ((float) $revenue / $maxRevenue * 180) : 0;
                    ?>
                        <div class="bar-wrapper">
                            <div class="bar" style="height:<?= max($height, 8) ?>px">
                                <span><?= money($revenue) ?></span>
                            </div>
                            <small class="bar-label"><?= date('d/m', strtotime($date)) ?></small>
                        </div>
                    <?php endforeach; 
                }
            } catch (Exception $e) {
                echo '<p class="muted">Terjadi kesalahan dalam memuat grafik.</p>';
            }
            ?>
        </div>
    </div>
    <div class="panel">
        <h2>Pembayaran Tertunda</h2>
        <?php if ($pending): ?>
            <?php foreach ($pending as $item): ?>
                <div class="pending-item">
                    <div>
                        <strong><?= e($item['transaction_no']) ?></strong>
                        <small><?= e($item['customer_name']) ?></small>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <span class="badge danger">Sisa: <?= money($item['remaining_amount']) ?></span>
                        <form
                            method="post"
                            action="<?= e(url('/dashboard/mark-paid')) ?>"
                            class="lunas-form"
                            style="margin:0;"
                            data-trx="<?= e($item['transaction_no']) ?>"
                            data-name="<?= e($item['customer_name']) ?>"
                            data-amount="<?= e(money($item['remaining_amount'])) ?>"
                        >
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                            <input type="hidden" name="amount" value="<?= e($item['remaining_amount']) ?>">
                            <input type="hidden" name="payment_method" value="Cash">
                            <button type="button"
                                onclick="confirmLunas(this.closest('form'))"
                                style="padding:8px 16px;border-radius:8px;border:none;background:linear-gradient(135deg,#10b981,#059669);color:#fff;cursor:pointer;font-weight:600;transition:all .2s;display:flex;align-items:center;gap:6px;">
                                ✅ Lunas
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="muted">Tidak ada pembayaran tertunda. 🎉</p>
        <?php endif; ?>
    </div>
    <div class="panel">
        <h2>Transaksi Terbaru</h2>
        <div class="table-wrap"><table><thead><tr><th>No</th><th>Pelanggan</th><th>Total</th><th>Status</th></tr></thead><tbody>
        <?php foreach ($recent as $row): ?><tr><td><?= e($row['transaction_no']) ?></td><td><?= e($row['customer_name']) ?></td><td><?= money($row['total_amount']) ?></td><td><span class="badge"><?= e($row['payment_status']) ?></span></td></tr><?php endforeach; ?>
        </tbody></table></div>
    </div>
    <div class="panel">
        <h2>Layanan Terpopuler</h2>
        <?php foreach ($topServices as $item): ?><div class="rank"><span><?= e($item['name']) ?></span><strong><?= e($item['total']) ?>x</strong></div><?php endforeach; ?>
    </div>
    <div class="panel">
        <h2>Pelanggan Terbaik</h2>
        <?php foreach ($topCustomers as $item): ?><div class="rank"><span><?= e($item['name']) ?><small><?= e($item['visits']) ?> kunjungan</small></span><strong><?= money($item['spent']) ?></strong></div><?php endforeach; ?>
    </div>
</section>

<!-- Custom Modal Lunas -->
<div id="lunas-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);z-index:2000;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:20px;padding:36px 32px 28px;max-width:420px;width:92%;box-shadow:0 25px 60px rgba(0,0,0,.25);animation:modalSlideIn .3s ease-out;position:relative;">
        <div style="text-align:center;margin-bottom:20px;">
            <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#d1fae5,#a7f3d0);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;font-size:28px;">✅</div>
            <h3 style="margin:0 0 6px;font-size:20px;color:#0f172a;">Konfirmasi Pelunasan</h3>
            <p style="margin:0;color:#64748b;font-size:14px;">Pastikan data transaksi sudah benar sebelum melanjutkan.</p>
        </div>
        <div style="background:#f8fafc;border-radius:12px;padding:16px 18px;margin-bottom:22px;border:1px solid #e2e8f0;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <span style="font-size:13px;color:#64748b;">No. Transaksi</span>
                <strong id="lunas-trx" style="font-size:13px;color:#0f172a;"></strong>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <span style="font-size:13px;color:#64748b;">Pelanggan</span>
                <strong id="lunas-name" style="font-size:13px;color:#0f172a;"></strong>
            </div>
            <div style="height:1px;background:#e2e8f0;margin:10px 0;"></div>
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:14px;color:#64748b;font-weight:600;">Jumlah Dibayar</span>
                <strong id="lunas-amount" style="font-size:18px;color:#059669;"></strong>
            </div>
        </div>
        <div style="display:flex;gap:10px;">
            <button onclick="closeLunasModal()" style="flex:1;padding:13px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;color:#475569;font-weight:600;cursor:pointer;font-size:14px;transition:all .2s;">
                Batal
            </button>
            <button id="lunas-confirm-btn" style="flex:2;padding:13px;border-radius:10px;border:none;background:linear-gradient(135deg,#10b981,#059669);color:#fff;font-weight:700;cursor:pointer;font-size:14px;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px;">
                ✅ Ya, Tandai Lunas
            </button>
        </div>
    </div>
</div>

<script>
let _lunasForm = null;

function confirmLunas(form) {
    _lunasForm = form;
    document.getElementById('lunas-trx').textContent    = form.dataset.trx;
    document.getElementById('lunas-name').textContent   = form.dataset.name;
    document.getElementById('lunas-amount').textContent = form.dataset.amount;
    const modal = document.getElementById('lunas-modal');
    modal.style.display = 'flex';
}

function closeLunasModal() {
    document.getElementById('lunas-modal').style.display = 'none';
    _lunasForm = null;
}

document.getElementById('lunas-confirm-btn').addEventListener('click', () => {
    if (_lunasForm) {
        const btn = document.getElementById('lunas-confirm-btn');
        btn.disabled = true;
        btn.innerHTML = '⏳ Memproses...';
        _lunasForm.submit();
    }
});

// Tutup modal kalau klik di luar
document.getElementById('lunas-modal').addEventListener('click', function(e) {
    if (e.target === this) closeLunasModal();
});
</script>

