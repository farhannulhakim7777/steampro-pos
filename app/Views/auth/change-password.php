<section class="panel narrow">
    <h2>Ganti Password</h2>
    <form method="post" action="<?= e(url('/change-password')) ?>" class="grid two">
        <?= csrf_field() ?>
        <label>Password Saat Ini<input type="password" name="current_password" required></label>
        <label>Password Baru<input type="password" name="new_password" minlength="8" required></label>
        <button class="primary" type="submit">Update Password</button>
    </form>
</section>

