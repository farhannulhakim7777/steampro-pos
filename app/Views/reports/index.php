<section class="panel">
    <div class="panel-head"><h2>Laporan Keuangan</h2><form><select name="period" onchange="this.form.submit()"><option value="daily" <?= $period==='daily'?'selected':'' ?>>Harian</option><option value="weekly" <?= $period==='weekly'?'selected':'' ?>>Mingguan</option><option value="monthly" <?= $period==='monthly'?'selected':'' ?>>Bulanan</option><option value="yearly" <?= $period==='yearly'?'selected':'' ?>>Tahunan</option></select> <a class="ghost" href="<?= e(url('/reports')) ?>?period=<?= e($period) ?>&export=csv">Excel CSV</a> <button class="ghost" type="button" onclick="window.print()">PDF / Cetak</button></form></div>
    <div style="height: 350px; position: relative;">
        <canvas id="revenueChart"></canvas>
    </div>
</section>
<section class="content-grid">
    <div class="panel">
        <h2>Tren Pendapatan</h2>
        <div style="height: 250px; position: relative; margin-bottom: 15px;">
            <canvas id="revenueLineChart"></canvas>
        </div>
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
        <div style="height: 250px; position: relative; margin-bottom: 15px;">
            <canvas id="expenseChart"></canvas>
        </div>
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
    <div style="height: 300px; position: relative; margin-bottom: 15px;">
        <canvas id="paymentChart"></canvas>
    </div>
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

<script>
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueData = <?= json_encode(array_reverse($revenue)) ?>;
const revenueLabels = revenueData.map(d => d.label);
const revenueValues = revenueData.map(d => d.revenue);

new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Pendapatan',
            data: revenueValues,
            backgroundColor: 'rgba(12, 124, 89, 0.8)',
            borderColor: 'rgba(12, 124, 89, 1)',
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.raw.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

// Revenue Line Chart
const revenueLineCtx = document.getElementById('revenueLineChart').getContext('2d');
const revenueLineData = <?= json_encode(array_reverse($revenue)) ?>;
const revenueLineLabels = revenueLineData.map(d => d.label);
const revenueLineValues = revenueLineData.map(d => d.revenue);

new Chart(revenueLineCtx, {
    type: 'line',
    data: {
        labels: revenueLineLabels,
        datasets: [{
            label: 'Pendapatan',
            data: revenueLineValues,
            borderColor: 'rgba(12, 124, 89, 1)',
            backgroundColor: 'rgba(12, 124, 89, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.raw.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

// Expense Chart
const expenseCtx = document.getElementById('expenseChart').getContext('2d');
const expenseData = <?= json_encode(array_reverse($expenses)) ?>;
const expenseLabels = expenseData.map(d => d.label);
const expenseValues = expenseData.map(d => d.expenses);

new Chart(expenseCtx, {
    type: 'bar',
    data: {
        labels: expenseLabels,
        datasets: [{
            label: 'Pengeluaran',
            data: expenseValues,
            backgroundColor: 'rgba(196, 69, 54, 0.8)',
            borderColor: 'rgba(196, 69, 54, 1)',
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' + context.raw.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

// Payment Methods Chart
const paymentCtx = document.getElementById('paymentChart').getContext('2d');
const paymentData = <?= json_encode(array_reverse($paymentStats)) ?>;
const paymentLabels = paymentData.map(d => d.label);

new Chart(paymentCtx, {
    type: 'bar',
    data: {
        labels: paymentLabels,
        datasets: [
            {
                label: 'Tunai',
                data: paymentData.map(d => d.cash_amount),
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1,
                borderRadius: 4
            },
            {
                label: 'QRIS',
                data: paymentData.map(d => d.qris_amount),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 4
            },
            {
                label: 'Transfer',
                data: paymentData.map(d => d.transfer_amount),
                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                borderColor: 'rgba(245, 158, 11, 1)',
                borderWidth: 1,
                borderRadius: 4
            },
            {
                label: 'E-Wallet',
                data: paymentData.map(d => d.ewallet_amount),
                backgroundColor: 'rgba(139, 92, 246, 0.8)',
                borderColor: 'rgba(139, 92, 246, 1)',
                borderWidth: 1,
                borderRadius: 4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            x: {
                stacked: false
            },
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
