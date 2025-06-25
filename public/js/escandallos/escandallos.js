document.addEventListener('DOMContentLoaded', () => {
    const boton_asignar_materias = document.getElementById('boton_asignar_materias');
    const contenedor_materias = document.getElementById('contenedor_materias');
    const articulo_codigo = document.getElementById('articulo_codigo');

    function cargarMaterias(codigo){
        fetch('./obtener_materias_por_codigo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `codigo=${encodeURIComponent(codigo)}`
        })
        .then(res => res.json())
        .then(data => {
            contenedor_materias.classList.remove('d-none');
            contenedor_materias.innerHTML = `
                <button id="boton_nueva_materia" type="button" class="btn btn-success mb-2"><i class="bi bi-plus-square me-2"></i>Nueva</button>
            `;

            if (data.length > 0) {
                data.forEach(materia => {
                    const div = document.createElement('div');
                    div.className = 'materia-item mb-2';

                    // Contenedor flex para alinear input y botón horizontalmente
                    const contenedorFlex = document.createElement('div');
                    contenedorFlex.className = 'd-flex align-items-center';

                    // Input ocupa casi todo el ancho, el botón tiene margen a la izquierda
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'materias[]';
                    input.value = `${materia.CODIGO} - ${materia.NOMBRE}` || '';
                    input.className = 'form-control flex-grow-1'; // ocupa todo el espacio posible
                    input.readOnly = true;

                    const btnEliminar = document.createElement('button');
                    btnEliminar.type = 'button';
                    btnEliminar.className = 'ms-2 btn btn-danger btn-eliminar';
                    btnEliminar.innerHTML = '<i class="bi bi-trash"></i>';
                    btnEliminar.addEventListener('click', () => {
                        div.remove();
                    });

                    contenedorFlex.appendChild(input);
                    contenedorFlex.appendChild(btnEliminar);
                    div.appendChild(contenedorFlex);
                    contenedor_materias.appendChild(div);

                });
            } else {
                const aviso = document.createElement('div');
                aviso.className = 'alert alert-warning mt-2';
                aviso.textContent = 'Este artículo no tiene materias primas.';
                contenedor_materias.appendChild(aviso);
            }

            document.getElementById('boton_nueva_materia').addEventListener('click', agregarNuevaMateria);
        })
        .catch(err => {
            console.error('Error obteniendo materias:', err);
        });
    }

    boton_asignar_materias.addEventListener('click', () => {
        const codigo = articulo_codigo.value;

        if (!codigo) {
            mostrarAlerta('Debes seleccionar un articulo válido', 'danger');
            return;
        }

        cargarMaterias(codigo);
    });

function agregarNuevaMateria() {
    const contenedor = document.createElement('div');
    contenedor.classList.add('materia-item', 'mb-3', 'd-flex', 'align-items-center', 'position-relative');

    const inputVisible = document.createElement('input');
    inputVisible.type = 'text';
    inputVisible.className = 'form-control flex-grow-1';
    inputVisible.placeholder = 'Nombre o código del artículo';
    inputVisible.autocomplete = 'off';

    const inputHidden = document.createElement('input');
    inputHidden.type = 'hidden';
    inputHidden.name = 'materias[]';
    inputHidden.classList.add('codigo-articulo-materia');

    const sugerencias = document.createElement('div');
    sugerencias.className = 'list-group mt-1 position-absolute';
    sugerencias.style.zIndex = '1000';
    sugerencias.style.top = '100%';
    sugerencias.style.left = '0';
    sugerencias.style.right = '0';

    const botonAgregar = document.createElement('button');
    botonAgregar.type = 'button';
    botonAgregar.className = 'btn btn-success ms-2';
    botonAgregar.innerHTML = '<i class="bi-plus-square"></i>';

    botonAgregar.addEventListener('click', () => {
        const codigoPadre = articulo_codigo.value;
        const codigoMateria = inputHidden.value;

        if (!codigoPadre || !codigoMateria) {
            mostrarAlerta('Debes seleccionar un articulo válido', 'danger');
            return;
        }

        fetch('./escandallos_crear', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `codigo_articulo_padre=${encodeURIComponent(codigoPadre)}&codigo_articulo=${encodeURIComponent(codigoMateria)}`
        })
        .then(res => {
            if (res.ok) {
                mostrarAlerta('Materia prima añadida correctamente.', 'success');
                cargarMaterias(codigoPadre);
            } else {
                mostrarAlerta('Error al guardar el escandallo.', 'danger');
            }
        })
        .catch(err => {
            console.error('Error al crear escandallo:', err);
            mostrarAlerta('Ocurrió un error al crear el escandallo.', 'danger');
        });
    });

    const btnCancelar = document.createElement('button');
    btnCancelar.type = 'button';
    btnCancelar.className = 'btn btn-secondary ms-2';
    btnCancelar.innerHTML = '<i class="bi-x-square"></i>';
    btnCancelar.addEventListener('click', function () {
        contenedor.remove();
    });

    contenedor.appendChild(inputVisible);
    contenedor.appendChild(inputHidden);
    contenedor.appendChild(sugerencias);
    contenedor.appendChild(botonAgregar);
    contenedor.appendChild(btnCancelar);
    contenedor_materias.appendChild(contenedor);

    contenedor_materias.classList.remove('d-none');

    aplicarAutocompletadoArticulo(inputVisible, inputHidden, sugerencias);
}


    function aplicarAutocompletadoArticulo(input, hiddenInput, suggestionsContainer) {
        let timeout = null;

        input.addEventListener('input', () => {
            const query = input.value.trim();
            clearTimeout(timeout);
            if (query.length < 2) {
            suggestionsContainer.innerHTML = '';
            hiddenInput.value = '';
            return;
            }

            timeout = setTimeout(() => {
            fetch('./articulos_sugerencias', {
                method: 'POST',
                headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `q=${encodeURIComponent(query)}`,
            })
                .then(res => res.json())
                .then(data => {
                suggestionsContainer.innerHTML = '';
                hiddenInput.value = '';

                if (data.length === 0) {
                    const noItem = document.createElement('div');
                    noItem.classList.add('list-group-item');
                    noItem.textContent = 'No se encontraron artículos';
                    suggestionsContainer.appendChild(noItem);
                    return;
                }

                data.forEach(art => {
                    const item = document.createElement('div');
                    item.classList.add('list-group-item', 'list-group-item-action');
                    item.textContent = `${art.codigo} - ${art.nombre}`;
                    item.addEventListener('click', () => {
                    input.value = `${art.codigo} - ${art.nombre}`; // Mostrar código y nombre
                    hiddenInput.value = art.codigo;
                    suggestionsContainer.innerHTML = '';
                    });
                    suggestionsContainer.appendChild(item);
                });
                })
                .catch(err => {
                console.error('Error al buscar artículos:', err);
                suggestionsContainer.innerHTML = '';
                hiddenInput.value = '';
                });
            }, 300);
        });

        document.addEventListener('click', (e) => {
            if (!suggestionsContainer.contains(e.target) && e.target !== input) {
            suggestionsContainer.innerHTML = '';
            }
        });
    }

    function mostrarAlerta(mensaje, tipo = 'info') {
        const alerta = document.getElementById('alerta');
        alerta.textContent = mensaje;
        alerta.className = `alert alert-${tipo} alert-dismissible fade show`;
        alerta.setAttribute('role', 'alert');

        const botonCerrar = document.createElement('button');
        botonCerrar.type = 'button';
        botonCerrar.className = 'btn-close';
        botonCerrar.setAttribute('data-bs-dismiss', 'alert');
        botonCerrar.setAttribute('aria-label', 'Cerrar');

        alerta.appendChild(botonCerrar);

        // Oculta automáticamente después de 3 segundos (3000ms)
        setTimeout(() => {
            alerta.classList.add('d-none');
        }, 3000);
    }

});
