<section class="panel">
    <h2>Pengeluaran Operasional</h2>
    <form method="post" action="<?= e(url('/expenses/save')) ?>" class="grid four">
        <?= csrf_field() ?>
        <label>Tanggal<input type="date" name="expense_date" value="<?= e(date('Y-m-d')) ?>"></label>
        <label>Kategori<select name="category"><option>Listrik</option><option>Air</option><option>Sewa</option><option>Peralatan</option><option>Gaji</option><option>Pemeliharaan</option><option>Pengeluaran Lainnya</option></select></label>
        <label>Jumlah<input type="number" min="0" name="amount" required></label>
        <label>Deskripsi<input name="description"></label>
        <button class="primary" type="submit">Simpan Pengeluaran</button>
    </form>
</section>
<section class="panel"><div class="table-wrap"><table><thead><tr><th>Tanggal</th><th>Kategori</th><th>Jumlah</th><th>Deskripsi</th></tr></thead><tbody>
<?php foreach ($expenses as $x): ?><tr><td><?= e($x['expense_date']) ?></td><td><?= e($x['category']) ?></td><td><?= money($x['amount']) ?></td><td><?= e($x['description']) ?></td></tr><?php endforeach; ?>
</tbody></table></div></section>

