<div class="m-4">
  <h2 class="titulo mb-4">Escandallos</h2>

  <button id="toggleBtn" class="btn btn-success">
    <i class="bi bi-caret-down-square"></i> Asignar materias primas
  </button>

  <form id="formulario_asignar_materias" class="form-section shadow p-3 mt-3" method="post" action="<?= BASE_URL ?>/" enctype="multipart/form-data" style="display:none;">
    <div class="row align-items-end g-2">
      <div class="col-md-10 position-relative">
        <label for="articulo_search" class="form-label">Buscar artículo</label>
        <input type="text" class="form-control" id="articulo_search" autocomplete="off" required>
        <input type="hidden" name="articulo_codigo" id="articulo_codigo">
        <div id="articulo_suggestions" class="list-group position-absolute mt-1" style="z-index: 1000;"></div>
      </div>
      <div class="col-md-2 d-grid">
        <button id="boton_asignar_materias" type="button" class="btn btn-dark">Asignar Materias Primas</button>
      </div>
    </div>

    <div id="alertas" class="mt-2" style="z-index: 1100;"></div>


    <div id="contenedor_materias" class="d-none m-4"></div>
  </form>
</div>

<div class="m-4">
  <!--<h2 class="titulo mb-4">Lista de artículos ID: <?= htmlspecialchars($_SESSION['id'] ?? '') ?></h2>-->

  <div id="articulos-container"></div>
</div>

<!-- Modal eliminar usuario -->
<?php include_once __DIR__ . '../../components/modals/escandallos/escandallos_eliminar_materia_modal.php'; ?>

<script>
      //Mostrar y ocultar el formulario
    const toggleBtn = document.getElementById('toggleBtn');
    const formulario_asignar_materias = document.getElementById('formulario_asignar_materias');

    toggleBtn.addEventListener('click', () => {
        if (formulario_asignar_materias.style.display === 'none' || formulario_asignar_materias.style.display === '') {
        formulario_asignar_materias.style.display = 'block';
        toggleBtn.innerHTML = '<i class="bi bi-caret-up-square"></i> Ocultar';
        toggleBtn.classList.remove('btn-success');
        toggleBtn.classList.add('btn-secondary');
        } else {
        formulario_asignar_materias.style.display = 'none';
        toggleBtn.innerHTML = '<i class="bi bi-caret-down-square"></i> Asignar materias primas';
        toggleBtn.classList.remove('btn-secondary');
        toggleBtn.classList.add('btn-success');
        }
    });
</script>

<script type="module" src="./public/js/escandallos/escandallos.js"></script>
<script type="module" src="./public/js/articulos/articulos.js"></script>
<script src="./public/js/articulos/articulos_ver_materias.js"></script>
<script src="./public/js/escandallos/escandallos_articulos_sugerencias.js"></script>
<script src="./public/js/escandallos/escandallos.js"></script>