document.addEventListener('DOMContentLoaded', () => {
  // Definimos la función global cargarLineas para cargar las líneas de un pedido
  window.cargarLineas = function(claped, container, page = 1, limit = 5) {
    const offset = (page - 1) * limit;
    container.dataset.page = page;

    // Mostrar animación de carga
    container.innerHTML = `
      <div class="d-flex justify-content-center align-items-center">
        <div class="d-flex align-items-center gap-2">
          <img src="./public/images/maquina_coser.gif" width="80" alt="Cargando...">
          <span>Cargando líneas...</span>
        </div>
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
        container.innerHTML = '<p class="text-muted">Este pedido no contiene líneas.</p>';
        return;
      }

      // Tabla con formato similar al de artículos
      let html = `
      <div class="table-responsive rounded-3 overflow-hidden shadow" style="background-color: #fff;">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>CLAPED</th>
              <th>Código</th>
              <th>Descripción</th>
              <th>Cantidad</th>
              <th>Precio</th>
            </tr>
          </thead>
          <tbody>`;

      data.forEach(l => {
        html += `
          <tr>
            <td>${l.CLAPED}</td>
            <td>${l.CODIGO}</td>
            <td>${l.LINDESC}</td>
            <td>${l.CANTIDAD}</td>
            <td>${l.PRECIO}</td>
          </tr>`;
      });

      html += `
          </tbody>
        </table>
      </div>`;

      // Controles de paginación internos
      html += `
        <div class="d-flex justify-content-between align-items-center mt-2">
          ${page > 1
            ? `<button class="btn btn-sm btn-outline-primary prev-lineas">Anterior</button>`
            : `<span></span>`}

          <span class="text-muted">Página ${page} de ${totalPages}</span>

          ${page < totalPages
            ? `<button class="btn btn-sm btn-outline-primary next-lineas">Siguiente</button>`
            : `<span></span>`}
        </div>`;

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
