<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Editar libro</h5>
            </div>
            <div class="card-body">
                <form method="post" action="index.php?action=libro-actualizar&id=<?= $libro['id'] ?>" novalidate>
                    <?php include __DIR__ . '/_form.php'; ?>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="index.php?action=libros" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-add">
                            <i class="bi bi-save me-1"></i> Actualizar libro
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
