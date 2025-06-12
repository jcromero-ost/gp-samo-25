document.addEventListener('DOMContentLoaded', function () {
    // Definimos las variables del modal
    const modal_eliminar = document.getElementById('modal_eliminar_usuario');
    const modal_eliminar_usuario = new bootstrap.Modal(modal_eliminar);

    // Definimos una variable para guardar la id a eliminar y otra para mostrarlo en el modal
    const delete_id = document.getElementById('delete_id');
    const delete_id_mostrar = document.getElementById('delete_id_mostrar');

    // Cuando se pulse el boton, se realizan las operaciones
    document.querySelectorAll('.btn-eliminar').forEach(btn => {
      btn.addEventListener('click', () => {
        const id = btn.dataset.id; // Guardamos la id pasada por el boton en una variable
  
        delete_id.value = id; // Asignamos la id a la variable para eliminar

        delete_id_mostrar.textContent = id; // Asignamos la id para mostrarla en el modal
  
        modal_eliminar_usuario.show(); //Mostramos el modal
      });
    });

});