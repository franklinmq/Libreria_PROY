<?php if (!isset($isAuthPage) || !$isAuthPage): ?>
        </main>
    </div>
</div>
<?php endif; ?>

<footer class="<?= isset($isAuthPage) && $isAuthPage ? 'auth-footer' : 'text-center text-muted py-4 small mt-auto' ?>" style="<?= isset($isAuthPage) && $isAuthPage ? 'text-align: center; color: #6b7280; font-size: 0.8rem; background: transparent; width: 100%; z-index: 10; position: absolute; bottom: 30px; pointer-events: none;' : '' ?>">
    &copy; <?= date('Y') ?> Desarrollado por: <b>Franklin MQ</b>
    <br>Todos los derechos reservados.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
