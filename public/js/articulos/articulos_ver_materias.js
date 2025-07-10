// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {

  // Función para cargar los artículos vía AJAX
  window.cargarArticulosPagina = function(page = 1) {
        // Obtener valores del formulario de filtros
    const codigo = document.getElementById('filtrar_codigo').value.trim();
    const nombre = document.getElementById('filtrar_nombre').value.trim();
    const cantidad = document.getElementById('cantidad').value;

    // Construir query string con filtros
    const params = new URLSearchParams({
        page,
        codigo,
        nombre,
        cantidad
    });

    const url = `./articulos?${params.toString()}`; // URL con el número de página

    // Llamada fetch al backend para obtener los artículos en formato JSON
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById('articulos-container');
        if (!container) return;

        // Comienza a construir el HTML de la tabla de artículos
        let html = `
        <div class="table-responsive rounded-3 overflow-hidden shadow" style="background-color: #fff;">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
              <tr>
                <th>Código</th>
                <th>Artículo</th>
                <th>Sin orden</th>
                <th class="text-center">Materias Primas</th>
              </tr>
            </thead>
            <tbody>`;

        // Itera sobre los artículos recibidos
        data.articulos.forEach((articulo, i) => {
          const materiasCount = articulo.materias_count || 0; // Cantidad de materias primas
          const articulosSinOrdenCount = articulo.articulos_sin_orden_count || 0; // Cantidad de articulos sin orden

          html += `
            <tr>
              <td>${articulo.CODIGO}</td>
              <td>${articulo.NOMBRE}</td>
              <td>Sin orden</td>
              <td class="text-center">`;

          // Si tiene materias primas, muestra botones para expandir/cerrar
          if (materiasCount > 0) {
            html += `
              <button class="btn btn-sm btn-primary toggle-lines-btn show-btn" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapseLines${i}"
                aria-expanded="false"
                aria-controls="collapseLines${i}">
                <i class="bi bi-caret-down-square me-2"></i>Ver materias primas (${materiasCount})
              </button>
              <button class="btn btn-sm btn-secondary toggle-lines-btn hide-btn d-none" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapseLines${i}"
                aria-expanded="true"
                aria-controls="collapseLines${i}">
                <i class="bi bi-caret-up-square me-2"></i>Ocultar materias primas
              </button>
            `;
          } else {
            // Si no tiene materias primas, botón deshabilitado
            html += `
              <button class="btn btn-sm btn-danger" type="button" disabled>
                No tiene materias primas
              </button>
            `;
          }

          html += `
          </td>
          </tr>
          <tr class="collapse" id="collapseLines${i}">
            <td colspan="7">
              <div class="p-2">
                <div class="mt-2 materias-content" data-codigo="${articulo.CODIGO}" data-loaded="false"></div>
              </div>
            </td>
          </tr>`;
        });

        html += `
            </tbody>
          </table>
        </div>`;

        // Agrega la sección de paginación
        html += `
        <nav aria-label="Paginación artículos" class="mt-4">
          <ul class="pagination justify-content-center">
            ${data.page > 1
              ? `<li class="page-item"><a href="#" class="page-link" style="background-color: #111; color: white; border-color: #333;" data-page="${data.page - 1}">Anterior</a></li>`
              : `<li class="page-item disabled"><span class="page-link" style="background-color: #111; color: white; border-color: #333;">Anterior</span></li>`}

            <li class="page-item disabled">
              <span class="page-link" style="background-color: #111; color: white; border-color: #333;">
                Página ${data.page} de ${data.totalPaginas}
              </span>
            </li>

            ${data.page < data.totalPaginas
              ? `<li class="page-item"><a href="#" class="page-link" style="background-color: #111; color: white; border-color: #333;" data-page="${data.page + 1}">Siguiente</a></li>`
              : `<li class="page-item disabled"><span class="page-link" style="background-color: #111; color: white; border-color: #333;">Siguiente</span></li>`}
          </ul>
        </nav>`;

        // Inserta el HTML en el contenedor principal
        container.innerHTML = html;

        // Inicializa colapsables y botones de mostrar/ocultar
        inicializarEventosCollapse();
        inicializarToggleButtons();

        // Limpia y marca las materias primas como no cargadas aún
        container.querySelectorAll('.materias-content').forEach(div => {
          div.dataset.loaded = 'false';
          div.dataset.page = '1';
          div.innerHTML = '';
        });
      })
      .catch(() => {
        alert('Error al cargar artículos.');
      });
  }


  function inicializarEventosFiltros() {
    document.getElementById('filtrar_codigo').addEventListener('input', () => cargarArticulosPagina(1));
    document.getElementById('filtrar_nombre').addEventListener('input', () => cargarArticulosPagina(1));
    document.getElementById('cantidad').addEventListener('change', () => cargarArticulosPagina(1));
  }

  // Carga las materias primas de un artículo específico

  function cargarMateriasPaginadas(codigoPadre, container, page = 1, limit = 5) {
    const offset = (page - 1) * limit;
    container.dataset.page = page;

    container.innerHTML = `
      <div class="d-flex justify-content-center align-items-center">
        <div class="d-flex align-items-center gap-2">
          <img src="./public/images/maquina_coser.gif" width="80" alt="Cargando...">
          <span>Cargando materias primas...</span>
        </div>
      </div>`;

    fetch('./obtener_materias_por_codigo_paginadas', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `codigo=${encodeURIComponent(codigoPadre)}&page=${page}&limit=${limit}`
    })
    .then(res => res.json())
    .then(response => {
      const materias = response.materias;
      const totalPaginas = response.totalPaginas;

      if (!materias || materias.length === 0) {
        container.innerHTML = '<div class="text-muted">Este artículo no tiene materias primas.</div>';
        return;
      }

      let html = `
        <div class="table-responsive rounded-3 overflow-hidden shadow" style="background-color: #fff;">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Cantidad</th>
              </tr>
            </thead>
            <tbody>`;

      materias.forEach(materia => {
        html += `
          <tr>
            <td>${materia.CODIGO}</td>
            <td>${materia.NOMBRE}</td>
            <td>${materia.CANTIDAD}</td>
          </tr>`;
      });

      html += `
            </tbody>
          </table>
        </div>`;

      // Paginación estilo dark
      html += `
        <div class="d-flex justify-content-between align-items-center mt-2">
          ${page > 1
            ? `<button class="btn btn-sm btn-dark prev-materias">Anterior</button>`
            : `<span></span>`}
          <span class="text-muted">Página ${page} de ${totalPaginas}</span>
          ${page < totalPaginas
            ? `<button class="btn btn-sm btn-dark next-materias">Siguiente</button>`
            : `<span></span>`}
        </div>`;

      container.innerHTML = html;
      container.dataset.totalPages = totalPaginas;
      container.dataset.loaded = 'true';

      const prevBtn = container.querySelector('.prev-materias');
      const nextBtn = container.querySelector('.next-materias');

      if (prevBtn) {
        prevBtn.addEventListener('click', () => {
          cargarMateriasPaginadas(codigoPadre, container, page - 1, limit);
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', () => {
          cargarMateriasPaginadas(codigoPadre, container, page + 1, limit);
        });
      }
    })
    .catch(() => {
      container.innerHTML = '<div class="text-danger">Error al cargar materias primas.</div>';
    });
  }

  // Inicializa los eventos de colapso
  function inicializarEventosCollapse() {
    document.querySelectorAll('tr.collapse').forEach(collapseEl => {
      if (collapseEl.dataset.eventsAttached === 'true') return; // Evita múltiples bindings

      // Evento al mostrar el colapsable
      collapseEl.addEventListener('shown.bs.collapse', () => {
        const container = collapseEl.querySelector('.materias-content');
        if (container.dataset.loaded === 'true') return; // Ya cargado

        const codigo = container.dataset.codigo;
        cargarMateriasPaginadas(codigo, container, 1, 5);
      });

      collapseEl.dataset.eventsAttached = 'true';
    });
  }

  // Inicializa el comportamiento de los botones mostrar/ocultar materias
  function inicializarToggleButtons() {
    const toggleButtons = document.querySelectorAll('.toggle-lines-btn');

    toggleButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        const targetId = btn.getAttribute('data-bs-target');
        const target = document.querySelector(targetId);
        const row = btn.closest('tr');
        const showBtn = row.querySelector('.show-btn');
        const hideBtn = row.querySelector('.hide-btn');

        // Evento cuando se oculta el colapsable
        target.addEventListener('hidden.bs.collapse', () => {
          showBtn.classList.remove('d-none');
          hideBtn.classList.add('d-none');
        }, { once: true });

        // Evento cuando se muestra el colapsable
        target.addEventListener('shown.bs.collapse', () => {
          showBtn.classList.add('d-none');
          hideBtn.classList.remove('d-none');
        }, { once: true });
      });
    });
  }

  // Captura los clics en los enlaces de paginación
  document.getElementById('articulos-container').addEventListener('click', e => {
    if (e.target.matches('.page-link[data-page]')) {
      e.preventDefault();
      const page = parseInt(e.target.getAttribute('data-page'), 10);
      if (page) {
        cargarArticulosPagina(page);
      }
    }
  });

  // Carga inicial de la primera página de artículos al cargar el DOM
  cargarArticulosPagina(1);
  inicializarEventosFiltros();
});
