<h2>Crear usuario ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>

<form method="post" action="<?= BASE_URL ?>/usuarios_crear" enctype="multipart/form-data">
  <div class="form-group">
    <label for="nombre">Nombre</label>
    <input 
      type="text" 
      class="form-control" 
      id="nombre" 
      name="nombre" 
      placeholder="Nombre completo"
    >
  </div>

  <div class="form-group">
    <label for="email">Email</label>
    <input 
      type="email" 
      class="form-control" 
      id="email" 
      name="email" 
      placeholder="ejemplo@dominio.com"
    >
  </div>

  <div class="form-group">
    <label for="passwd">Contraseña</label>
    <input 
      type="password" 
      class="form-control" 
      id="passwd" 
      name="passwd" 
      placeholder="Contraseña"
    >
  </div>

  <div class="form-group">
    <label for="alias">Alias</label>
    <input 
      type="text" 
      class="form-control" 
      id="alias" 
      name="alias" 
      placeholder="Nombre de usuario o alias"
    >
  </div>

  <div class="form-group">
    <label for="telefono">Teléfono</label>
    <input 
      type="tel" 
      class="form-control" 
      id="telefono" 
      name="telefono" 
      placeholder="Ej. +34 600 123 456"
    >
  </div>

  <div class="form-group">
    <label for="departamento_id">Departamento</label>
				<select name="departamento_id" id="departamento_id" class="form-select" required>
					<option value="">Seleccionar...</option>
					<?php foreach ($departamentos as $departamento): ?>
						<option value="<?= $departamento['id'] ?>"><?= htmlspecialchars($departamento['nombre']) ?></option>
					<?php endforeach; ?>
				</select>
  </div>

  <button type="submit" class="btn btn-primary">Enviar</button>
</form>

