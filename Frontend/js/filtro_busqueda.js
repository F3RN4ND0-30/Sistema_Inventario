document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('filtroTipoDispositivo');
    const inputBuscar = document.getElementById('inputBuscarCodigo');

    // Verificamos si el select existe
    if (!select) return;

    // Inicializar Choices.js (si usas)
    const choices = new Choices(select, {
        searchEnabled: false,
        itemSelectText: '',
        shouldSort: false,
        placeholder: true,
        classNames: {
            containerInner: 'choices-inner-custom',
            input: 'choices-input-custom'
        }
    });

    select.addEventListener('showDropdown', () => {
        const dropdown = select.closest('.choices').querySelector('.choices__list--dropdown');
        if (dropdown) {
            dropdown.style.maxHeight = '200px'; // ≈5 opciones
            dropdown.style.overflowY = 'auto';
        }
    });

    // Función que filtra la tabla según select e input
    function filtrarTabla() {
        const tipoSeleccionado = select.value?.toLowerCase() || '';
        const codigoBuscado = inputBuscar?.value.toLowerCase() || '';

        document.querySelectorAll('#tabla-dispositivos tbody tr').forEach(fila => {
            const tipo = fila.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            const codigo = fila.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';

            const mostrar = (tipoSeleccionado === '' || tipo === tipoSeleccionado) &&
                (codigoBuscado === '' || codigo.includes(codigoBuscado));

            fila.style.display = mostrar ? '' : 'none';
        });

        filtrarYPaginar(1);
    }

    // Escuchamos cambios en el select y el input para filtrar en tiempo real
    select.addEventListener('change', filtrarTabla);

    if (inputBuscar) {
        inputBuscar.addEventListener('input', filtrarTabla);
    }

    // Inicialmente mostramos todo y aplicamos paginación
    filtrarTabla();
});
