document.addEventListener('DOMContentLoaded', function () {
    // Definimos las variables del modal
    const modal_ver = document.getElementById('modal_ver_lineas_pedido');
    const modal_ver_lineas_pedido = new bootstrap.Modal(modal_ver);

    // Definimos una variable para guardar la id y para mostrarlo en el modal
    const id_mostrar = document.getElementById('id_mostrar');

    // Cuando se pulse el boton, se realizan las operaciones
    document.querySelectorAll('.btn-ver-lineas').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id; // Guardamos la id pasada por el boton en una variable
  
        id_mostrar.textContent = id; // Asignamos la id para mostrarla en el modal
  
        modal_ver_lineas_pedido.show(); //Mostramos el modal
      });
    });

    //--------------Ver lineas---------------------------------
// Seleccionamos todos los botones con la clase .btn-ver-lineas
const botones = document.querySelectorAll('.btn-ver-lineas');

// Seleccionamos el contenedor del cuerpo del modal donde mostraremos las líneas del pedido
const modalBody = document.getElementById('lineas_contenido');

// Seleccionamos el elemento donde mostraremos el ID del pedido
const idMostrar = document.getElementById('id_mostrar');

// Recorremos cada botón para agregarle un evento 'click'
botones.forEach(btn => {
  btn.addEventListener('click', () => {
    // Obtenemos el valor del atributo data-id del botón clicado (el ID del pedido)
    const claped = btn.getAttribute('data-id');

    // Mostramos el ID del pedido en el modal
    idMostrar.textContent = claped;

    // Mostramos mensaje temporal mientras se cargan las líneas
    modalBody.innerHTML = `
      <div class="d-flex align-items-center gap-2">
        <div class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></div>
        <span class="text-muted">Cargando líneas...</span>
      </div>
    `;

    // Hacemos una petición POST al backend usando fetch
    fetch('./pedidos_ver_lineas', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, // Indicamos el tipo de datos
      body: 'CLAPED=' + encodeURIComponent(claped) // Enviamos el ID del pedido como parámetro
    })
    .then(response => response.json()) // Parseamos la respuesta como JSON
    .then(data => {
      // Si la respuesta contiene un error, lo mostramos en rojo
      if (data.error) {
        modalBody.innerHTML = `<p class="text-danger">${data.error}</p>`;
        return;
      }

      // Si el array de líneas está vacío, mostramos mensaje de "No hay líneas"
      if (data.length === 0) {
        modalBody.innerHTML = '<p>No hay líneas para este pedido.</p>';
        return;
      }

      // Creamos la estructura HTML de la tabla para mostrar las líneas
      let table = `
        <table class="table table-sm table-striped">
          <thead>
            <tr>
              <th>Código</th>
              <th>Descripción</th>
              <th>Cantidad</th>
              <th>Precio</th>
            </tr>
          </thead>
          <tbody>
      `;

      // Por cada línea del pedido, generamos una fila de la tabla
      data.forEach(linea => {
        table += `
          <tr>
            <td>${linea.CODIGO}</td>
            <td>${linea.LINDESC}</td>
            <td>${linea.CANTIDAD}</td>
            <td>${linea.PRECIO}</td>
          </tr>
        `;
      });

      // Cerramos el cuerpo y tabla
      table += '</tbody></table>';

      // Insertamos la tabla generada en el modal
      modalBody.innerHTML = table;
    })
    .catch(err => {
      // Si ocurre un error en la petición, lo mostramos en rojo
      modalBody.innerHTML = `<p class="text-danger">Error al cargar líneas.</p>`;
      console.error(err); // Lo mostramos también en la consola para depurar
    });
  });
});


});