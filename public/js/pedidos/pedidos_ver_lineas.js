document.addEventListener('DOMContentLoaded', () => {
  // Definimos la función global cargarLineas para cargar las líneas de un pedido
  window.cargarLineas = function(claped, container, page = 1, limit = 5) {
    // Calcular el offset según la página y límite (para paginación)
    const offset = (page - 1) * limit;
    container.dataset.page = page; // Guardar la página actual en un atributo data

    // Mostrar animación de carga dentro del contenedor mientras se obtienen datos
    container.innerHTML = `
      <div class="d-flex justify-content-center align-items-center">
        <div class="d-flex align-items-center gap-2">
          <img src="./public/images/maquina_coser.gif" width="80" alt="Cargando...">
          <span>Cargando líneas...</span>
        </div>
      </div>`;

    // Realizar petición POST para obtener las líneas del pedido según CLAPED, offset y limit
    fetch('./pedidos_ver_lineas', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      // Enviar los parámetros codificados en el cuerpo de la petición
      body: `CLAPED=${encodeURIComponent(claped)}&offset=${offset}&limit=${limit}`
    })
    .then(res => res.json()) // Convertir la respuesta a JSON
    .then(response => {
      const data = response.data; // Array con las líneas recibidas
      const total = response.total; // Total de líneas disponibles para ese pedido
      const totalPages = Math.ceil(total / limit); // Calcular total de páginas

      // Si no hay líneas para mostrar, informar al usuario y salir
      if (!data || data.length === 0) {
        container.innerHTML = '<p>No hay líneas para este pedido.</p>';
        return;
      }

      // Construir tabla HTML para mostrar las líneas
      let html = `<table class="table table-sm table-striped mb-0">
                    <thead><tr>
                      <th>Código</th><th>Descripción</th><th>Cantidad</th><th>Precio</th>
                    </tr></thead><tbody>`;

      // Agregar una fila por cada línea con sus datos
      data.forEach(l => {
        html += `<tr>
                   <td>${l.CODIGO}</td>
                   <td>${l.LINDESC}</td>
                   <td>${l.CANTIDAD}</td>
                   <td>${l.PRECIO}</td>
                 </tr>`;
      });

      html += `</tbody></table>`;

      // Contenedor para los botones de paginación y el texto de página
      html += `<div class="d-flex justify-content-between align-items-center mt-2">`;

      // Botón "Anterior" solo si no es la primera página
      if (page > 1) {
        html += `<button class="btn btn-sm btn-outline-primary prev-lineas">Anterior</button>`;
      } else {
        html += `<span></span>`; // Espacio vacío para mantener el diseño
      }

      // Texto que muestra la página actual y total de páginas
      html += `<span class="text-muted">Página ${page} de ${totalPages}</span>`;

      // Botón "Siguiente" solo si no es la última página
      if (page < totalPages) {
        html += `<button class="btn btn-sm btn-outline-primary next-lineas">Siguiente</button>`;
      } else {
        html += `<span></span>`; // Espacio vacío para mantener el diseño
      }

      html += `</div>`;

      // Insertar el HTML generado en el contenedor
      container.innerHTML = html;
      container.dataset.totalPages = totalPages; // Guardar total de páginas en atributo data
      container.dataset.loaded = 'true'; // Marcar como cargado para evitar recargas innecesarias

      // Seleccionar los botones de paginación si existen
      const prevBtn = container.querySelector('.prev-lineas');
      const nextBtn = container.querySelector('.next-lineas');

      // Agregar evento click al botón "Anterior" para cargar la página anterior
      if (prevBtn) {
        prevBtn.addEventListener('click', () => {
          window.cargarLineas(claped, container, page - 1, limit);
        });
      }

      // Agregar evento click al botón "Siguiente" para cargar la página siguiente
      if (nextBtn) {
        nextBtn.addEventListener('click', () => {
          window.cargarLineas(claped, container, page + 1, limit);
        });
      }
    })
    .catch(() => {
      // Mostrar mensaje de error si falla la carga de líneas
      container.innerHTML = `<p class="text-danger">Error al cargar líneas.</p>`;
    });
  };
});
