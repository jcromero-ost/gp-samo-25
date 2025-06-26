document.addEventListener('DOMContentLoaded', () => {
  // Función para cargar los pedidos de una página específica (por defecto la página 1)
  function cargarPedidosPagina(page = 1) {
    // Construir la URL con el parámetro de página
    const url = `./pedidos?page=${page}`;

    // Realizar la petición fetch con la cabecera para indicar que es AJAX
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(res => res.json()) // Convertir la respuesta a JSON
      .then(data => {
        const container = document.getElementById('pedidos-container'); // Contenedor donde se mostrarán los pedidos
        if (!container) return; // Si no existe el contenedor, salir de la función

        // Construcción del HTML para la tabla con los pedidos
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

        // Iterar sobre cada pedido recibido en el array data.pedidos
        data.pedidos.forEach((pedido, i) => {
          html += `
            <tr>
              <td>${pedido.CLAPED}</td>
              <td>${pedido.CLAEMP}</td>
              <td>${pedido.NOMCLI}</td>
              <td>
                <!-- Botón para mostrar las líneas del pedido -->
                <button class="btn btn-sm btn-info toggle-lines-btn show-btn" type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#collapseLines${i}"
                  aria-expanded="false"
                  aria-controls="collapseLines${i}">
                  Ver líneas
                </button>

                <!-- Botón para ocultar las líneas, inicialmente oculto con d-none -->
                <button class="btn btn-sm btn-secondary toggle-lines-btn hide-btn d-none" type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#collapseLines${i}"
                  aria-expanded="true"
                  aria-controls="collapseLines${i}">
                  Ocultar líneas
                </button>
              </td>
            </tr>
            <!-- Fila oculta que contendrá las líneas del pedido, colapsable -->
            <tr class="collapse" id="collapseLines${i}">
              <td colspan="4">
                <div class="p-2">
                  <!-- Contenedor para cargar las líneas, marcado con data-claped y data-loaded para control -->
                  <div class="mt-2 lineas-content" data-claped="${pedido.CLAPED}" data-loaded="false"></div>
                </div>
              </td>
            </tr>`;
        });

        html += `</tbody></table>`;

        // Añadir paginación con botones anterior y siguiente, y texto de página actual
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

        // Insertar el HTML construido dentro del contenedor en el DOM
        container.innerHTML = html;

        // Inicializar eventos para manejar el colapso de líneas y alternar botones
        inicializarEventosCollapse();
        inicializarToggleButtons();
      })
      .catch(() => {
        // Mostrar alerta si hubo error al cargar los pedidos
        alert('Error al cargar pedidos.');
      });
  }

  // Función que inicializa el evento para cargar líneas cuando se expande un collapse
  function inicializarEventosCollapse() {
    // Seleccionar todos los elementos tr con clase collapse (las filas colapsables)
    document.querySelectorAll('tr.collapse').forEach(collapseEl => {
      // Agregar evento cuando el collapse se muestre (se expanda)
      collapseEl.addEventListener('shown.bs.collapse', () => {
        const container = collapseEl.querySelector('.lineas-content'); // Contenedor donde se cargarán las líneas
        if (container.dataset.loaded === 'true') return; // Si ya se cargaron las líneas, no hacer nada

        const claped = container.dataset.claped; // Obtener el código CLAPED para cargar las líneas correspondientes
        window.cargarLineas(claped, container, 1, 5); // Llamar función global para cargar las líneas, paginadas (página 1, 5 líneas)
      }, { once: true }); // Evento se ejecuta solo una vez por collapse para evitar recargas repetidas
    });
  }

  // Función que inicializa los botones para mostrar y ocultar líneas, alternando su visibilidad
  function inicializarToggleButtons() {
    // Seleccionar todos los botones que alternan líneas
    document.querySelectorAll('.toggle-lines-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        const targetId = btn.getAttribute('data-bs-target'); // Obtener el id del collapse relacionado
        const target = document.querySelector(targetId); // Seleccionar el collapse
        const row = btn.closest('tr'); // Fila que contiene el botón
        const showBtn = row.querySelector('.show-btn'); // Botón para mostrar líneas
        const hideBtn = row.querySelector('.hide-btn'); // Botón para ocultar líneas

        // Cuando el collapse se oculta, mostrar el botón "Ver líneas" y ocultar "Ocultar líneas"
        target.addEventListener('hidden.bs.collapse', () => {
          showBtn.classList.remove('d-none');
          hideBtn.classList.add('d-none');
        }, { once: true });

        // Cuando el collapse se muestra, ocultar el botón "Ver líneas" y mostrar "Ocultar líneas"
        target.addEventListener('shown.bs.collapse', () => {
          showBtn.classList.add('d-none');
          hideBtn.classList.remove('d-none');
        }, { once: true });
      });
    });
  }

  // Delegación de evento click para manejar la paginación dentro del contenedor de pedidos
  document.getElementById('pedidos-container').addEventListener('click', e => {
    // Si el click fue en un enlace de paginación con atributo data-page
    if (e.target.matches('.page-link[data-page]')) {
      e.preventDefault(); // Prevenir comportamiento por defecto del enlace
      const page = parseInt(e.target.getAttribute('data-page'), 10); // Obtener número de página
      if (page) cargarPedidosPagina(page); // Cargar la página seleccionada
    }
  });

  // Carga inicial de la primera página de pedidos al cargar el DOM
  cargarPedidosPagina(1);
});
