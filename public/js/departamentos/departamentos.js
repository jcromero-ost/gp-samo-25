document.addEventListener('DOMContentLoaded', function () {
    //Mostrar y ocultar el formulario
    const toggleBtn = document.getElementById('toggleBtn');
    const formulario_crear_departamento = document.getElementById('formulario_crear_departamento');

    toggleBtn.addEventListener('click', () => {
        if (formulario_crear_departamento.style.display === 'none' || formulario_crear_departamento.style.display === '') {
        formulario_crear_departamento.style.display = 'block';
        toggleBtn.innerHTML = '<i class="bi bi-caret-up-square"></i> Ocultar';
        toggleBtn.classList.remove('btn-success');
        toggleBtn.classList.add('btn-secondary');
        } else {
        formulario_crear_departamento.style.display = 'none';
        toggleBtn.innerHTML = '<i class="bi bi-caret-down-square"></i> Crear nuevo departamento';
        toggleBtn.classList.remove('btn-secondary');
        toggleBtn.classList.add('btn-success');
        }
    });

});