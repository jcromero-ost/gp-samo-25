document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('tr.collapse').forEach(collapseRow => {
    collapseRow.addEventListener('shown.bs.collapse', () => {
      const container = collapseRow.querySelector('.lineas-content');
      if (container.dataset.loaded === 'true') return;

      const claped = container.dataset.claped;
      container.innerHTML = `
        <div class="d-flex align-items-center gap-2">
          <img src="./public/images/maquina_coser.gif" width="30" alt="Cargando...">
          <span class="text-muted">Cargando líneas...</span>
        </div>`;

      fetch('./pedidos_ver_lineas', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'CLAPED=' + encodeURIComponent(claped)
      })
      .then(res => res.json())
      .then(data => {
        if (data.error) {
          container.innerHTML = `<p class="text-danger">${data.error}</p>`;
          return;
        }
        if (data.length === 0) {
          container.innerHTML = '<p>No hay líneas para este pedido.</p>';
          return;
        }

        let html = `<table class="table table-sm table-striped mb-0">
                      <thead><tr>
                        <th>Código</th><th>Descripción</th>
                        <th>Cantidad</th><th>Precio</th>
                      </tr></thead><tbody>`;

        data.forEach(l => {
          html += `<tr>
                     <td>${l.CODIGO}</td>
                     <td>${l.LINDESC}</td>
                     <td>${l.CANTIDAD}</td>
                     <td>${l.PRECIO}</td>
                   </tr>`;
        });
        html += '</tbody></table>';
        container.innerHTML = html;
        container.dataset.loaded = 'true';
      })
      .catch(() => {
        container.innerHTML = `<p class="text-danger">Error al cargar líneas.</p>`;
      });
    });
  });

  const toggleButtons = document.querySelectorAll('.toggle-lines-btn');

  toggleButtons.forEach(btn => {
    btn.addEventListener('click', function () {
      const targetId = btn.getAttribute('data-bs-target');
      const target = document.querySelector(targetId);
      const row = btn.closest('tr');
      const showBtn = row.querySelector('.show-btn');
      const hideBtn = row.querySelector('.hide-btn');

      const isCollapsed = target.classList.contains('show');

      // Toggle visibility after Bootstrap collapse animation ends
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
});
