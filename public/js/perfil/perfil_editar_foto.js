document.addEventListener("DOMContentLoaded", function () {
  // Iniciar Cropper para edición de foto
  iniciarCropper({
    dropAreaId: "edit-drop-area",
    inputFileId: "edit_foto",
    previewImgId: "edit_preview",
    previewContainerId: "edit-preview-container",
    hiddenInputId: "edit_foto_recortada", // asegúrate de tener este input en tu <form>
    dropTextId: "edit-drop-text",
    clearButtonId: "edit-btn-clear",
    cropButtonId: "edit-btn-crop"
  });

  // Validar antes de enviar el formulario
  const formEditarfoto = document.getElementById('formEditarfoto');

  formEditarfoto.addEventListener("submit", function (e) {
    const base64 = document.getElementById("edit_foto_recortada")?.value;
    const fileInput = document.getElementById("edit_foto");

    if (fileInput.files.length > 0 && (!base64 || !base64.startsWith("data:image/"))) {
      alert("Por favor espera a que la imagen termine de cargarse y recortarse.");
      e.preventDefault();
    }
  });
});