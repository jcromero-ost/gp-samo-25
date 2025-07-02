// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
  
    // Función para cargar los ejercicios vía AJAX desde el servidor
    function cargarEjerciciosPagina(page = 1) {
        const url = `./ejercicios?page=${page}`; // Construye la URL con el número de página

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }) // Realiza una solicitud AJAX
        .then(res => res.json()) // Parsea la respuesta como JSON
        .then(data => {
            const container = document.getElementById('ejercicios-container'); // Contenedor de los datos
            if (!container) return; // Si no existe el contenedor, salir

            // Comienza a construir el HTML de la tabla de ejercicios
            let html = `
        <div class="table-responsive rounded-3 overflow-hidden shadow" style="background-color: #fff;">
            <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Desde</th>
                <th>Hasta</th>
                </tr>
            </thead>
            <tbody>`;

            // Itera sobre los ejercicios recibidos y agrega filas a la tabla
            data.ejercicios.forEach(ejercicio => {
            html += `
            <tr>
                <td>${ejercicio.CLAEJE}</td>
                <td>${ejercicio.NOMEJE}</td>
                <td>${ejercicio.DESDE}</td>
                <td>${ejercicio.HASTA}</td>
            </tr>`;
            });

            // Cierra la tabla
            html += `
            </tbody>
            </table>
        </div>`;

            // Agrega el componente de paginación
            html += `
            <nav aria-label="Paginación ejercicios" class="mt-4">
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

            // Inserta todo el HTML generado en el contenedor
            container.innerHTML = html;

            // Inicializa comportamientos interactivos (si aplican líneas colapsables)
            inicializarEventosCollapse();
            inicializarToggleButtons();

            // Limpia el estado de carga previo de líneas
            container.querySelectorAll('.lineas-content').forEach(div => {
            div.dataset.loaded = 'false';
            div.dataset.page = '1';
            div.innerHTML = '';
            });
        })
        .catch(() => {
            // Muestra alerta si ocurre error en la carga de datos
            alert('Error al cargar ejercicios');
        });
    }

  function formatearFecha(fechaStr) {
    if (!fechaStr || fechaStr.length !== 8) return '';
      const anio = fechaStr.substring(0, 4);
      const mes = fechaStr.substring(4, 6);
      const dia = fechaStr.substring(6, 8);
      return `${dia}/${mes}/${anio}`; // o `${anio}-${mes}-${dia}` si prefieres
  }

  // Inicializa eventos de colapso de Bootstrap para líneas de ejercicios
  function inicializarEventosCollapse() {
    document.querySelectorAll('tr.collapse').forEach(collapseEl => {
      // Evita múltiples registros del mismo evento
      if (collapseEl.dataset.eventsAttached === 'true') return;

      // Cuando el colapsable se expande, carga las líneas si no están cargadas aún
      collapseEl.addEventListener('shown.bs.collapse', () => {
        const container = collapseEl.querySelector('.lineas-content');
        if (container.dataset.loaded === 'true') return;

        const claped = container.dataset.claped; // Código del ejercicio
        cargarLineas(claped, container, 1, 5); // Función que debe estar definida en otro archivo
      });

      collapseEl.dataset.eventsAttached = 'true'; // Marca este colapsable como con eventos adjuntos
    });
  }

  // Inicializa los botones de mostrar/ocultar líneas asociadas al ejercicio
  function inicializarToggleButtons() {
    const toggleButtons = document.querySelectorAll('.toggle-lines-btn');

    toggleButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        const targetId = btn.getAttribute('data-bs-target'); // ID del colapsable
        const target = document.querySelector(targetId);
        const row = btn.closest('tr');
        const showBtn = row.querySelector('.show-btn');
        const hideBtn = row.querySelector('.hide-btn');

        // Cuando se oculta el colapsable, se alternan los botones
        target.addEventListener('hidden.bs.collapse', () => {
          showBtn.classList.remove('d-none');
          hideBtn.classList.add('d-none');
        }, { once: true });

        // Cuando se muestra el colapsable, se alternan los botones
        target.addEventListener('shown.bs.collapse', () => {
          showBtn.classList.add('d-none');
          hideBtn.classList.remove('d-none');
        }, { once: true });
      });
    });
  }

  // NOTA IMPORTANTE:
  // Se asume que la función cargarLineas(claped, container, page, limit)
  // está definida en otro archivo JS cargado previamente

  // Captura clics en enlaces de paginación dentro del contenedor de ejercicios
  document.getElementById('ejercicios-container').addEventListener('click', e => {
    if (e.target.matches('.page-link[data-page]')) {
      e.preventDefault(); // Previene comportamiento por defecto del enlace
      const page = parseInt(e.target.getAttribute('data-page'), 10); // Obtiene número de página
      if (page) {
        cargarEjerciciosPagina(page); // Llama a la función para cargar la nueva página
      }
    }
  });

  // Carga inicial de la primera página al cargar el documento
  cargarEjerciciosPagina(1);
});
