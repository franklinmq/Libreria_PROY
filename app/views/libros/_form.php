<?php
// Variable $old o $libro según el contexto (crear/editar)
$valores = $libro ?? $old ?? [];
$val = fn($campo) => htmlspecialchars($valores[$campo] ?? '');
?>

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Título <span class="text-danger">*</span></label>
        <input type="text" name="titulo" value="<?= $val('titulo') ?>"
               class="form-control <?= isset($errores['titulo']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errores['titulo'])): ?>
            <div class="invalid-feedback"><?= $errores['titulo'] ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <label class="form-label">ISBN <span class="text-danger">*</span></label>
        <input type="text" name="isbn" value="<?= $val('isbn') ?>"
               class="form-control <?= isset($errores['isbn']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errores['isbn'])): ?>
            <div class="invalid-feedback"><?= $errores['isbn'] ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Autor <span class="text-danger">*</span></label>
        <input type="text" name="autor" value="<?= $val('autor') ?>"
               class="form-control <?= isset($errores['autor']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errores['autor'])): ?>
            <div class="invalid-feedback"><?= $errores['autor'] ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Editorial</label>
        <input type="text" name="editorial" value="<?= $val('editorial') ?>" class="form-control">
    </div>

    <div class="col-md-4">
        <label class="form-label">Categoría</label>
        <select name="categoria_id" class="form-select">
            <option value="">-- Sin categoría --</option>
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= (($valores['categoria_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Precio (Bs./$) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" name="precio" value="<?= $val('precio') ?>"
               class="form-control <?= isset($errores['precio']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errores['precio'])): ?>
            <div class="invalid-feedback"><?= $errores['precio'] ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <label class="form-label">Stock (unidades) <span class="text-danger">*</span></label>
        <input type="number" min="0" name="stock" value="<?= $val('stock') ?>"
               class="form-control <?= isset($errores['stock']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errores['stock'])): ?>
            <div class="invalid-feedback"><?= $errores['stock'] ?></div>
        <?php endif; ?>
    </div>
</div>
