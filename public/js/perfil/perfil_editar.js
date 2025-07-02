document.addEventListener("DOMContentLoaded", function () {
    // Validación de coincidencia de contraseña nueva
    const new_password = document.getElementById('new_password');
    const new_password_confirm = document.getElementById('new_password_confirm');
    const help = document.getElementById('passwordHelp');

    if (new_password && new_password_confirm && help) {
        new_password_confirm.addEventListener('input', () => {
        help.classList.toggle('d-none', new_password_confirm.value === new_password.value);
        });
    }

    // Mostrar/ocultar contraseña
    const toggleNewPassword = document.getElementById('toggleNewPassword');
    const toggleNewPassword_confirm = document.getElementById('toggleNewPassword_confirm');

    if (toggleNewPassword && new_password) {
        toggleNewPassword.addEventListener('click', () => {
        const icon = document.getElementById('iconPassword');
        const isVisible = new_password.type === 'text';
        new_password.type = isVisible ? 'password' : 'text';
        icon.className = isVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }

    if (toggleNewPassword_confirm && new_password_confirm) {
        toggleNewPassword_confirm.addEventListener('click', () => {
        const icon = document.getElementById('iconConfirm');
        const isVisible = new_password_confirm.type === 'text';
        new_password_confirm.type = isVisible ? 'password' : 'text';
        icon.className = isVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }

    // Validación de coincidencia de pin
    const new_pin = document.getElementById('new_pin');
    const new_pin_confirm = document.getElementById('new_pin_confirm');
    const help_pin = document.getElementById('PinHelp');

    if (new_pin && new_pin_confirm && help) {
        new_pin_confirm.addEventListener('input', () => {
        help_pin.classList.toggle('d-none', new_pin_confirm.value === new_pin.value);
        });
    }

    // Mostrar/ocultar contraseña
    const toggleNewPin = document.getElementById('toggleNewPin');
    const toggleNewPin_confirm = document.getElementById('toggleNewPin_confirm');

    if (toggleNewPin && new_pin) {
        toggleNewPin.addEventListener('click', () => {
        const icon = document.getElementById('iconPin');
        const isVisible = new_pin.type === 'text';
        new_pin.type = isVisible ? 'password' : 'text';
        icon.className = isVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }

    if (toggleNewPin_confirm && new_pin_confirm) {
        toggleNewPin_confirm.addEventListener('click', () => {
        const icon = document.getElementById('iconPinConfirm');
        const isVisible = new_pin_confirm.type === 'text';
        new_pin_confirm.type = isVisible ? 'password' : 'text';
        icon.className = isVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }

    //Modal de editar foto
    const modalElement = document.getElementById('modalEditarFoto');

    const modalEditarFoto = new bootstrap.Modal(modalElement);

    //Guardamos el boton de la imagen
    const boton_editar_foto = document.getElementById('boton_editar_foto');

    //Guardamos datos para cargar la imgaen
    const preview = document.getElementById('edit_preview');
    const previewContainer = document.getElementById('edit-preview-container');
    const dropText = document.getElementById('edit-drop-text');
    const hiddenInput = document.getElementById('edit_foto_recortada');

    boton_editar_foto.addEventListener('click', function(){
        modalEditarFoto.show();

        const foto = modalElement.getAttribute('data-usuario-foto');

        // Si foto es un nombre simple (como 'default.jpeg'), agrega la ruta completa
        let fotoSrc = foto || '';
        if (fotoSrc && !fotoSrc.startsWith('data:image/') && !fotoSrc.startsWith('http') && !fotoSrc.startsWith('/')) {
            // Asumiendo que las imágenes están en /public/images/
            fotoSrc = './public/images/images_users/' + fotoSrc;
        }

        if (fotoSrc) {
            preview.src = fotoSrc;
            preview.classList.remove('d-none');
            previewContainer.classList.remove('d-none');
            dropText.classList.add('d-none');
            hiddenInput.value = '';
            document.getElementById('edit_foto').value = '';
        } else {
            preview.src = '';
            preview.classList.add('d-none');
            previewContainer.classList.add('d-none');
            dropText.classList.remove('d-none');
            hiddenInput.value = '';
            document.getElementById('edit_foto').value = '';
        }
    });
});
