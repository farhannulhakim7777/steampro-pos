<section class="panel">
    <div class="panel-head"><h2>Laporan Keuangan</h2><form><select name="period" onchange="this.form.submit()"><option value="daily" <?= $period==='daily'?'selected':'' ?>>Harian</option><option value="weekly" <?= $period==='weekly'?'selected':'' ?>>Mingguan</option><option value="monthly" <?= $period==='monthly'?'selected':'' ?>>Bulanan</option><option value="yearly" <?= $period==='yearly'?'selected':'' ?>>Tahunan</option></select> <a class="ghost" href="<?= e(url('/reports')) ?>?period=<?= e($period) ?>&export=csv">Excel CSV</a> <button class="ghost" type="button" onclick="window.print()">PDF / Cetak</button></form></div>
    <div class="chart-bars">
    <?php $max = max(array_column($revenue ?: [['revenue'=>1]], 'revenue')); foreach (array_reverse($revenue) as $r): $h = $max > 0 ? (int) (((float) $r['revenue'] / $max) * 180) : 0; ?>
        <div class="bar" style="height:<?= e((string) max($h, 8)) ?>px"><span><?= money($r['revenue']) ?></span><small><?= e($r['label']) ?></small></div>
    <?php endforeach; ?>
    </div>
</section>
<section class="content-grid">
    <div class="panel">
        <h2>Tren Pendapatan</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th style="text-align: right;">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($revenue as $r): ?>
                        <tr>
                            <td><strong><?= e($r['label']) ?></strong></td>
                            <td style="text-align: right; font-weight: 600; color: var(--ok);"><?= money($r['revenue']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel">
        <h2>Tren Pengeluaran</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th style="text-align: right;">Total Pengeluaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($expenses)): ?>
                        <tr><td colspan="2" class="muted" style="text-align: center;">Belum ada data pengeluaran.</td></tr>
                    <?php else: ?>
                        <?php foreach ($expenses as $x): ?>
                            <tr>
                                <td><strong><?= e($x['label']) ?></strong></td>
                                <td style="text-align: right; font-weight: 600; color: var(--danger);"><?= money($x['expenses']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="panel" style="margin-top: 18px;">
    <h2>Kinerja Karyawan (Pencuci)</h2>
    <p class="muted" style="font-size: 13px; margin-top: -10px; margin-bottom: 15px;">
        Jumlah kendaraan yang dicuci dan omset layanan yang diselesaikan oleh masing-masing karyawan pada periode ini.
    </p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Karyawan</th>
                    <th>Posisi</th>
                    <th style="text-align: center;">Jumlah Cuci</th>
                    <th style="text-align: right;">Omset Layanan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($employeeStats)): ?>
                    <tr><td colspan="4" class="muted" style="text-align:center;">Tidak ada data untuk periode ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($employeeStats as $emp): ?>
                        <tr>
                            <td><strong><?= e($emp['employee_name']) ?></strong></td>
                            <td><span class="badge" style="background:var(--bg);color:var(--ink);"><?= e($emp['position'] ?: 'Washer') ?></span></td>
                            <td style="text-align: center; font-weight: 700; color: var(--brand);"><?= e($emp['total_washes']) ?>x</td>
                            <td style="text-align: right; font-weight: 600;"><?= money($emp['total_services_revenue']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="panel" style="margin-top: 18px;">
    <h2>Rekonsiliasi Metode Pembayaran</h2>
    <p class="muted" style="font-size: 13px; margin-top: -10px; margin-bottom: 15px;">
        Rincian pendapatan berdasarkan metode transaksi yang tercatat di sistem (Apple-to-Apple dengan Tren Pendapatan per Hari/Periode).
    </p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Periode</th>
                    <th style="text-align: right;">Tunai (Cash)</th>
                    <th style="text-align: right;">QRIS</th>
                    <th style="text-align: right;">Transfer</th>
                    <th style="text-align: right;">E-Wallet</th>
                    <th style="text-align: center;">Total Transaksi</th>
                    <th style="text-align: right;">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($paymentStats)): ?>
                    <tr><td colspan="7" class="muted" style="text-align:center;">Tidak ada data untuk periode ini.</td></tr>
                <?php else: ?>
                    <?php foreach ($paymentStats as $pay): ?>
                        <tr>
                            <td><strong><?= e($pay['label']) ?></strong></td>
                            <td style="text-align: right; color: var(--muted);"><?= money($pay['cash_amount']) ?></td>
                            <td style="text-align: right; color: var(--muted);"><?= money($pay['qris_amount']) ?></td>
                            <td style="text-align: right; color: var(--muted);"><?= money($pay['transfer_amount']) ?></td>
                            <td style="text-align: right; color: var(--muted);"><?= money($pay['ewallet_amount']) ?></td>
                            <td style="text-align: center; font-weight: 600;"><?= e($pay['total_transactions']) ?> tx</td>
                            <td style="text-align: right; font-weight: 700; color: var(--ok);"><?= money($pay['total_revenue']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
