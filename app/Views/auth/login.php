<section class="login-stage">
    <div class="login-panel">
        <div class="brand large">
            <!-- Logo Image - Ganti path gambar di bawah ini -->
            <img src="<?= e(url('/assets/images/logo.png')) ?>" alt="Logo" class="brand-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <!-- Fallback jika gambar tidak ada -->
            <span class="brand-mark" style="display:none;">SP</span>
            <span><strong>SteamPro POS</strong><small>Akses operator aman</small></span>
        </div>
        <?php if ($flash): ?><div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
        <form method="post" action="<?= e(url('/login')) ?>" class="stack">
            <?= csrf_field() ?>
            <label>Email<input name="email" type="email" autocomplete="email" required autofocus></label>
            <label>Password<input name="password" type="password" autocomplete="current-password" required></label>
            <label class="check"><input type="checkbox" name="remember" value="1"> Ingat login</label>
            <button class="primary" type="submit">Masuk POS</button>
        </form>
    </div>
    <div class="login-art">
        <p>Irama bengkel hari ini</p>
        <strong>Antrian, cuci, detail, antar.</strong>
        <span>Dirancang untuk tim cuci motor yang bergerak cepat dan tetap butuh angka yang bersih.</span>
    </div>
</section>

