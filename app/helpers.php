<?php

use App\Core\Csrf;

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function money(int|float|null $value): string
{
    return 'Rp ' . number_format((float) $value, 0, ',', '.');
}

function app_base_path(): string
{
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    return $scriptDir === '/' ? '' : rtrim($scriptDir, '/');
}

function url(string $path = '/'): string
{
    $base = app_base_path();
    $path = '/' . ltrim($path, '/');
    return ($base === '' ? '' : $base) . ($path === '/' ? '/' : $path);
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(Csrf::token()) . '">';
}

function view(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $viewFile = dirname(__DIR__) . '/app/Views/' . $template . '.php';
    require dirname(__DIR__) . '/app/Views/layouts/app.php';
}

function partial(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require dirname(__DIR__) . '/app/Views/' . $template . '.php';
}

function post(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $default;
}

function query(string $key, mixed $default = null): mixed
{
    return $_GET[$key] ?? $default;
}