<?php

require_once __DIR__ . '/../core/Controller.php';

class AuthController extends Controller
{
    public function login(): void
    {
        $this->renderStandalone('auth/login');
    }

    public function processLogin(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($email === 'admin@admin.com' && $password === '12345') {
            $this->redirect('index.php?action=libros');
        } else {
            $this->renderStandalone('auth/login', [
                'error' => 'Correo o contraseña incorrectos.',
                'old_email' => $email
            ]);
        }
    }
}
