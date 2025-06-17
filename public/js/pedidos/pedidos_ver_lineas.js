document.addEventListener('DOMContentLoaded', () => {
  window.cargarLineas = function(claped, container, page = 1, limit = 5) {
    const offset = (page - 1) * limit;
    container.dataset.page = page;

    container.innerHTML = `
      <div class="d-flex align-items-center gap-2">
        <img src="./public/images/maquina_coser.gif" width="30" alt="Cargando...">
        <span class="text-muted">Cargando líneas...</span>
      </div>`;

    fetch('./pedidos_ver_lineas', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `CLAPED=${encodeURIComponent(claped)}&offset=${offset}&limit=${limit}`
    })
    .then(res => res.json())
    .then(response => {
      const data = response.data;
      const total = response.total;
      const totalPages = Math.ceil(total / limit);

      if (!data || data.length === 0) {
        container.innerHTML = '<p>No hay líneas para este pedido.</p>';
        return;
      }

      let html = `<table class="table table-sm table-striped mb-0">
                    <thead><tr>
                      <th>Código</th><th>Descripción</th><th>Cantidad</th><th>Precio</th>
                    </tr></thead><tbody>`;

      data.forEach(l => {
        html += `<tr>
                   <td>${l.CODIGO}</td>
                   <td>${l.LINDESC}</td>
                   <td>${l.CANTIDAD}</td>
                   <td>${l.PRECIO}</td>
                 </tr>`;
      });

      html += `</tbody></table>`;

      html += `<div class="d-flex justify-content-between align-items-center mt-2">`;

      if (page > 1) {
        html += `<button class="btn btn-sm btn-outline-primary prev-lineas">Anterior</button>`;
      } else {
        html += `<span></span>`;
      }

      html += `<span class="text-muted">Página ${page} de ${totalPages}</span>`;

      if (page < totalPages) {
        html += `<button class="btn btn-sm btn-outline-primary next-lineas">Siguiente</button>`;
      } else {
        html += `<span></span>`;
      }

      html += `</div>`;

      container.innerHTML = html;
      container.dataset.totalPages = totalPages;
      container.dataset.loaded = 'true';

      const prevBtn = container.querySelector('.prev-lineas');
      const nextBtn = container.querySelector('.next-lineas');

      if (prevBtn) {
        prevBtn.addEventListener('click', () => {
          window.cargarLineas(claped, container, page - 1, limit);
        });
      }

      if (nextBtn) {
        nextBtn.addEventListener('click', () => {
          window.cargarLineas(claped, container, page + 1, limit);
        });
      }
    })
    .catch(() => {
      container.innerHTML = `<p class="text-danger">Error al cargar líneas.</p>`;
    });
  };
});
