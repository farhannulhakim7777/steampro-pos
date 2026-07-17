<section class="panel">
    <h2>Manajemen Karyawan</h2>
    <form method="post" action="<?= e(url('/employees/save')) ?>" class="grid four">
        <?= csrf_field() ?>
        <label>Nama<input name="name" required></label>
        <label>No. Telepon<input name="phone"></label>
        <label>Posisi<input name="position" placeholder="Pencuci / Detailer"></label>
        <label>Gaji<input type="number" min="0" name="salary"></label>
        <label>Tanggal Bergabung<input type="date" name="join_date"></label>
        <label>Status<select name="status"><option>active</option><option>inactive</option></select></label>
        <label class="wide-field">Alamat<input name="address"></label>
        <button class="primary" type="submit">Simpan Karyawan</button>
    </form>
</section>
<section class="panel"><div class="table-wrap"><table><thead><tr><th>Nama</th><th>No. Telepon</th><th>Posisi</th><th>Gaji</th><th>Pekerjaan Selesai</th><th>Status</th></tr></thead><tbody>
<?php foreach ($employees as $e): ?><tr><td><?= e($e['name']) ?></td><td><?= e($e['phone']) ?></td><td><?= e($e['position']) ?></td><td><?= money($e['salary']) ?></td><td><?= e($e['completed_jobs']) ?></td><td><span class="badge"><?= e($e['status']) ?></span></td></tr><?php endforeach; ?>
</tbody></table></div></section>

