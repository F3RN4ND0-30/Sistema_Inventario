document.addEventListener('DOMContentLoaded', () => {
    const TipoSelect = document.getElementById('modalTipoDispositivo');

    TipoSelect.innerHTML = '<option value="">Cargando tipos de dispositivos...</option>';

    fetch('../Backend/dispositivos/get_tipos_dispositivo.php')
        .then(response => response.json())
        .then(tipos => {
            TipoSelect.innerHTML = '<option value="">Seleccione un tipo de dispositivo</option>';
            tipos.forEach(tipos => {
                const option = document.createElement('option');
                option.value = tipos.IdTipoDispositivo;
                option.textContent = tipos.NombreTipo;
                TipoSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar Tipo de Dispositivo:', error);
            TipoSelect.innerHTML = '<option value="">Error al cargar Tipo de Dispositivos</option>';
        });
});