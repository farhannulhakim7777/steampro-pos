<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;

final class AuthController extends Controller
{
    public function login(): void
    {
        if (Auth::check()) {
            redirect('/');
        }
        view('auth/login', ['title' => 'Login', 'authPage' => true]);
    }

    public function authenticate(): void
    {
        Csrf::validate();
        $email = trim((string) post('email'));
        $password = (string) post('password');

        $stmt = $this->db()->prepare('SELECT users.*, roles.name AS role_name, roles.slug AS role_slug FROM users JOIN roles ON roles.id = users.role_id WHERE users.email = ? AND users.status = "active" LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Email atau password tidak sesuai.'];
            redirect('/login');
        }

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role_slug' => $user['role_slug'],
            'role_name' => $user['role_name'],
        ];

        $this->db()->prepare('UPDATE users SET last_login_at = NOW(), remember_token = ? WHERE id = ?')
            ->execute([post('remember') ? bin2hex(random_bytes(32)) : null, $user['id']]);
        $this->log('login', 'users', (int) $user['id']);

        redirect('/');
    }

    public function logout(): void
    {
        Csrf::validate();
        $this->log('logout', 'users', Auth::id());
        $_SESSION = [];
        session_destroy();
        redirect('/login');
    }

    public function changePassword(): void
    {
        Auth::requireLogin();
        view('auth/change-password', ['title' => 'Change Password']);
    }

    public function updatePassword(): void
    {
        Auth::requireLogin();
        Csrf::validate();

        $current = (string) post('current_password');
        $new = (string) post('new_password');
        if (strlen($new) < 8) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Password baru minimal 8 karakter.'];
            redirect('/change-password');
        }

        $stmt = $this->db()->prepare('SELECT password_hash FROM users WHERE id = ?');
        $stmt->execute([Auth::id()]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($current, $user['password_hash'])) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Password lama tidak sesuai.'];
            redirect('/change-password');
        }

        $this->db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')->execute([password_hash($new, PASSWORD_DEFAULT), Auth::id()]);
        $this->log('change_password', 'users', Auth::id());
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Password berhasil diperbarui.'];
        redirect('/change-password');
    }
}

