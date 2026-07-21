<section class="panel">
    <h2>Pengeluaran Operasional</h2>
    <form method="post" action="<?= e(url('/expenses/save')) ?>" class="grid four" id="expense-form">
        <?= csrf_field() ?>
        <input type="hidden" name="id" id="expense-id" value="">
        <label>Tanggal<input type="date" name="expense_date" id="expense-date" value="<?= e(date('Y-m-d')) ?>"></label>
        <label>Kategori<select name="category" id="expense-category"><option value="Listrik">Listrik</option><option value="Air">Air</option><option value="Sewa">Sewa</option><option value="Peralatan">Peralatan</option><option value="Gaji">Gaji</option><option value="Pemeliharaan">Pemeliharaan</option><option value="Pengeluaran Lainnya">Pengeluaran Lainnya</option></select></label>
        <label>Jumlah<input type="number" min="0" name="amount" id="expense-amount" required></label>
        <label>Deskripsi<input name="description" id="expense-description"></label>
        <button class="primary" type="submit" id="expense-submit-btn">Simpan Pengeluaran</button>
        <button class="ghost" type="button" id="expense-cancel-btn" style="display:none;" onclick="resetExpenseForm()">Batal Edit</button>
    </form>
</section>
<section class="panel"><div class="table-wrap"><table><thead><tr><th>Tanggal</th><th>Kategori</th><th>Jumlah</th><th>Deskripsi</th><th>Aksi</th></tr></thead><tbody>
<?php foreach ($expenses as $x): ?><tr>
    <td><?= e($x['expense_date']) ?></td>
    <td><?= e($x['category']) ?></td>
    <td><?= money($x['amount']) ?></td>
    <td><?= e($x['description']) ?></td>
    <td>
        <button class="ghost" onclick="editExpense(<?= e($x['id']) ?>, '<?= e($x['expense_date']) ?>', '<?= e($x['category']) ?>', <?= e($x['amount']) ?>, '<?= e($x['description']) ?>')" style="padding:6px 12px;font-size:12px;">Edit</button>
        <form method="post" action="<?= e(url('/expenses/delete')) ?>" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?');">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= e($x['id']) ?>">
            <button class="ghost danger" type="submit" style="padding:6px 12px;font-size:12px;">Hapus</button>
        </form>
    </td>
</tr><?php endforeach; ?>
</tbody></table></div></section>

<script>
function editExpense(id, date, category, amount, description) {
    document.getElementById('expense-id').value = id;
    document.getElementById('expense-date').value = date;
    document.getElementById('expense-category').value = category;
    document.getElementById('expense-amount').value = amount;
    document.getElementById('expense-description').value = description;
    document.getElementById('expense-submit-btn').textContent = 'Update Pengeluaran';
    document.getElementById('expense-cancel-btn').style.display = 'inline-block';
    document.getElementById('expense-form').scrollIntoView({ behavior: 'smooth' });
}

function resetExpenseForm() {
    document.getElementById('expense-id').value = '';
    document.getElementById('expense-date').value = '<?= e(date('Y-m-d')) ?>';
    document.getElementById('expense-category').value = 'Listrik';
    document.getElementById('expense-amount').value = '';
    document.getElementById('expense-description').value = '';
    document.getElementById('expense-submit-btn').textContent = 'Simpan Pengeluaran';
    document.getElementById('expense-cancel-btn').style.display = 'none';
}
</script>

