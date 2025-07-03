<div class="container mt-4">
  <h2 class="titulo mb-5 text-center">Inicio</h2>
  <div class="row g-4">

    <!-- Clientes -->
    <div class="col-md-4">
      <div class="card bg-dark text-white shadow-lg h-100 border-primary-custom">
        <div class="card-body text-center">
          <i class="bi bi-people-fill" style="font-size: 5rem;"></i>
          <h4 class="fw-bold mt-3">Clientes</h4>
          <div class="d-grid gap-2 mt-4">
            <a href="<?= BASE_URL ?>/clientes" class="btn btn-primary-custom btn-lg">Ver Clientes</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Artículos -->
    <div class="col-md-4">
      <div class="card bg-dark text-white shadow-lg h-100 border-primary-custom">
        <div class="card-body text-center">
          <i class="bi bi-box-seam" style="font-size: 5rem;"></i>
          <h4 class="fw-bold mt-3">Artículos</h4>
          <div class="d-grid gap-2 mt-4">
            <a href="<?= BASE_URL ?>/escandallos_crear" class="btn btn-primary-custom btn-lg">Ver Escandallos</a>
            <a href="<?= BASE_URL ?>/articulos" class="btn btn-primary-custom btn-lg">Ver Artículos</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Pedidos -->
    <div class="col-md-4">
      <div class="card bg-dark text-white shadow-lg h-100 border-primary-custom">
        <div class="card-body text-center">
          <i class="bi bi-receipt" style="font-size: 5rem;"></i>
          <h4 class="fw-bold mt-3">Pedidos</h4>
          <div class="d-grid gap-2 mt-4">
            <a href="<?= BASE_URL ?>/pedidos" class="btn btn-primary-custom btn-lg">Ver Pedidos</a>
          </div>
        </div>
      </div>
    </div>    
  </div>
</div>
