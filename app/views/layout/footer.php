<?php if (!isset($isAuthPage) || !$isAuthPage): ?>
        </main>
        <footer class="text-center text-muted py-3 small mt-auto">
            &copy; <?= date('Y') ?> Desarrollado por: <b>Franklin MQ</b>
            <br>Todos los derechos reservados.
        </footer>
    </div>
</div>
<?php else: ?>
    <footer class="auth-footer" style="text-align: center; color: #6b7280; font-size: 0.8rem; background: transparent; width: 100%; z-index: 10; position: absolute; bottom: 30px; pointer-events: none;">
        &copy; <?= date('Y') ?> Desarrollado por: <b>Franklin MQ</b>
        <br>Todos los derechos reservados.
    </footer>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('.table-datatable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
            },
            "pageLength": 10,
            "lengthChange": false,
            "info": true
        });
    });
</script>
<script src="assets/js/app.js"></script>
</body>
</html>
