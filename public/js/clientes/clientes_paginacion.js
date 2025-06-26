// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
  
  // Función para cargar los clientes vía AJAX
function cargarClientesPagina(page = 1) {
  const url = `./clientes?page=${page}`;

  fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById('clientes-container');
      if (!container) return;

      let html = `
      <div class="table-responsive rounded-3 overflow-hidden shadow" style="background-color: #fff;">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-dark">
            <tr>
              <th>Código</th>
              <th>Nombre</th>
              <th>Dirección</th>
              <th>Localidad</th>
              <th>Provincia</th>
              <th>Postal</th>
              <th>País</th>
              <th>Teléfono</th>
            </tr>
          </thead>
          <tbody>`;

      data.clientes.forEach(cliente => {
        html += `
          <tr>
            <td>${cliente.CODIGO}</td>
            <td>${cliente.NOMBRE}</td>
            <td>${cliente.DIRECCION ?? ''}</td>
            <td>${cliente.LOCALIDAD ?? ''}</td>
            <td>${cliente.PROVINCIA ?? ''}</td>
            <td>${cliente.POSTAL ?? ''}</td>
            <td>${cliente.PAIS ?? ''}</td>
            <td>${cliente.TELEFONO ?? ''}</td>
          </tr>`;
      });

      html += `
          </tbody>
        </table>
      </div>`;

      // Paginación con clases y estructura ajustadas
      html += `
        <nav aria-label="Paginación clientes" class="mt-4">
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

      // Re-inicializa eventos si tienes (como en tu código original)
      inicializarEventosCollapse();
      inicializarToggleButtons();

      container.querySelectorAll('.lineas-content').forEach(div => {
        div.dataset.loaded = 'false';
        div.dataset.page = '1';
        div.innerHTML = '';
      });
    })
    .catch(() => {
      alert('Error al cargar clientes.');
    });
}


  // Inicializa los eventos de colapso para las filas de líneas
  function inicializarEventosCollapse() {
    document.querySelectorAll('tr.collapse').forEach(collapseEl => {
      // Evita adjuntar múltiples veces
      if (collapseEl.dataset.eventsAttached === 'true') return;

      // Evento cuando se expande el colapso
      collapseEl.addEventListener('shown.bs.collapse', () => {
        const container = collapseEl.querySelector('.lineas-content');
        if (container.dataset.loaded === 'true') return; // Si ya fue cargado, no hace nada

        const claped = container.dataset.claped;
        cargarLineas(claped, container, 1, 5); // Carga las líneas del clientes
      });

      collapseEl.dataset.eventsAttached = 'true'; // Marca como con evento adjunto
    });
  }

  // Inicializa los botones de mostrar/ocultar líneas
  function inicializarToggleButtons() {
    const toggleButtons = document.querySelectorAll('.toggle-lines-btn');

    toggleButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        const targetId = btn.getAttribute('data-bs-target');
        const target = document.querySelector(targetId);
        const row = btn.closest('tr');
        const showBtn = row.querySelector('.show-btn');
        const hideBtn = row.querySelector('.hide-btn');

        // Evento al colapsar (ocultar)
        target.addEventListener('hidden.bs.collapse', () => {
          showBtn.classList.remove('d-none');
          hideBtn.classList.add('d-none');
        }, { once: true });

        // Evento al expandir (mostrar)
        target.addEventListener('shown.bs.collapse', () => {
          showBtn.classList.add('d-none');
          hideBtn.classList.remove('d-none');
        }, { once: true });
      });
    });
  }

  // IMPORTANTE:
  // Se asume que la función cargarLineas(claped, container, page, limit)
  // está definida en otro archivo
  // Si no está definida, se debe incluir o importar ese JS antes que este archivo

  // Captura los clics en los enlaces de paginación
  document.getElementById('clientes-container').addEventListener('click', e => {
    if (e.target.matches('.page-link[data-page]')) {
      e.preventDefault(); // Previene navegación por defecto
      const page = parseInt(e.target.getAttribute('data-page'), 10);
      if (page) {
        cargarClientesPagina(page); // Carga la página seleccionada
      }
    }
  });

  // Carga la primera página de clientes al iniciar
  cargarClientesPagina(1);
});
