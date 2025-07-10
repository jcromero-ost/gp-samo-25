document.addEventListener('DOMContentLoaded', () => {
  function cargarPedidosPagina(page = 1) {
const ejercicio = document.getElementById('ejercicio')?.value || '';
const orden = document.getElementById('orden_fabricacion_select')?.value || '';

const url = `./pedidos?page=${page}&ejercicio=${encodeURIComponent(ejercicio)}&orden_fabricacion_select=${encodeURIComponent(orden)}`;


  const container = document.getElementById('pedidos-container');
  if (!container) return;

      /*
      container.innerHTML = `
        <div class="d-flex justify-content-center align-items-center py-5">
          <div class="d-flex align-items-center gap-3">
            <img src="./public/images/maquina_coser_2.gif" width="550" alt="Cargando...">
          </div>
        </div>
      `;
    */
    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById('pedidos-container');
        if (!container) return;

        let html = `
        <div class="table-responsive rounded-3 overflow-hidden shadow" style="background-color: #fff;">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
              <tr>
                <th>Número</th>
                <th>Nombre del cliente</th>
                <th>Dirección del cliente</th>
                <th>Localidad del cliente</th>
                <th>Fecha</th>
                <th class="text-center">Líneas</th>
              </tr>
            </thead>
            <tbody>`;

            if (data.pedidos.length > 0) {
              data.pedidos.forEach((pedido, i) => {
  const materiasCount = pedido.materias_count || 0;

  html += `
    <tr>
      <td>${pedido.NUMERO}</td>
      <td>${pedido.NOMCLI}</td>
      <td>${pedido.DIRCLI}</td>
      <td>${pedido.LOCCLI}</td>
      <td>${pedido.FECHA}</td>
      <td class="text-center">`;

  if (materiasCount > 0) {
    html += `
        <button class="btn btn-sm btn-primary toggle-lines-btn show-btn" type="button"
          data-bs-toggle="collapse"
          data-bs-target="#collapseLines${i}"
          aria-expanded="false"
          aria-controls="collapseLines${i}">
          <i class="bi bi-caret-down-square me-2"></i>Ver líneas (${materiasCount})
        </button>
        <button class="btn btn-sm btn-secondary toggle-lines-btn hide-btn d-none" type="button"
          data-bs-toggle="collapse"
          data-bs-target="#collapseLines${i}"
          aria-expanded="true"
          aria-controls="collapseLines${i}">
          <i class="bi bi-caret-up-square me-2"></i>Ocultar líneas
        </button>`;
  } else {
    html += `
        <button class="btn btn-sm btn-danger" type="button" disabled>
          No tiene líneas
        </button>`;
  }

  html += `
      </td>
    </tr>
    <tr class="collapse" id="collapseLines${i}">
      <td colspan="8">
        <div class="p-2">
          <div class="mt-2 lineas-content" data-claped="${pedido.CLAPED}" data-loaded="false"></div>
        </div>
      </td>
    </tr>`;
});

            } else {
              html += `
                <tr>
                  <td colspan="8" class="text-center py-4">No hay pedidos para el ejercicio seleccionado</td>
                </tr>`;
            }

        html += `
            </tbody>
          </table>
        </div>`;

        // Agregar la paginación estilizada igual que en artículos
        html += `
        <nav aria-label="Paginación pedidos" class="mt-4">
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
      })
      .catch(() => {
        alert('Error al cargar pedidos.');
      });
  }

  function inicializarEventosCollapse() {
    document.querySelectorAll('tr.collapse').forEach(collapseEl => {
      collapseEl.addEventListener('shown.bs.collapse', () => {
        const container = collapseEl.querySelector('.lineas-content');
        if (container.dataset.loaded === 'true') return;

        const claped = container.dataset.claped;
        const orden = document.getElementById('orden_fabricacion_select')?.value || '';
        window.cargarLineas(claped, container, 1, 5, orden);

      }, { once: true });
    });
  }

  function inicializarToggleButtons() {
    document.querySelectorAll('.toggle-lines-btn').forEach(btn => {
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

  document.getElementById('pedidos-container').addEventListener('click', e => {
    if (e.target.matches('.page-link[data-page]')) {
      e.preventDefault();
      const page = parseInt(e.target.getAttribute('data-page'), 10);
      if (page) cargarPedidosPagina(page);
    }
  });

  cargarPedidosPagina(1);
});
