document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('filtroTipoDispositivo');

    // Verificamos si el select existe
    if (!select) return;

    // ✅ Inicializar Choices.js con búsqueda y estilo
    const choices = new Choices(select, {
        searchEnabled: false, // puedes cambiarlo a true si luego quieres activar el buscador interno
        itemSelectText: '',
        shouldSort: false,
        placeholder: true,
        classNames: {
            containerInner: 'choices-inner-custom',
            input: 'choices-input-custom'
        }
    });

    // ✅ Estilos visuales del dropdown para mostrar máximo 5 opciones
    select.addEventListener('showDropdown', () => {
        const dropdown = select.closest('.choices').querySelector('.choices__list--dropdown');
        if (dropdown) {
            dropdown.style.maxHeight = '200px'; // ≈5 opciones
            dropdown.style.overflowY = 'auto';
        }
    });

    // ✅ Función para filtrar tabla combinando select y buscador
    function filtrarTabla() {
        const tipoSeleccionado = select.value?.toLowerCase() || '';
        const codigoBuscado = document.getElementById('inputBuscarCodigo')?.value.toLowerCase() || '';

        document.querySelectorAll('#tabla-dispositivos tr').forEach((fila) => {
            const tipo = fila.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
            const codigo = fila.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';

            const mostrar =
                (tipoSeleccionado === '' || tipo === tipoSeleccionado) &&
                (codigoBuscado === '' || codigo.includes(codigoBuscado));

            fila.style.display = mostrar ? '' : 'none';
        });
    }

    // ✅ Eventos para activar el filtro
    select.addEventListener('change', filtrarTabla);
    document.getElementById('inputBuscarCodigo')?.addEventListener('input', filtrarTabla);
});
