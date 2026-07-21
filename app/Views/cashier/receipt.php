<section class="receipt">
    <div class="receipt-card" id="printable-receipt">
        <h2><?= e($settings['business_name'] ?? 'SteamPro POS') ?></h2>
        <p><?= e($settings['business_address'] ?? 'Motorcycle Wash & Detailing') ?><br><?= e($settings['business_phone'] ?? '') ?></p>
        <hr>
        <div class="receipt-row"><span>No</span><strong><?= e($transaction['transaction_no']) ?></strong></div>
        <div class="receipt-row"><span>Tanggal</span><strong><?= e($transaction['transaction_date']) ?></strong></div>
        <div class="receipt-row"><span>Kasir</span><strong><?= e($transaction['cashier_name']) ?></strong></div>
        <div class="receipt-row"><span>Pelanggan</span><strong><?= e($transaction['customer_name']) ?></strong></div>
        <div class="receipt-row"><span>Plat</span><strong><?= e($transaction['plate_number']) ?></strong></div>
        <hr>
        <?php foreach ($details as $d): ?><div class="receipt-row"><span><?= e($d['quantity']) ?>x <?= e($d['item_name']) ?></span><strong><?= money($d['total_price']) ?></strong></div><?php endforeach; ?>
        <hr>
        <div class="receipt-row"><span>Subtotal</span><strong><?= money($transaction['subtotal']) ?></strong></div>
        <div class="receipt-row"><span>Diskon</span><strong><?= money($transaction['discount']) ?></strong></div>
        <div class="receipt-row grand"><span>Total</span><strong><?= money($transaction['total_amount']) ?></strong></div>
        <div class="receipt-row"><span>Dibayar</span><strong><?= money($transaction['paid_amount']) ?></strong></div>
        <div class="receipt-row"><span>Sisa</span><strong><?= money($transaction['remaining_amount']) ?></strong></div>
        <div class="receipt-row"><span>Metode</span><strong><?= e($transaction['payment_method']) ?></strong></div>
        <p class="center"><?= e($settings['receipt_footer'] ?? 'Thank you. Ride clean, ride safe.') ?></p>
    </div>
    <div class="receipt-actions">
        <button class="btn-success" onclick="window.print()">
            <span>Cetak Struk</span>
            <small>Print receipt</small>
        </button>
        <a href="<?= e(url('/cashier')) ?>" class="btn-warning" style="display:flex;flex-direction:column;align-items:center;gap:4px;padding:16px;border-radius:12px;text-decoration:none;color:#fff;text-align:center;font-weight:600">
            <span>Kembali ke Kasir</span>
            <small>Transaksi baru</small>
        </a>
    </div>
</section>

<style>
@media print {
    .receipt-actions {
        display: none !important;
    }
    body {
        background: white !important;
    }
    .receipt {
        display: flex;
        justify-content: center;
        padding: 0;
    }
    .receipt-card {
        box-shadow: none;
        border: none;
        width: 100%;
        max-width: none;
    }
}
</style>

