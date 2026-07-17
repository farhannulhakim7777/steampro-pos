<?php
$config = require dirname(__DIR__, 3) . '/config/config.php';
$user = \App\Core\Auth::user();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$authPage = $authPage ?? false;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= e(\App\Core\Csrf::token()) ?>">
    <meta name="app-base" content="<?= e(app_base_path()) ?>">
    <title><?= e($title ?? $config['app']['name']) ?> - <?= e($config['app']['name']) ?></title>
    <link rel="stylesheet" href="<?= e(url('/assets/css/app.css')) ?>">
</head>
<body class="<?= $authPage ? 'auth-shell' : '' ?>">
<?php if ($authPage): ?>
    <?php require $viewFile; ?>
<?php else: ?>
<div class="shell">
    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    <aside class="sidebar" id="sidebar">
        <a class="brand" href="<?= e(url('/')) ?>">
            <!-- Logo Image - Ganti path gambar di bawah ini -->
            <img src="<?= e(url('/assets/images/logo.png')) ?>" alt="Logo" class="brand-logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <!-- Fallback jika gambar tidak ada -->
            <span class="brand-mark" style="display:none;">SP</span>
            <span><strong>SteamPro</strong><small>POS Cuci Motor</small></span>
        </a>
        <nav>
            <?php
            $userRole = $user['role_slug'] ?? '';
            if ($userRole === 'cashier') {
                $links = [
                    '/' => 'Dashboard', '/cashier' => 'Kasir', '/queue' => 'Papan Antrian',
                ];
            } else {
                $links = [
                    '/' => 'Dashboard', '/cashier' => 'Kasir', '/queue' => 'Papan Antrian', '/customers' => 'Pelanggan',
                    '/services' => 'Layanan', '/products' => 'Produk', '/employees' => 'Karyawan',
                    '/expenses' => 'Pengeluaran', '/reports' => 'Laporan', '/settings' => 'Pengaturan',
                ];
            }
            foreach ($links as $href => $label):
            ?>
                <a class="<?= (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/') === app_base_path() . ($href === '/' ? '/' : $href) ? 'active' : '' ?>" href="<?= e(url($href)) ?>"><?= e($label) ?></a>
            <?php endforeach; ?>
        </nav>
    </aside>
    <main class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button class="menu-toggle" id="menu-toggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div>
                    <p class="eyebrow"><?= e(date('l, d M Y')) ?></p>
                    <h1><?= e($title ?? 'SteamPro POS') ?></h1>
                </div>
            </div>
            <div class="userbox">
                <span><?= e($user['name'] ?? '') ?><small><?= e($user['role_name'] ?? '') ?></small></span>
                <?php if (($user['role_slug'] ?? '') !== 'cashier'): ?>
                <a class="ghost" href="<?= e(url('/change-password')) ?>">Password</a>
                <?php endif; ?>
                <form method="post" action="<?= e(url('/logout')) ?>"><?= csrf_field() ?><button class="ghost" type="submit">Keluar</button></form>
            </div>
        </header>
        <?php if ($flash): ?><div class="alert <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div><?php endif; ?>
        <?php require $viewFile; ?>
    </main>
</div>
<!-- Custom Modal -->
<div id="confirm-modal" class="modal-overlay">
    <div class="modal-content">
        <h3 id="modal-title">Konfirmasi</h3>
        <p id="modal-message">Apakah Anda yakin?</p>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeModal()">Batal</button>
            <button class="btn-confirm" id="modal-confirm-btn">Ya</button>
        </div>
    </div>
</div>
<?php endif; ?>
<script src="<?= e(url('/assets/js/app.js')) ?>"></script>
</body>
</html>

