<section class="panel">
    <div class="panel-head"><h2>Laporan Keuangan</h2><form><select name="period" onchange="this.form.submit()"><option value="daily" <?= $period==='daily'?'selected':'' ?>>Harian</option><option value="weekly" <?= $period==='weekly'?'selected':'' ?>>Mingguan</option><option value="monthly" <?= $period==='monthly'?'selected':'' ?>>Bulanan</option><option value="yearly" <?= $period==='yearly'?'selected':'' ?>>Tahunan</option></select> <a class="ghost" href="<?= e(url('/reports')) ?>?period=<?= e($period) ?>&export=csv">Excel CSV</a> <button class="ghost" type="button" onclick="window.print()">PDF / Cetak</button></form></div>
    <div class="chart-bars">
    <?php $max = max(array_column($revenue ?: [['revenue'=>1]], 'revenue')); foreach (array_reverse($revenue) as $r): $h = $max > 0 ? (int) (((float) $r['revenue'] / $max) * 180) : 0; ?>
        <div class="bar" style="height:<?= e((string) max($h, 8)) ?>px"><span><?= money($r['revenue']) ?></span><small><?= e($r['label']) ?></small></div>
    <?php endforeach; ?>
    </div>
</section>
<section class="content-grid">
    <div class="panel"><h2>Tren Pendapatan</h2><?php foreach ($revenue as $r): ?><div class="rank"><span><?= e($r['label']) ?></span><strong><?= money($r['revenue']) ?></strong></div><?php endforeach; ?></div>
    <div class="panel"><h2>Tren Pengeluaran</h2><?php foreach ($expenses as $x): ?><div class="rank"><span><?= e($x['label']) ?></span><strong><?= money($x['expenses']) ?></strong></div><?php endforeach; ?></div>
</section>
