// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {

  // Función para cargar los artículos vía AJAX
  function cargarArticulosPagina(page = 1) {
    const url = `./articulos?page=${page}`;

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById('articulos-container');
        if (!container) return;

        let html = `
        <div class="table-responsive rounded-3 overflow-hidden shadow" style="background-color: #fff;">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
              <tr>
                <th>CLAART</th>
                <th>Código</th>
                <th>Nombre</th>
                <th>Materias Primas</th>
              </tr>
            </thead>
            <tbody>`;

        data.articulos.forEach((articulo, i) => {
          html += `
            <tr>
              <td>${articulo.CLAART}</td>
              <td>${articulo.CODIGO}</td>
              <td>${articulo.NOMBRE}</td>
              <td>
                <button class="btn btn-sm btn-info toggle-lines-btn show-btn" type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#collapseLines${i}"
                  aria-expanded="false"
                  aria-controls="collapseLines${i}">
                  Ver materias primas
                </button>
                <button class="btn btn-sm btn-secondary toggle-lines-btn hide-btn d-none" type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#collapseLines${i}"
                  aria-expanded="true"
                  aria-controls="collapseLines${i}">
                  Ocultar materias primas
                </button>
              </td>
            </tr>
            <tr class="collapse" id="collapseLines${i}">
              <td colspan="4">
                <div class="p-2">
                  <div class="mt-2 materias-content" data-codpadre="${articulo.CODIGO}" data-loaded="false"></div>
                </div>
              </td>
            </tr>`;
        });

        html += `
            </tbody>
          </table>
        </div>`;

        // Paginación con estilo oscuro
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

        container.innerHTML = html;

        inicializarEventosCollapse();
        inicializarToggleButtons();

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

  // Carga las materias primas de un artículo
  function cargarMaterias(codigoPadre, container, page = 1, limit = 5) {
    fetch('./obtener_materias_por_codigo', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `codigo=${encodeURIComponent(codigoPadre)}`
    })
    .then(res => res.json())
    .then(data => {
      container.dataset.loaded = 'true';

      if (!Array.isArray(data) || data.length === 0) {
        container.innerHTML = '<div class="text-muted">Este artículo no tiene materias primas.</div>';
        return;
      }

      let html = `
        <div class="table-responsive rounded-3 overflow-hidden shadow-sm" style="background-color: #fff;">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Código</th>
                <th>Nombre</th>
              </tr>
            </thead>
            <tbody>`;

      data.forEach(materia => {
        html += `
              <tr>
                <td>${materia.CODIGO}</td>
                <td>${materia.NOMBRE}</td>
              </tr>`;
      });

      html += `
            </tbody>
          </table>
        </div>`;

      container.innerHTML = html;

    })
    .catch(err => {
      console.error('Error al cargar materias primas:', err);
      container.innerHTML = '<div class="text-danger">Error al cargar materias primas.</div>';
    });
  }

  // Inicializa los eventos de colapso
  function inicializarEventosCollapse() {
    document.querySelectorAll('tr.collapse').forEach(collapseEl => {
      if (collapseEl.dataset.eventsAttached === 'true') return;

      collapseEl.addEventListener('shown.bs.collapse', () => {
        const container = collapseEl.querySelector('.materias-content');
        if (container.dataset.loaded === 'true') return;

        const codpadre = container.dataset.codpadre;
        cargarMaterias(codpadre, container, 1, 5);
      });

      collapseEl.dataset.eventsAttached = 'true';
    });
  }

  // Inicializa los botones de mostrar/ocultar materias primas
  function inicializarToggleButtons() {
    const toggleButtons = document.querySelectorAll('.toggle-lines-btn');

    toggleButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        const targetId = btn.getAttribute('data-bs-target');
        const target = document.querySelector(targetId);
        const row = btn.closest('tr');
        const showBtn = row.querySelector('.show-btn');
        const hideBtn = row.querySelector('.hide-btn');

        target.addEventListener('hidden.bs.collapse', () => {
          showBtn.classList.remove('d-none');
          hideBtn.classList.add('d-none');
        }, { once: true });

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

  // Carga inicial de la primera página de artículos
  cargarArticulosPagina(1);
});
