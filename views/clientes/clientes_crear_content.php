<form action="<?= BASE_URL ?>/clientes_crear" method="POST">
    <div class="form-group">
        <label>Código</label>
        <input class="form-control" type="text" name="CODIGO" required value="<?= htmlspecialchars($ultimo_codigo ?? '') ?>" readonly>
    </div>

    <div class="form-group">
        <label>Nombre</label>
        <input class="form-control" type="text" name="NOMBRE" required>
    </div>

    <div class="form-group">
        <label>Dirección</label>
        <input class="form-control" type="text" name="DIRECCION">
    </div>

    <div class="form-group">
        <label>Localidad</label>
        <input class="form-control" type="text" name="LOCALIDAD">
    </div>

    <div class="form-group">
        <label>Provincia:</label>
        <input class="form-control" type="text" name="PROVINCIA">
    </div>

    <div class="form-group">
        <label>Postal</label>
        <input class="form-control" type="text" name="POSTAL">
    </div>

    <div class="form-group">
        <label>País</label>
        <input class="form-control" type="text" name="PAIS">
    </div>

    <div class="form-group">
        <label>Teléfono</label>
        <input class="form-control" type="text" name="TELEFONO">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input class="form-control" type="email" name="EMAIL">
    </div>

    <!-- Agrega más campos según lo necesites -->

    <button type="submit" class="btn btn-primary">Guardar</button>
</form>