<section class="panel">
    <div class="panel-head">
        <h2>Katalog Layanan</h2>
        <button class="primary" onclick="openServiceModal()">+ Tambah Layanan</button>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($services as $s): ?>
                <tr>
                    <td><strong><?= e($s['name']) ?></strong></td>
                    <td><?= e($s['category_name']) ?></td>
                    <td><?= money($s['price']) ?></td>
                    <td><?= e($s['estimated_duration']) ?> menit</td>
                    <td><span class="badge" style="background: <?= $s['status'] === 'active' ? 'var(--ok)' : 'var(--danger)' ?>; color: white;"><?= e($s['status']) ?></span></td>
                    <td style="text-align: right;">
                        <button class="ghost" onclick="editService(<?= $s['id'] ?>, '<?= addslashes(e($s['name'])) ?>', <?= $s['category_id'] ?>, <?= $s['price'] ?>, <?= $s['estimated_duration'] ?>, '<?= e($s['status']) ?>')">Edit</button>
                        <button class="ghost" style="color: var(--danger);" onclick="deleteService(<?= $s['id'] ?>, '<?= addslashes(e($s['name'])) ?>')">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<!-- Modal untuk Tambah/Edit Layanan -->
<div id="serviceModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Tambah Layanan</h3>
            <button class="close" onclick="closeServiceModal()">&times;</button>
        </div>
        <form method="post" action="<?= e(url('/services/save')) ?>" id="serviceForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="serviceId" value="0">
            <div class="form-group">
                <label>Nama Layanan</label>
                <input type="text" name="name" id="serviceName" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="category_id" id="serviceCategory" required>
                    <?php foreach ($categories as $c): ?>
                    <option value="<?= e($c['id']) ?>"><?= e($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="price" id="servicePrice" min="0" required>
            </div>
            <div class="form-group">
                <label>Durasi (menit)</label>
                <input type="number" name="estimated_duration" id="serviceDuration" min="1" value="20" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="serviceStatus">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="ghost" onclick="closeServiceModal()">Batal</button>
                <button type="submit" class="primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal untuk Hapus Konfirmasi -->
<div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h3>Hapus Layanan</h3>
            <button class="close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Apakah Anda yakin ingin menghapus layanan <strong id="deleteServiceName"></strong>?</p>
            <p class="muted" style="font-size: 13px;">Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <form method="post" action="<?= e(url('/services/delete')) ?>" id="deleteForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="deleteServiceId">
            <div class="modal-footer">
                <button type="button" class="ghost" onclick="closeDeleteModal()">Batal</button>
                <button type="submit" class="danger">Hapus</button>
            </div>
        </form>
    </div>
</div>

<style>
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    background: var(--bg);
    border-radius: 8px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid var(--border);
}
.modal-header h3 {
    margin: 0;
}
.close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--muted);
}
.close:hover {
    color: var(--ink);
}
.modal-body {
    padding: 20px;
}
.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 20px;
    border-top: 1px solid var(--border);
}
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}
.form-group input,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--border);
    border-radius: 4px;
    background: var(--bg);
    color: var(--ink);
}
.danger {
    background: var(--danger);
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}
.danger:hover {
    opacity: 0.9;
}
.panel-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
</style>

<script>
function openServiceModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Layanan';
    document.getElementById('serviceId').value = '0';
    document.getElementById('serviceName').value = '';
    document.getElementById('servicePrice').value = '';
    document.getElementById('serviceDuration').value = '20';
    document.getElementById('serviceStatus').value = 'active';
    document.getElementById('serviceModal').style.display = 'flex';
}

function closeServiceModal() {
    document.getElementById('serviceModal').style.display = 'none';
}

function editService(id, name, categoryId, price, duration, status) {
    document.getElementById('modalTitle').textContent = 'Edit Layanan';
    document.getElementById('serviceId').value = id;
    document.getElementById('serviceName').value = name;
    document.getElementById('serviceCategory').value = categoryId;
    document.getElementById('servicePrice').value = price;
    document.getElementById('serviceDuration').value = duration;
    document.getElementById('serviceStatus').value = status;
    document.getElementById('serviceModal').style.display = 'flex';
}

function deleteService(id, name) {
    document.getElementById('deleteServiceName').textContent = name;
    document.getElementById('deleteServiceId').value = id;
    document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

