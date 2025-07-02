document.addEventListener('DOMContentLoaded', function () {
    // Definimos las variables del modal
    const modal_eliminar = document.getElementById('modal_editar_usuario');
    const modal_editar_usuario = new bootstrap.Modal(modal_eliminar);

    // Definimos una variable para guardar la id a eliminar y otra para mostrarlo en el modal
    const edit_id = document.getElementById('edit_id');

    // Cuando se pulse el boton, se realizan las operaciones
    document.querySelectorAll('.btn-editar').forEach(btn => {
        btn.addEventListener('click', () => {
        const id = btn.dataset.id; // Guardamos la id pasada por el boton en una variable

        const user = JSON.parse(btn.dataset.usuario);

        edit_id.value = id; // Asignamos la id a la variable para eliminar
        document.getElementById('edit_nombre').value = user.nombre;
        document.getElementById('edit_alias').value = user.alias || '';
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_telefono').value = user.telefono || '';
        document.getElementById('edit_departamento_id').value = user.departamento_id || '';

        // Previsualizar imagen actual (base64 o ruta)
        const preview = document.getElementById('edit_preview');
        const previewContainer = document.getElementById('edit-preview-container');
        const dropText = document.getElementById('edit-drop-text');

        if (user.foto && user.foto.startsWith('data:image/')) {
            // Foto guardada como base64
            preview.src = user.foto;
        } else if (user.foto) {
            // Foto guardada como archivo (modo anterior)
            preview.src = `./public/images/images_users/${user.foto}`;
        } else {
            preview.src = '';
        }

        if (user.foto) {
            preview.classList.remove('d-none');
            previewContainer.classList.remove('d-none');
            dropText.classList.add('d-none');
        } else {
            preview.classList.add('d-none');
            previewContainer.classList.add('d-none');
            dropText.classList.remove('d-none');
        }

        modal_editar_usuario.show(); //Mostramos el modal
        });
    });


    // Activar Cropper.js en el modal de edición
    iniciarCropper({
        dropAreaId: "edit-drop-area",
        inputFileId: "edit_foto",
        previewImgId: "edit_preview",
        previewContainerId: "edit-preview-container",
        hiddenInputId: "edit_foto_recortada",
        dropTextId: "edit-drop-text",
        clearButtonId: "edit-btn-clear"
    });
});