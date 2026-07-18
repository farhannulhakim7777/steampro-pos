<section class="panel">
    <h2><?= isset($editEmployee) ? '✏️ Edit Karyawan' : 'Tambah Karyawan' ?></h2>
    <?php if ($editEmployee): ?>
    <p class="muted" style="font-size:13px;margin-top:-10px;margin-bottom:14px;">
        Anda sedang mengedit data karyawan. <a href="<?= e(url('/employees')) ?>">Batal / Tambah Baru</a>
    </p>
    <?php endif; ?>
    <form method="post" action="<?= e(url('/employees/save')) ?>" class="grid four">
        <?php $e = $editEmployee ?? null; ?>
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= e($e['id'] ?? '') ?>">
        <label>Nama<input name="name" required value="<?= e($e['name'] ?? '') ?>"></label>
        <label>No. Telepon<input name="phone" value="<?= e($e['phone'] ?? '') ?>"></label>
        <label>Posisi<input name="position" placeholder="Pencuci / Detailer" value="<?= e($e['position'] ?? '') ?>"></label>
        <label>Gaji<input type="number" min="0" name="salary" value="<?= e($e['salary'] ?? '') ?>"></label>
        <label>Tanggal Bergabung<input type="date" name="join_date" value="<?= e($e['join_date'] ?? '') ?>"></label>
        <label>Status<select name="status">
            <option value="active" <?= (isset($e['status']) && $e['status'] === 'active') ? 'selected' : '' ?>>active</option>
            <option value="inactive" <?= (isset($e['status']) && $e['status'] === 'inactive') ? 'selected' : '' ?>>inactive</option>
        </select></label>
        <label class="wide-field">Alamat<input name="address" value="<?= e($e['address'] ?? '') ?>"></label>
        <button class="primary" type="submit">Simpan Karyawan</button>
        <?php if ($editEmployee): ?>
        <a href="<?= e(url('/employees')) ?>" class="ghost" style="display:inline-flex;align-items:center;padding:10px 16px;border-radius:8px;text-decoration:none;font-weight:600;">Batal</a>
        <?php endif; ?>
    </form>
</section>
<section class="panel"><div class="table-wrap"><table><thead><tr><th>Nama</th><th>No. Telepon</th><th>Posisi</th><th>Gaji</th><th>Pekerjaan Selesai</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($employees as $emp): ?>
        <tr>
            <td><?= e($emp['name']) ?></td>
            <td><?= e($emp['phone']) ?></td>
            <td><?= e($emp['position']) ?></td>
            <td><?= money($emp['salary']) ?></td>
            <td><?= e($emp['completed_jobs']) ?></td>
            <td><span class="badge"><?= e($emp['status']) ?></span></td>
            <td><a href="<?= e(url('/employees?id=' . $emp['id'])) ?>" class="btn-small">✏️ Edit</a></td>
        </tr>
<?php endforeach; ?>
</tbody></table></div></section>
