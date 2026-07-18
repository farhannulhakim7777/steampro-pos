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
                        <form method="post" action="<?= e(url('/dashboard/mark-paid')) ?>" style="margin:0;" onsubmit="return confirm('Tandai transaksi ini sebagai lunas?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id" value="<?= e($item['id']) ?>">
                            <input type="hidden" name="amount" value="<?= e($item['remaining_amount']) ?>">
                            <input type="hidden" name="payment_method" value="Cash">
                            <button type="submit" style="padding:8px 16px;border-radius:8px;border:none;background:linear-gradient(135deg,var(--success),#059669);color:#fff;cursor:pointer;font-weight:600;transition:all .2s;">
                                Lunas
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="muted">Tidak ada pembayaran tertunda.</p>
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


