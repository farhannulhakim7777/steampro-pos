<section class="panel">
    <h2>Katalog Layanan</h2>
    <form method="post" action="<?= e(url('/services/save')) ?>" class="grid five">
        <?= csrf_field() ?>
        <label>Nama<input name="name" required></label>
        <label>Kategori<select name="category_id"><?php foreach ($categories as $c): ?><option value="<?= e($c['id']) ?>"><?= e($c['name']) ?></option><?php endforeach; ?></select></label>
        <label>Harga<input type="number" min="0" name="price" required></label>
        <label>Durasi<input type="number" min="1" name="estimated_duration" value="20"></label>
        <label>Status<select name="status"><option>active</option><option>inactive</option></select></label>
        <button class="primary" type="submit">Simpan Layanan</button>
    </form>
</section>
<section class="panel">
    <div class="table-wrap"><table><thead><tr><th>Layanan</th><th>Kategori</th><th>Harga</th><th>Durasi</th><th>Status</th></tr></thead><tbody>
    <?php foreach ($services as $s): ?><tr><td><?= e($s['name']) ?></td><td><?= e($s['category_name']) ?></td><td><?= money($s['price']) ?></td><td><?= e($s['estimated_duration']) ?> menit</td><td><span class="badge"><?= e($s['status']) ?></span></td></tr><?php endforeach; ?>
    </tbody></table></div>
</section>

