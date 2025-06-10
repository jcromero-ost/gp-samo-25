<form action="<?= BASE_URL ?>/clientes_crear" method="POST">
    <label>Código:</label>
    <input type="text" name="CODIGO" required value="<?= htmlspecialchars($ultimo_codigo ?? '') ?>"><br>

    <label>Nombre:</label>
    <input type="text" name="NOMBRE" required><br>

    <label>Dirección:</label>
    <input type="text" name="DIRECCION"><br>

    <label>Localidad:</label>
    <input type="text" name="LOCALIDAD"><br>

    <label>Provincia:</label>
    <input type="text" name="PROVINCIA"><br>

    <label>Postal:</label>
    <input type="text" name="POSTAL"><br>

    <label>País:</label>
    <input type="text" name="PAIS"><br>

    <label>Teléfono:</label>
    <input type="text" name="TELEFONO"><br>

    <label>Email:</label>
    <input type="email" name="EMAIL"><br>

    <!-- Agrega más campos según lo necesites -->

    <button type="submit">Guardar</button>
</form>