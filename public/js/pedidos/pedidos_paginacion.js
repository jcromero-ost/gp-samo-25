document.addEventListener('DOMContentLoaded', () => {
  function cargarPedidosPagina(page = 1) {
    const url = `./pedidos?page=${page}`;

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(res => res.json())
      .then(data => {
        const container = document.getElementById('pedidos-container');
        if (!container) return;

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
                  <div class="mt-2 lineas-content" data-claped="${pedido.CLAPED}" data-loaded="false"></div>
                </div>
              </td>
            </tr>`;
        });

        html += `</tbody></table>`;

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
        window.cargarLineas(claped, container, 1, 5);
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
