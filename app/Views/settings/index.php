<section class="panel narrow">
    <h2>Pengaturan Bisnis</h2>
    <form method="post" action="<?= e(url('/settings/save')) ?>" class="stack">
        <?= csrf_field() ?>
        <label>Nama Bisnis<input name="business_name" value="<?= e($settings['business_name'] ?? 'SteamPro POS') ?>"></label>
        <label>Alamat<input name="business_address" value="<?= e($settings['business_address'] ?? '') ?>"></label>
        <label>Telepon<input name="business_phone" value="<?= e($settings['business_phone'] ?? '') ?>"></label>
        <label>Footer Struk<input name="receipt_footer" value="<?= e($settings['receipt_footer'] ?? 'Terima kasih. Berkendara bersih, berkendara aman.') ?>"></label>
        <label class="check"><input type="checkbox" name="dark_mode" value="1" <?= !empty($settings['dark_mode']) ? 'checked' : '' ?>> Gunakan mode gelap untuk operator</label>
        <button class="primary" type="submit">Simpan Pengaturan</button>
    </form>
</section>

