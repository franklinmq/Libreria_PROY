<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Librería</title>
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
                <h1>BIENVENIDO</h1>
                <h2>SISTEMA DE INVENTARIO</h2>
                <p>Administra tus productos.
                    Controla tus compras, ventas, créditos y reportes financieros de manera rápida y segura.
                    ¡Accede para optimizar tu negocio!</p>
            </div>
        </div>

        <div class="right-panel">
            <div class="login-wrapper">
                <h2>Iniciar Sesión</h2>
                <p class="subtitle">Ingresa tus credenciales para acceder al sistema de la librería.</p>

                <?php if (!empty($error)): ?>
                    <div
                        style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <div
                        style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?action=process-login" method="POST" id="loginForm">
                    <div class="input-group">
                        <span class="icon-left"><i class="bi bi-person-fill"></i></span>
                        <input type="text" name="email" id="email" value="<?= htmlspecialchars($old_email ?? '') ?>"
                            placeholder="Usuario o Correo" required>
                    </div>

                    <div class="input-group">
                        <span class="icon-left"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password" id="password" placeholder="Contraseña" required>
                        <button type="button" class="btn-show" id="showPassword">MOSTRAR</button>
                    </div>

                    <div class="form-options">
                        <label class="remember">
                            <input type="checkbox" name="remember">
                            <span>Recordarme</span>
                        </label>
                        <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="btn-primary" id="loginBtn">
                        <span class="btn-text">Iniciar Sesión</span>
                    </button>

                    <p class="signup-link">¿No tienes cuenta? <a href="index.php?action=register">Crea tu cuenta</a></p>
                </form>
            </div>

            <div class="shape-4"></div>
        </div>
    </div>

    <script src="assets/js/auth.js"></script>
    
    <?php 
    $isAuthPage = true;
    require_once __DIR__ . '/../layout/footer.php'; 
    ?>