// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
  
  // Función para cargar los clientes vía AJAX
  function cargarClientesPagina(page = 1) {
    const url = `./clientes?page=${page}`; // URL con número de página

    // Se realiza la petición fetch a la URL
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(res => res.json()) // Se espera respuesta JSON
      .then(data => {
        const container = document.getElementById('clientes-container');
        if (!container) return; // Si no existe el contenedor, termina

        // Construcción del HTML de la tabla y paginación
        let html = `
        <table class="table table-bordered table-striped">
          <thead class="thead-dark">
            <tr>
                <th>CLACLI</th>
                <th>Codigo</th>
                <th>Nombre</th>
                <th>Direccion</th>
                <th>Localidad</th>
                <th>Provincia</th>
                <th>Postal</th>
                <th>Pais</th>
                <th>Telefono</th>
            </tr>
          </thead>
          <tbody>`;

        // Por cada cliente recibido, se construye una fila de la tabla
        data.clientes.forEach((cliente, i) => {
            html += `
                <tr>
                    <td>${cliente.CLACLI}</td>
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

        html += `</tbody></table>`; // Cierre del tbody y tabla

        // Agrega la paginación
        html += `
        <nav aria-label="Paginación clientes">
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
        alert('Error al cargar clientes.'); // Muestra alerta si falla la carga
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
