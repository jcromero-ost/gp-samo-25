<h2>Iniciar Sesión ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>
<form method="post" action="<?= BASE_URL ?>/login">
  <div class="form-group">
    <label for="email">Email address</label>
    <input 
      type="email" 
      class="form-control" 
      id="email" 
      name="email" 
      aria-describedby="emailHelp" 
      placeholder="Enter email"
    >
    <small id="emailHelp" class="form-text text-muted">Nunca compartiremos tu correo con nadie más.</small>
  </div>

  <div class="form-group">
    <label for="passwd">Password</label>
    <input 
      type="password" 
      class="form-control" 
      id="passwd" 
      name="passwd" 
      placeholder="Password"
    >
  </div>

  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="remember">
    <label class="form-check-label" for="remember">Recuérdame</label>
  </div>

  <button type="submit" class="btn btn-primary">Enviar</button>
</form>

