<section class="panel">
    <div class="panel-head"><h2>Daftar Pelanggan</h2><form><input name="q" placeholder="Cari nama, telepon, plat" value="<?= e(query('q', '')) ?>"></form></div>
    <div class="table-wrap"><table><thead><tr><th>Nama</th><th>Plat</th><th>Motor</th><th>Kunjungan</th><th>Total Belanja</th><th>Kunjungan Terakhir</th><th></th></tr></thead><tbody>
    <?php foreach ($customers as $c): ?><tr>
        <td><?= e($c['name']) ?><small><?= e($c['phone']) ?></small></td>
        <td><strong><?= e($c['plate_number']) ?></strong></td>
        <td><?= e($c['motorcycle_brand'] . ' ' . $c['motorcycle_type']) ?></td>
        <td><?= e($c['total_visits']) ?></td>
        <td><?= money($c['total_spending']) ?></td>
        <td><?= e($c['last_visit'] ?: '-') ?></td>
        <td>
            <button class="ghost danger" type="button" onclick="confirmDeleteCustomer(<?= e($c['id']) ?>)">Hapus</button>
        </td>
    </tr><?php endforeach; ?>
    </tbody></table></div>
</section>
<script>
function confirmDeleteCustomer(id) {
    showConfirm('Yakin ingin menghapus pelanggan ini? Semua data terkait akan dihapus.').then(confirmed => {
        if (confirmed) {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = '<?= e(url('/customers/delete')) ?>';
            form.innerHTML = `
                <input type="hidden" name="_csrf" value="<?= e(\App\Core\Csrf::token()) ?>">
                <input type="hidden" name="id" value="${id}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

