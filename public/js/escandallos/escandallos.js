document.addEventListener('DOMContentLoaded', () => {



    const boton_asignar_materias = document.getElementById('boton_asignar_materias');
    const contenedor_materias = document.getElementById('contenedor_materias');
    const articulo_codigo = document.getElementById('articulo_codigo');

    function mostrarAlerta(mensaje, tipo = 'danger') {
        const alertas = document.getElementById('alertas');
        const alerta = document.createElement('div');
        const id = Date.now();

        alerta.className = `alert alert-${tipo} alert-dismissible fade show`;
        alerta.role = 'alert';
        alerta.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        alerta.id = `alerta-${id}`;
        alertas.appendChild(alerta);

        // Oculta después de 5 segundos automáticamente
        setTimeout(() => {
            const a = document.getElementById(`alerta-${id}`);
            if (a) a.remove();
        }, 5000);
    }

    // Definimos las variables del modal
    const modal_eliminar = document.getElementById('modal_eliminar_materia');
    const modal_eliminar_materia = bootstrap.Modal.getOrCreateInstance(modal_eliminar);
    const boton_eliminar_modal = document.getElementById('boton_eliminar_modal');

    // Definimos una variable para guardar la id a eliminar y otra para mostrarlo en el modal
    const articulopadre_id_modal = document.getElementById('articulopadre_id_modal');
    const articulo_id_modal = document.getElementById('articulo_id_modal');
    const id_articulo_mostrar = document.getElementById('id_articulo_mostrar');

    function cargarMaterias(codigo) {
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
                <button id="boton_nueva_materia" type="button" class="btn btn-success w-100 mb-2"><i class="bi bi-plus-square me-2"></i>Nueva Materia</button>
            `;

            if (data.length > 0) {
                data.forEach(materia => {
                    const div = document.createElement('div');
                    div.className = 'materia-item mb-2';

                    const contenedorFlex = document.createElement('div');
                    contenedorFlex.className = 'd-flex align-items-center';

                    const input = document.createElement('input');
                    input.type = 'text';
                    input.name = 'materias[]';
                    input.value = `${materia.CODIGO} - ${materia.NOMBRE}` || '';
                    input.className = 'form-control flex-grow-1 w-75 me-2';
                    input.readOnly = true;

                    const input_cantidad = document.createElement('input');
                    input_cantidad.type = 'text';
                    input_cantidad.name = 'cantidad';
                    input_cantidad.value = `${materia.CANTIDAD} Gramos`;
                    input_cantidad.className = 'form-control flex-grow-1 w-25';
                    input_cantidad.readOnly = true;

                    const btnEliminar = document.createElement('button');
                    btnEliminar.type = 'button';
                    btnEliminar.className = 'ms-2 btn btn-danger btn-eliminar';
                    btnEliminar.innerHTML = '<i class="bi bi-trash"></i>';
                    
                    btnEliminar.dataset.id = materia.CODIGO; // Asigna el código al botón

                    btnEliminar.addEventListener('click', () => {
                        const id = btnEliminar.dataset.id;

                        articulopadre_id_modal.value = codigo;
                        articulo_id_modal.value = id;
                        id_articulo_mostrar.textContent = codigo;
                        modal_eliminar_materia.show();
                    });

                    // Boton eliminar del modal
                    // Asegúrate de que este código solo se registre una vez
                    if (!window.eliminarModalRegistrado) {
                        boton_eliminar_modal.addEventListener('click', (event) => {
                            event.preventDefault(); // Evita cualquier comportamiento por defecto, por si acaso

                            const codigoPadre = articulopadre_id_modal.value;
                            const codigoMateria = articulo_id_modal.value;

                            fetch('./escandallos_eliminar', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: `codigo_articulo_padre=${encodeURIComponent(codigoPadre)}&codigo_articulo=${encodeURIComponent(codigoMateria)}`
                            })
                            .then(res => {
                                if (res.ok) {
                                    return res.json();
                                } else {
                                    return res.json().then(data => {
                                        throw new Error(data.message || 'Error desconocido');
                                    });
                                }
                            })
                            .then(data => {
                                if (data.success) {
                                    cargarMaterias(codigoPadre);
                                    cargarArticulosPagina(1);
                                    mostrarAlerta(data.message, 'success');
                                    modal_eliminar_materia.hide(); // <-- Esto no está funcionando correctamente
                                } else {
                                    mostrarAlerta(data.message || 'No se pudo eliminar.', 'danger');
                                }
                            })
                            .catch(err => {
                                console.error('Error al eliminar materia:', err);
                                mostrarAlerta(err.message || 'No se pudo eliminar la materia.', 'danger');
                            });
                        });

                        window.eliminarModalRegistrado = true;
                    }

                    contenedorFlex.appendChild(input);
                    contenedorFlex.appendChild(input_cantidad);
                    contenedorFlex.appendChild(btnEliminar);
                    div.appendChild(contenedorFlex);
                    contenedor_materias.appendChild(div);
                });
            } else {
                const aviso_no_materias = document.createElement('div');
                aviso_no_materias.className = 'd-flex align-items-center justify-content-center flex-column bg-light border rounded p-4 mt-3 shadow-sm mb-3';

                aviso_no_materias.innerHTML = `
                    <i class="bi bi-box-seam display-4 text-secondary mb-2"></i>
                    <p class="text-muted mb-0">Este artículo no tiene materias primas asignadas</p>
                `;

                contenedor_materias.appendChild(aviso_no_materias);
            }

            document.getElementById('boton_nueva_materia').addEventListener('click', agregarNuevaMateria);
        })
        .catch(err => {
            console.error('Error obteniendo materias:', err);
            mostrarAlerta('Error al cargar materias.', 'danger');
        });
    }

    boton_asignar_materias.addEventListener('click', () => {
        const codigo = articulo_codigo.value;

        if (!codigo) {
            mostrarAlerta('Debes seleccionar un artículo válido.', 'warning');
            return;
        }

        cargarMaterias(codigo);
    });

    function agregarNuevaMateria() {
        const contenedor = document.createElement('div');
        contenedor.classList.add('materia-item', 'mb-3', 'd-flex', 'align-items-center', 'position-relative');

        const inputVisible = document.createElement('input');
        inputVisible.type = 'text';
        inputVisible.className = 'form-control flex-grow-1 w-75 me-2';
        inputVisible.placeholder = 'Nombre o código del artículo';
        inputVisible.autocomplete = 'off';

        const input_cantidad = document.createElement('input');
        input_cantidad.type = 'number';
        input_cantidad.name = 'cantidad';
        input_cantidad.placeholder = 'Cantidad';
        input_cantidad.className = 'form-control flex-grow-1 w-25';

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
            const cantidadMateria = input_cantidad.value;

            if (!codigoPadre || !codigoMateria) {
                mostrarAlerta('Debes seleccionar un artículo válido.', 'warning');
                return;
            }

            fetch('./escandallos_crear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `codigo_articulo_padre=${encodeURIComponent(codigoPadre)}&codigo_articulo=${encodeURIComponent(codigoMateria)}&cantidad=${encodeURIComponent(cantidadMateria)}`
            })
            .then(res => {
                if (res.ok) {
                    return res.json();
                } else if (res.status === 409) {
                    return res.json().then(data => {
                        mostrarAlerta(data.message, 'danger');
                    });
                } else {
                    mostrarAlerta('Error inesperado al asignar materia.', 'danger');
                }
            })
            .then(data => {
                if (data && data.success) {
                    cargarMaterias(codigoPadre);
                    cargarArticulosPagina(1);
                    mostrarAlerta(data.message, 'success');
                }
            })
            .catch(err => {
                console.error('Error al crear escandallo:', err);
                mostrarAlerta('No se pudo asignar la materia.', 'danger');
            });
        });

        const btnCancelar = document.createElement('button');
        btnCancelar.type = 'button';
        btnCancelar.className = 'btn btn-secondary ms-2';
        btnCancelar.innerHTML = '<i class="bi-x-square"></i>';
        btnCancelar.addEventListener('click', () => {
            contenedor.remove();
        });

        contenedor.appendChild(inputVisible);
        contenedor.appendChild(input_cantidad);
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
                            input.value = `${art.codigo} - ${art.nombre}`;
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
});
