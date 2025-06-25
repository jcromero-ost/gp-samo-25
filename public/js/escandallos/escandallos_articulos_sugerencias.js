document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('articulo_search');
  const suggestions = document.getElementById('articulo_suggestions');
  const hiddenInput = document.getElementById('articulo_codigo');

  let timeout = null;

  input.addEventListener('input', () => {
    const query = input.value.trim();

    clearTimeout(timeout);
    if (query.length < 2) {
      suggestions.innerHTML = '';
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
          suggestions.innerHTML = '';
          hiddenInput.value = '';

          if (data.length === 0) {
            const noItem = document.createElement('div');
            noItem.classList.add('list-group-item');
            noItem.textContent = 'No se encontraron artículos';
            suggestions.appendChild(noItem);
            return;
          }

          data.forEach(art => {
            const item = document.createElement('div');
            item.classList.add('list-group-item', 'list-group-item-action');
            item.textContent = `${art.codigo} - ${art.nombre}`;
            item.addEventListener('click', () => {
              input.value = `${art.codigo} - ${art.nombre}`; // Mostrar código y nombre
              hiddenInput.value = art.codigo;
              suggestions.innerHTML = '';
            });
            suggestions.appendChild(item);
          });
        })
        .catch(err => {
          console.error('Error al buscar artículos:', err);
          suggestions.innerHTML = '';
          hiddenInput.value = '';
        });
    }, 300);
  });

  // Ocultar sugerencias si clic fuera
  document.addEventListener('click', (e) => {
    if (!suggestions.contains(e.target) && e.target !== input) {
      suggestions.innerHTML = '';
    }
  });
});
