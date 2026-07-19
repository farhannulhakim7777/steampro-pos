<form method="post" action="<?= e(url('/cashier/checkout')) ?>" class="cashier-form">
    <?= csrf_field() ?>
    
    <div class="form-container">
        <!-- Customer Section -->
        <section class="panel customer-section">
            <div class="section-header">
                <h2>👤 Informasi Pelanggan</h2>
                <button type="button" onclick="clearCustomer()" class="btn-clear">
                    <span>Clear</span>
                </button>
            </div>
            
            <div class="customer-search-wrapper">
                <input type="text" id="customer-search" list="customer-list" placeholder="🔍 Cari nama atau plat nomor..." autocomplete="off">
                <input type="hidden" name="customer_id" id="customer-id">
                <datalist id="customer-list">
                    <option value="">Pelanggan Baru</option>
                    <?php foreach ($customers as $c): ?>
                    <option value="<?= e($c['plate_number'] . ' - ' . $c['name']) ?>" data-id="<?= e($c['id']) ?>" data-name="<?= e($c['name']) ?>" data-phone="<?= e($c['phone']) ?>" data-plate="<?= e($c['plate_number']) ?>" data-brand="<?= e($c['motorcycle_brand']) ?>" data-type="<?= e($c['motorcycle_type']) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>
            
            <div class="customer-grid">
                <div class="form-group">
                    <label>Nama Pelanggan</label>
                    <input type="text" name="customer_name" required placeholder="Masukkan nama pelanggan">
                </div>
                <div class="form-group">
                    <label>Plat Nomor</label>
                    <input type="text" name="plate_number" data-history-plate required placeholder="B 1234 ABC" style="text-transform:uppercase">
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="phone" placeholder="08xxxxxxxxxx">
                </div>
                <div class="form-group">
                    <label>Tipe Motor</label>
                    <input type="text" name="motorcycle_type" placeholder="Beat, NMAX, dll">
                </div>
                <div class="form-group">
                    <label>Petugas Cuci</label>
                    <select name="employee_id" required>
                        <option value="">Pilih petugas</option>
                        <?php foreach ($employees as $e): ?>
                        <option value="<?= e($e['id']) ?>"><?= e($e['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div id="plate-history" class="plate-history"></div>
        </section>
        
        <!-- Services Section -->
        <section class="panel services-section">
            <div class="section-header">
                <h2>🛠️ Pilih Layanan</h2>
                <span class="selected-count">0 layanan dipilih</span>
            </div>
            
            <div class="services-grid">
                <?php foreach ($services as $s): ?>
                <label class="service-card">
                    <input type="radio" name="service" value="<?= e($s['id']) ?>" data-price="<?= e($s['price']) ?>">
                    <div class="service-content">
                        <div class="service-icon">🧼</div>
                        <div class="service-info">
                            <span class="service-name"><?= e($s['name']) ?></span>
                            <span class="service-meta"><?= e($s['category_name']) ?> • <?= e($s['estimated_duration']) ?> menit</span>
                        </div>
                        <div class="service-price"><?= money($s['price']) ?></div>
                    </div>
                </label>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
    
    <!-- Payment Section -->
    <aside class="panel payment-section">
        <div class="section-header">
            <h2>💳 Pembayaran</h2>
        </div>
        
        <div class="payment-methods">
            <label class="payment-option">
                <input type="radio" name="payment_method" value="Cash" checked>
                <span class="payment-icon">💵</span>
                <span class="payment-label">Tunai</span>
            </label>
            <label class="payment-option">
                <input type="radio" name="payment_method" value="QRIS">
                <span class="payment-icon">📱</span>
                <span class="payment-label">QRIS</span>
            </label>
            <label class="payment-option">
                <input type="radio" name="payment_method" value="Transfer">
                <span class="payment-icon">🏦</span>
                <span class="payment-label">Transfer</span>
            </label>
            <label class="payment-option">
                <input type="radio" name="payment_method" value="E-Wallet">
                <span class="payment-icon">💳</span>
                <span class="payment-label">E-Wallet</span>
            </label>
        </div>
        
        <div class="form-group">
            <label>Catatan</label>
            <textarea name="notes" placeholder="Tambahkan catatan transaksi..." rows="3"></textarea>
        </div>
        
        <div class="payment-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <strong data-subtotal>Rp 0</strong>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <strong data-total>Rp 0</strong>
            </div>
        </div>
        
        <input type="hidden" name="action" id="action-field">
        
        <div class="payment-actions">
            <button class="btn-paid" type="submit" onclick="document.getElementById('action-field').value='paid'">
                <span class="btn-icon">✓</span>
                <span class="btn-text">Lunas</span>
                <span class="btn-subtext">Langsung ke struk</span>
            </button>
            <button class="btn-unpaid" type="submit" onclick="document.getElementById('action-field').value='unpaid'">
                <span class="btn-icon">⏱️</span>
                <span class="btn-text">Belum Lunas</span>
                <span class="btn-subtext">Ke dashboard</span>
            </button>
        </div>
    </aside>
</form>

<style>
.cashier-form {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.form-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.section-header h2 {
    margin: 0;
    font-size: 18px;
    color: var(--ink);
}

.btn-clear {
    padding: 8px 16px;
    border-radius: 8px;
    border: 1px solid var(--line);
    background: var(--bg);
    cursor: pointer;
    font-weight: 600;
    font-size: 13px;
    transition: all 0.2s;
}

.btn-clear:hover {
    background: var(--line);
}

/* Customer Section */
.customer-section {
    background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.customer-search-wrapper {
    margin-bottom: 20px;
}

.customer-search-wrapper input {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--line);
    border-radius: 12px;
    font-size: 15px;
    transition: border-color 0.2s;
}

.customer-search-wrapper input:focus {
    outline: none;
    border-color: var(--brand);
}

.customer-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 16px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.form-group label {
    font-size: 13px;
    font-weight: 600;
    color: var(--muted);
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 12px 14px;
    border: 1px solid var(--line);
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(12, 124, 89, 0.1);
}

.priority-check {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-radius: 10px;
    cursor: pointer;
    margin-bottom: 16px;
}

.priority-check input {
    width: 18px;
    height: 18px;
    accent-color: var(--warn);
}

.priority-check span {
    font-weight: 600;
    color: #92400e;
}

.plate-history {
    margin-top: 12px;
}

/* Services Section */
.services-section {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.selected-count {
    font-size: 13px;
    color: var(--muted);
    background: var(--bg);
    padding: 6px 12px;
    border-radius: 20px;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 12px;
}

.service-card {
    cursor: pointer;
    border: 2px solid var(--line);
    border-radius: 12px;
    padding: 16px;
    transition: all 0.2s;
    background: #fff;
}

.service-card:hover {
    border-color: var(--brand);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(12, 124, 89, 0.15);
}

.service-card input:checked + .service-content {
    background: linear-gradient(135deg, rgba(12, 124, 89, 0.1) 0%, rgba(12, 124, 89, 0.05) 100%);
}

.service-card input {
    display: none;
}

.service-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.service-icon {
    font-size: 28px;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--bg);
    border-radius: 10px;
}

.service-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.service-name {
    font-weight: 600;
    color: var(--ink);
    font-size: 14px;
}

.service-meta {
    font-size: 12px;
    color: var(--muted);
}

.service-price {
    font-weight: 700;
    color: var(--brand);
    font-size: 15px;
}

/* Payment Section */
.payment-section {
    background: linear-gradient(135deg, #fff 0%, #f8fafc 100%);
    border: 1px solid var(--line);
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    position: sticky;
    top: 24px;
    height: fit-content;
}

.payment-methods {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-bottom: 20px;
}

.payment-option {
    cursor: pointer;
    border: 2px solid var(--line);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
    background: #fff;
}

.payment-option:hover {
    border-color: var(--brand);
}

.payment-option input:checked + .payment-icon + .payment-label {
    color: var(--brand);
    font-weight: 700;
}

.payment-option input {
    display: none;
}

.payment-icon {
    font-size: 24px;
}

.payment-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--muted);
}

.payment-summary {
    background: var(--bg);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 20px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: 14px;
}

.summary-row.total {
    border-top: 2px solid var(--line);
    margin-top: 8px;
    padding-top: 12px;
    font-size: 18px;
}

.summary-row.total strong {
    color: var(--brand);
    font-size: 22px;
}

.payment-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.btn-paid,
.btn-unpaid {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 24px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
    text-align: center;
}

.btn-paid {
    background: linear-gradient(135deg, var(--brand) 0%, #073b3a 100%);
    color: #fff;
}

.btn-paid:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(12, 124, 89, 0.3);
}

.btn-unpaid {
    background: linear-gradient(135deg, var(--warn) 0%, #d97706 100%);
    color: #fff;
}

.btn-unpaid:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(240, 162, 2, 0.3);
}

.btn-icon {
    font-size: 20px;
}

.btn-text {
    font-size: 16px;
}

.btn-subtext {
    font-size: 12px;
    opacity: 0.9;
    font-weight: 400;
}

@media (max-width: 1024px) {
    .cashier-form {
        grid-template-columns: 1fr;
    }
    
    .payment-section {
        position: static;
    }
}

@media (max-width: 768px) {
    .customer-grid {
        grid-template-columns: 1fr;
    }
    
    .services-grid {
        grid-template-columns: 1fr;
    }
    
    .payment-methods {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

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
        document.querySelector('[name="motorcycle_type"]').value = selectedOption.dataset.type || '';
    } else {
        customerId.value = '';
        document.querySelector('[name="customer_name"]').value = '';
        document.querySelector('[name="plate_number"]').value = '';
        document.querySelector('[name="phone"]').value = '';
        document.querySelector('[name="motorcycle_type"]').value = '';
    }
});

// Calculate total based on selected service
function calculateTotal() {
    const selectedService = document.querySelector('input[name="service"]:checked');
    
    if (selectedService) {
        const price = parseFloat(selectedService.dataset.price) || 0;
        const formattedTotal = 'Rp ' + price.toLocaleString('id-ID');
        document.querySelector('[data-subtotal]').textContent = formattedTotal;
        document.querySelector('[data-total]').textContent = formattedTotal;
        document.querySelector('.selected-count').textContent = '1 layanan dipilih';
    } else {
        document.querySelector('[data-subtotal]').textContent = 'Rp 0';
        document.querySelector('[data-total]').textContent = 'Rp 0';
        document.querySelector('.selected-count').textContent = '0 layanan dipilih';
    }
}

// Add event listeners to all service radio buttons
document.querySelectorAll('input[name="service"]').forEach(radio => {
    radio.addEventListener('change', calculateTotal);
});

// Initialize on page load
calculateTotal();
</script>

