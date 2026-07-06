<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cuenta - Librería</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/auth.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>
    <div class="split-container">
        <div class="left-panel">
            <div class="bg-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-3"></div>
                <div class="shape shape-2"></div>
            </div>

            <div class="left-content">
                <img src="assets/img/logo.png" alt="Logo Librería" class="auth-logo">
                <h1>ÚNETE</h1>
                <h2>AL SISTEMA DE INVENTARIO</h2>
                <p>Crea tu cuenta para administrar tus productos.
                    Controla tus compras, ventas, créditos y reportes financieros de manera rápida y segura.</p>
            </div>
        </div>

        <div class="right-panel">
            <div class="login-wrapper">
                <h2>Crear Cuenta</h2>
                <p class="subtitle">Ingresa tus datos para registrarte en el sistema de la librería.</p>

                <?php if (!empty($error)): ?>
                    <div
                        style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?action=process-register" method="POST" id="registerForm">
                    <div class="input-group">
                        <span class="icon-left"><i class="bi bi-person-badge-fill"></i></span>
                        <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($old_nombre ?? '') ?>"
                            placeholder="Nombre Completo" required>
                    </div>

                    <div class="input-group">
                        <span class="icon-left"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" name="email" id="email" value="<?= htmlspecialchars($old_email ?? '') ?>"
                            placeholder="Correo Electrónico" required>
                    </div>

                    <div class="input-group">
                        <span class="icon-left"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" id="password" placeholder="Contraseña" required>
                        <button type="button" class="btn-show" id="showPassword">MOSTRAR</button>
                    </div>

                    <button type="submit" class="btn-primary" id="registerBtn">
                        <span class="btn-text">Crear Cuenta</span>
                    </button>

                    <p class="signup-link">¿Ya tienes cuenta? <a href="index.php?action=login">Inicia sesión</a></p>
                </form>
            </div>

            <div class="shape-4"></div>
        </div>
    </div>

    <script src="assets/js/auth.js"></script>
</body>

</html>
