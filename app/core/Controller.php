<?php

class Controller
{
    /**
     * Renderiza una vista dentro del layout general (header/footer)
     */
    protected function render(string $view, array $data = []): void
    {
        extract($data);

        $viewPath = __DIR__ . "/../views/{$view}.php";

        if (!file_exists($viewPath)) {
            die("La vista '{$view}' no existe.");
        }

        require __DIR__ . '/../views/layout/header.php';
        require $viewPath;
        require __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Renderiza una vista aislada, sin header ni footer (útil para login)
     */
    protected function renderStandalone(string $view, array $data = []): void
    {
        extract($data);

        $viewPath = __DIR__ . "/../views/{$view}.php";

        if (!file_exists($viewPath)) {
            die("La vista '{$view}' no existe.");
        }

        require $viewPath;
    }

    /**
     * Redirige a otra acción/ruta interna
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}
