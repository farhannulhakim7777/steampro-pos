<section class="panel">
    <h2>Stok Produk</h2>
    <form method="post" action="<?= e(url('/products/save')) ?>" class="grid five">
        <?= csrf_field() ?>
        <label>Nama<input name="name" required></label>
        <label>Kategori<input name="category" placeholder="Shampoo, Wax, Parfum"></label>
        <label>Harga<input type="number" min="0" name="price" required></label>
        <label>Stok<input type="number" min="0" name="stock" value="0"></label>
        <label>Stok Minimum<input type="number" min="0" name="low_stock_threshold" value="5"></label>
        <button class="primary" type="submit">Simpan Produk</button>
    </form>
</section>
<section class="content-grid">
    <div class="panel">
        <h2>Inventaris</h2>
        <div class="table-wrap"><table><thead><tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Peringatan</th></tr></thead><tbody>
        <?php foreach ($products as $p): ?><tr><td><?= e($p['name']) ?></td><td><?= e($p['category']) ?></td><td><?= money($p['price']) ?></td><td><?= e($p['stock']) ?></td><td><?= $p['stock'] <= $p['low_stock_threshold'] ? '<span class="badge danger">Rendah</span>' : '<span class="badge">OK</span>' ?></td></tr><?php endforeach; ?>
        </tbody></table></div>
    </div>
    <div class="panel">
        <h2>Pergerakan Stok</h2>
        <form method="post" action="<?= e(url('/products/stock')) ?>" class="stack">
            <?= csrf_field() ?>
            <label>Produk<select name="product_id"><?php foreach ($products as $p): ?><option value="<?= e($p['id']) ?>"><?= e($p['name']) ?></option><?php endforeach; ?></select></label>
            <label>Tipe<select name="type"><option value="in">Stok Masuk</option><option value="out">Stok Keluar</option></select></label>
            <label>Jumlah<input type="number" min="1" name="quantity" value="1"></label>
            <label>Catatan<input name="note"></label>
            <button class="primary" type="submit">Catat Pergerakan</button>
        </form>
    </div>
</section>

