<form method="post" action="<?= e(url('/cashier/checkout')) ?>" class="cashier-grid" data-pos-form>
    <?= csrf_field() ?>
    <section class="panel">
        <h2>Pelanggan</h2>
        <label>Cari Pelanggan
            <div style="display:flex;gap:8px;">
                <input type="text" id="customer-search" list="customer-list" placeholder="Cari nama atau plat nomor..." autocomplete="off" style="flex:1;">
                <button type="button" onclick="clearCustomer()" style="padding:8px 12px;border-radius:8px;border:none;background:var(--line);cursor:pointer;font-weight:600;">Clear</button>
            </div>
            <input type="hidden" name="customer_id" id="customer-id">
            <datalist id="customer-list">
                <option value="">Pelanggan Baru</option>
                <?php foreach ($customers as $c): ?>
                <option value="<?= e($c['plate_number'] . ' - ' . $c['name']) ?>" data-id="<?= e($c['id']) ?>" data-name="<?= e($c['name']) ?>" data-phone="<?= e($c['phone']) ?>" data-plate="<?= e($c['plate_number']) ?>" data-brand="<?= e($c['motorcycle_brand']) ?>" data-type="<?= e($c['motorcycle_type']) ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </label>
        <div class="grid two">
            <label>Nama<input name="customer_name" required></label>
            <label>Plat Nomor<input name="plate_number" data-history-plate required style="text-transform:uppercase"></label>
            <label>No. Telepon<input name="phone"></label>
            <label>Merk Motor<input name="motorcycle_brand"></label>
            <label>Tipe Motor<input name="motorcycle_type"></label>
            <label>Petugas Cuci<select name="employee_id" required><?php foreach ($employees as $e): ?><option value="<?= e($e['id']) ?>"><?= e($e['name']) ?></option><?php endforeach; ?></select></label>
        </div>
        <label class="check"><input type="checkbox" name="priority" value="1"> Antrian Prioritas</label>
        <div id="plate-history" class="inline-history"></div>
    </section>
    <section class="panel">
        <h2>Layanan</h2>
        <div class="pick-list">
        <?php foreach ($services as $s): ?>
            <label class="pick"><input type="checkbox" name="services[]" value="<?= e($s['id']) ?>" data-price="<?= e($s['price']) ?>"><span><?= e($s['name']) ?><small><?= e($s['category_name']) ?> - <?= e($s['estimated_duration']) ?> menit</small></span><strong><?= money($s['price']) ?></strong></label>
        <?php endforeach; ?>
        </div>
    </section>
    <aside class="panel checkout">
        <h2>Pembayaran</h2>
        <label>Metode Pembayaran<select name="payment_method"><option>Tunai</option><option>QRIS</option><option>Transfer</option><option>E-Wallet</option></select></label>
        <label>Catatan<textarea name="notes"></textarea></label>
        <div class="total-line"><span>Subtotal</span><strong data-subtotal>Rp 0</strong></div>
        <div class="total-line grand"><span>Total</span><strong data-total>Rp 0</strong></div>
        <input type="hidden" name="action" id="action-field">
        <div class="payment-actions">
            <button class="btn-success" type="submit" onclick="document.getElementById('action-field').value='paid'">
                <span>Lunas</span>
                <small>Langsung ke struk</small>
            </button>
            <button class="btn-warning" type="submit" onclick="document.getElementById('action-field').value='unpaid'">
                <span>Belum Lunas</span>
                <small>Ke dashboard</small>
            </button>
        </div>
    </aside>
</form>
<script>
const customerSearch = document.getElementById('customer-search');
const customerId = document.getElementById('customer-id');
const customerList = document.getElementById('customer-list');

function clearCustomer() {
    customerSearch.value = '';
    customerId.value = '';
    document.querySelector('[name="customer_name"]').value = '';
    document.querySelector('[name="plate_number"]').value = '';
    document.querySelector('[name="phone"]').value = '';
    document.querySelector('[name="motorcycle_brand"]').value = '';
    document.querySelector('[name="motorcycle_type"]').value = '';
}

customerSearch.addEventListener('input', () => {
    const selectedValue = customerSearch.value;
    const selectedOption = Array.from(customerList.options).find(opt => opt.value === selectedValue);
    
    if (selectedOption && selectedOption.dataset.id) {
        customerId.value = selectedOption.dataset.id;
        document.querySelector('[name="customer_name"]').value = selectedOption.dataset.name || '';
        document.querySelector('[name="plate_number"]').value = selectedOption.dataset.plate || '';
        document.querySelector('[name="phone"]').value = selectedOption.dataset.phone || '';
        document.querySelector('[name="motorcycle_brand"]').value = selectedOption.dataset.brand || '';
        document.querySelector('[name="motorcycle_type"]').value = selectedOption.dataset.type || '';
    } else {
        customerId.value = '';
        document.querySelector('[name="customer_name"]').value = '';
        document.querySelector('[name="plate_number"]').value = '';
        document.querySelector('[name="phone"]').value = '';
        document.querySelector('[name="motorcycle_brand"]').value = '';
        document.querySelector('[name="motorcycle_type"]').value = '';
    }
});
</script>

