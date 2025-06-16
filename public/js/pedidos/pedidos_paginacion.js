// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
  
  // Función para cargar los pedidos vía AJAX
  function cargarPedidosPagina(page = 1) {
    const url = `./pedidos?page=${page}`; // URL con número de página

    // Se realiza la petición fetch a la URL
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(res => res.json()) // Se espera respuesta JSON
      .then(data => {
        const container = document.getElementById('pedidos-container');
        if (!container) return; // Si no existe el contenedor, termina

        // Construcción del HTML de la tabla y paginación
        let html = `
        <table class="table table-bordered table-striped">
          <thead class="thead-dark">
            <tr>
              <th>CLAPED</th>
              <th>CLAEMP</th>
              <th>NOMBRE CLIENTE</th>
              <th>LINEAS</th>
            </tr>
          </thead>
          <tbody>`;

        // Por cada pedido recibido, se construye una fila de la tabla
        data.pedidos.forEach((pedido, i) => {
          html += `
            <tr>
              <td>${pedido.CLAPED}</td>
              <td>${pedido.CLAEMP}</td>
              <td>${pedido.NOMCLI}</td>
              <td>
                <button class="btn btn-sm btn-info toggle-lines-btn show-btn" type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#collapseLines${i}"
                  aria-expanded="false"
                  aria-controls="collapseLines${i}">
                  Ver líneas
                </button>

                <button class="btn btn-sm btn-secondary toggle-lines-btn hide-btn d-none" type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#collapseLines${i}"
                  aria-expanded="true"
                  aria-controls="collapseLines${i}">
                  Ocultar líneas
                </button>
              </td>
            </tr>
            <tr class="collapse" id="collapseLines${i}">
              <td colspan="4">
                <div class="p-2">
                  <div class="mt-2 lineas-content" data-claped="${pedido.CLAPED}"></div>
                </div>
              </td>
            </tr>`;
        });

        html += `</tbody></table>`; // Cierre del tbody y tabla

        // Agrega la paginación
        html += `
        <nav aria-label="Paginación pedidos">
          <ul class="pagination">
            ${data.page > 1
              ? `<li class="page-item"><a href="#" class="page-link" data-page="${data.page - 1}">Anterior</a></li>`
              : `<li class="page-item disabled"><span class="page-link">Anterior</span></li>`}

            <li class="page-item disabled"><span class="page-link">Página ${data.page} de ${data.totalPaginas}</span></li>

            ${data.page < data.totalPaginas
              ? `<li class="page-item"><a href="#" class="page-link" data-page="${data.page + 1}">Siguiente</a></li>`
              : `<li class="page-item disabled"><span class="page-link">Siguiente</span></li>`}
          </ul>
        </nav>`;

        container.innerHTML = html; // Inserta el contenido HTML en el contenedor

        // Re-inicializa eventos colapsables y de botones después de cargar nuevo contenido
        inicializarEventosCollapse();
        inicializarToggleButtons();

        // Resetea el estado de carga y limpia el contenido de las líneas para que se puedan volver a cargar al expandir
        container.querySelectorAll('.lineas-content').forEach(div => {
          div.dataset.loaded = 'false'; // marca como no cargado
          div.dataset.page = '1';       // opcional, para paginar líneas
          div.innerHTML = '';           // limpia el contenido previo
        });
      })
      .catch(() => {
        alert('Error al cargar pedidos.'); // Muestra alerta si falla la carga
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
        cargarLineas(claped, container, 1, 5); // Carga las líneas del pedido
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
  // está definida en otro archivo (por ejemplo: pedidos_ver_lineas.js)
  // Si no está definida, se debe incluir o importar ese JS antes que este archivo

  // Captura los clics en los enlaces de paginación
  document.getElementById('pedidos-container').addEventListener('click', e => {
    if (e.target.matches('.page-link[data-page]')) {
      e.preventDefault(); // Previene navegación por defecto
      const page = parseInt(e.target.getAttribute('data-page'), 10);
      if (page) {
        cargarPedidosPagina(page); // Carga la página seleccionada
      }
    }
  });

  // Carga la primera página de pedidos al iniciar
  cargarPedidosPagina(1);
});
