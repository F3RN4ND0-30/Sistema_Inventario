document.addEventListener('DOMContentLoaded', () => {
    const areaSelect = document.getElementById('modalArea');

    areaSelect.innerHTML = '<option value="">Cargando áreas...</option>';

    fetch('../Backend/areas/get_areas.php')
        .then(response => response.json())
        .then(areas => {
            areaSelect.innerHTML = '<option value="">Seleccione un área</option>';
            areas.forEach(area => {
                const option = document.createElement('option');
                option.value = area.IdArea;
                option.textContent = area.descripcion;
                areaSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar áreas:', error);
            areaSelect.innerHTML = '<option value="">Error al cargar áreas</option>';
        });
});
