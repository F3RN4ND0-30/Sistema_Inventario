async function actualizarTabla() {
    const tbody = document.querySelector('table tbody');
    if (!tbody) return console.error('No se encontró la tabla');

    try {
        const resp = await fetch('../Backend/dispositivos/obtener_dispositivos.php');
        const data = await resp.json();

        tbody.innerHTML = '';
        if (!data.length) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center">No hay dispositivos registrados.</td></tr>';
            return;
        }

        data.forEach(row => {
            const estadoClass = {
                Operativo: 'btn-success',
                EnReparacion: 'btn-warning',
                Baja: 'btn-danger',
                EnPrestamo: 'btn-primary'
            }[row.Estado] || 'btn-secondary';

            tbody.insertAdjacentHTML('beforeend', `
                <tr>
                  <td>${row.IdDispositivo}</td>
                  <td>${row.CodigoPatrimonial}</td>
                  <td>${row.NombreTipo || ''}</td>
                  <td>${row.descripcion || ''}</td>
                  <td><button class="btn btn-sm ${estadoClass}">${row.Estado}</button></td>
                  <td>${row.FechaRegistro}</td>
                  <td>${row.Observaciones || ''}</td>
                </tr>`);
        });
    } catch (err) {
        console.error('Error actualizando tabla:', err);
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error cargando datos.</td></tr>';
    }
}

document.addEventListener('DOMContentLoaded', actualizarTabla);
