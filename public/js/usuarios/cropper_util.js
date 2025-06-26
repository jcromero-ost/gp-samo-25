function iniciarCropper({
  dropAreaId,
  inputFileId,
  previewImgId,
  previewContainerId,
  hiddenInputId,
  dropTextId,
  clearButtonId,
  cropButtonId
}) {
  let cropper;
  const dropArea = document.getElementById(dropAreaId);
  const fileInput = document.getElementById(inputFileId);
  const preview = document.getElementById(previewImgId);
  const previewContainer = document.getElementById(previewContainerId);
  const hiddenField = document.getElementById(hiddenInputId);
  const dropText = document.getElementById(dropTextId);
  const clearBtn = document.getElementById(clearButtonId);
  const cropButton = document.getElementById(cropButtonId);

  function handleFile(file) {
    const url = URL.createObjectURL(file);
    preview.src = url;
    preview.classList.remove('d-none');
    previewContainer.classList.remove('d-none');
    dropText.classList.add('d-none');

    if (cropper) cropper.destroy();

    cropper = new Cropper(preview, {
      aspectRatio: 1,
      viewMode: 1,
      cropend: () => {
        const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
        hiddenField.value = canvas.toDataURL('image/jpeg');
      }
    });
  }

  dropArea.addEventListener('click', (e) => {
    if (e.target === dropArea || e.target.id === dropTextId) {
      fileInput.click();
    }
  });

  fileInput.addEventListener('change', e => {
    const file = e.target.files[0];
    if (file) handleFile(file);
  });

  dropArea.addEventListener('dragover', e => {
    e.preventDefault();
    dropArea.classList.add('bg-light');
  });

  dropArea.addEventListener('dragleave', () => {
    dropArea.classList.remove('bg-light');
  });

  dropArea.addEventListener('drop', e => {
    e.preventDefault();
    dropArea.classList.remove('bg-light');
    const file = e.dataTransfer.files[0];
    if (file) handleFile(file);
  });

  clearBtn.addEventListener('click', () => {
    fileInput.value = "";
    previewContainer.classList.add('d-none');
    preview.src = "";
    dropText.classList.remove('d-none');
    hiddenField.value = "";
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
  });

  // ⚠️ Reforzar que se genere la imagen recortada antes de enviar
  const form = dropArea.closest('form');
  if (form) {
    form.addEventListener('submit', function () {
      if (cropper && hiddenField.value === "") {
        const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
        hiddenField.value = canvas.toDataURL('image/jpeg');
      }
    });


    // aplicar recorte
  const cropButton = document.getElementById("edit-btn-crop"); // o usa parámetro si quieres generalizar

  if (cropButton) {
    cropButton.addEventListener('click', () => {
      if (cropper) {
        const canvas = cropper.getCroppedCanvas({ width: 300, height: 300 });
        hiddenField.value = canvas.toDataURL('image/jpeg');

        // Mostrar mini feedback visual (opcional)
        cropButton.classList.add('btn-success');
        cropButton.textContent = "Recorte aplicado";
        setTimeout(() => {
          cropButton.classList.remove('btn-success');
          cropButton.textContent = "Aplicar recorte";
        }, 1500);
      }
    });
  }

  }
}
