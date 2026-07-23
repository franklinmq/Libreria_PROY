<?php
// Variable $old o $producto según el contexto (crear/editar)
$valores = $producto ?? $old ?? [];
$val = fn($campo) => htmlspecialchars($valores[$campo] ?? '');
?>

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
        <input type="text" name="nombre" value="<?= $val('nombre') ?>"
               class="form-control <?= isset($errores['nombre']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errores['nombre'])): ?>
            <div class="invalid-feedback"><?= $errores['nombre'] ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <label class="form-label">Código de Barras <span class="text-danger">*</span></label>
        <input type="text" name="codigo_barras" value="<?= $val('codigo_barras') ?>"
               class="form-control <?= isset($errores['codigo_barras']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errores['codigo_barras'])): ?>
            <div class="invalid-feedback"><?= $errores['codigo_barras'] ?></div>
        <?php endif; ?>
    </div>

    <div class="col-md-6">
        <label class="form-label">Marca</label>
        <div class="input-group">
            <select name="marca_id" id="marca_id" class="form-select">
                <option value="">-- Sin marca --</option>
                <?php foreach ($marcas as $marca): ?>
                    <option value="<?= $marca['id'] ?>" <?= (($valores['marca_id'] ?? '') == $marca['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($marca['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalMarca" title="Nueva Marca">
                <i class="bi bi-plus-circle"></i>
            </button>
        </div>
    </div>
    
    <div class="col-md-6">
        <label class="form-label">Categoría</label>
        <div class="input-group">
            <select name="categoria_id" id="categoria_id" class="form-select">
                <option value="">-- Sin categoría --</option>
                <?php
                $principales = array_filter($categorias, fn($c) => empty($c['parent_id']));
                $subcategorias = array_filter($categorias, fn($c) => !empty($c['parent_id']));
                ?>
                <?php foreach ($principales as $principal): ?>
                    <option value="<?= $principal['id'] ?>" <?= (($valores['categoria_id'] ?? '') == $principal['id']) ? 'selected' : '' ?> class="fw-bold">
                        <?= htmlspecialchars($principal['nombre']) ?>
                    </option>
                    <?php foreach ($subcategorias as $sub): ?>
                        <?php if ($sub['parent_id'] == $principal['id']): ?>
                            <option value="<?= $sub['id'] ?>" <?= (($valores['categoria_id'] ?? '') == $sub['id']) ? 'selected' : '' ?>>
                                &nbsp;&nbsp;&nbsp;&nbsp;&#8627; <?= htmlspecialchars($sub['nombre']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#modalCategoria" title="Nueva Categoría">
                <i class="bi bi-plus-circle"></i>
            </button>
        </div>
    </div>

    <div class="col-md-12">
        <label class="form-label">Descripción</label>
        <textarea name="descripcion" class="form-control" rows="3"><?= $val('descripcion') ?></textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">Precio Compra (Bs./$) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" name="precio_compra" value="<?= $val('precio_compra') ?>"
               class="form-control <?= isset($errores['precio_compra']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errores['precio_compra'])): ?>
            <div class="invalid-feedback"><?= $errores['precio_compra'] ?></div>
        <?php endif; ?>
    </div>
    
    <div class="col-md-4">
        <label class="form-label">Precio Venta (Bs./$) <span class="text-danger">*</span></label>
        <input type="number" step="0.01" min="0" name="precio_venta" value="<?= $val('precio_venta') ?>"
               class="form-control <?= isset($errores['precio_venta']) ? 'is-invalid' : '' ?>">
        <?php if (isset($errores['precio_venta'])): ?>
            <div class="invalid-feedback"><?= $errores['precio_venta'] ?></div>
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
