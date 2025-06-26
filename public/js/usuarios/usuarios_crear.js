document.addEventListener('DOMContentLoaded', function () {
  // Validación de coincidencia de contraseñas
  const password = document.getElementById('password');
  const confirm = document.getElementById('confirm_password');
  const help = document.getElementById('passwordHelp');

  if (password && confirm && help) {
    confirm.addEventListener('input', () => {
      help.classList.toggle('d-none', confirm.value === password.value);
    });
  }

  // Mostrar/ocultar contraseña
  const togglePassword = document.getElementById('togglePassword');
  const toggleConfirm = document.getElementById('toggleConfirm');

  if (togglePassword && password) {
    togglePassword.addEventListener('click', () => {
      const icon = document.getElementById('iconPassword');
      const isVisible = password.type === 'text';
      password.type = isVisible ? 'password' : 'text';
      icon.className = isVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
  }

  if (toggleConfirm && confirm) {
    toggleConfirm.addEventListener('click', () => {
      const icon = document.getElementById('iconConfirm');
      const isVisible = confirm.type === 'text';
      confirm.type = isVisible ? 'password' : 'text';
      icon.className = isVisible ? 'bi bi-eye-slash' : 'bi bi-eye';
    });
  }

  // Inicializa Cropper.js usando cropper_util.js
  iniciarCropper({
    dropAreaId: "drop-area",
    inputFileId: "foto",
    previewImgId: "preview",
    previewContainerId: "preview-container",
    hiddenInputId: "foto_recortada",
    dropTextId: "drop-text",
    clearButtonId: "btn-clear"
  });
});
