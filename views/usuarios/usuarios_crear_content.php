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
    <label for="fecha_creacion">Fecha de creación</label>
    <input 
      type="date" 
      class="form-control" 
      id="fecha_creacion" 
      name="fecha_creacion"
    >
  </div>

  <div class="form-group">
    <label for="departamento_id">Departamento</label>
    <input type="text" class="form-control" id="departamento_id" name="departamento_id" placeholder="ID del departamento">
  </div>

  <button type="submit" class="btn btn-primary">Enviar</button>
</form>

<form action="<?= BASE_URL ?>/logout" method="post" class="d-inline">
  <button type="submit" class="btn btn-danger">
    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
  </button>
</form>

