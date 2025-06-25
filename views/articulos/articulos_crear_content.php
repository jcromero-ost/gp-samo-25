<form action="<?= BASE_URL ?>/articulos_crear" method="POST">
    <div class="form-group">
        <label>Código</label>
        <input class="form-control" type="text" name="CODIGO" required maxlength="15" value="<?php echo $nuevoCLAART; ?>">
    </div>

    <div class="form-group">
        <label>Nombre</label>
        <input class="form-control" type="text" name="NOMBRE" required maxlength="80">
    </div>

    <div class="form-group">
        <label>Referencia Proveedor</label>
        <input class="form-control" type="text" name="REFPROV" maxlength="14">
    </div>

    <div class="form-group">
        <label>Precio 1</label>
        <input class="form-control" type="number" name="PVP1" step="0.01">
    </div>

    <div class="form-group">
        <label>Precio 1 con IVA</label>
        <input class="form-control" type="number" name="PVP1IVA" step="0.01">
    </div>

    <div class="form-group">
        <label>Tipo IVA</label>
        <input class="form-control" type="number" name="TIVA" min="0" max="9">
    </div>

    <div class="form-group">
        <label>Stock mínimo</label>
        <input class="form-control" type="number" name="EXMIN" min="0">
    </div>

    <div class="form-group">
        <label>Stock máximo</label>
        <input class="form-control" type="number" name="EXMAX" min="0">
    </div>

    <div class="form-group">
        <label>Pedido mínimo</label>
        <input class="form-control" type="number" name="PEDMIN" min="0">
    </div>

    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" name="LOTES" value="1">
        <label class="form-check-label">Lotes</label>
    </div>

    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" name="CADUCA" value="1">
        <label class="form-check-label">Caduca</label>
    </div>

    <div class="form-group">
        <label>Unidad</label>
        <input class="form-control" type="number" name="CLAUNI" min="0">
    </div>

    <div class="form-group">
        <label>Ubicación</label>
        <input class="form-control" type="text" name="UBICA" maxlength="20">
    </div>

    <div class="form-group">
        <label>Nombre Web</label>
        <input class="form-control" type="text" name="NOMBREWEB" maxlength="40">
    </div>

    <div class="form-group">
        <label>Atributo 1</label>
        <input class="form-control" type="text" name="ATRIB1" maxlength="70">
    </div>

    <div class="form-group">
        <label>Atributo 2</label>
        <input class="form-control" type="text" name="ATRIB2" maxlength="70">
    </div>

    <!-- Puedes continuar con más campos según sea necesario -->

    <button type="submit" class="btn btn-primary">Guardar artículo</button>
</form>
