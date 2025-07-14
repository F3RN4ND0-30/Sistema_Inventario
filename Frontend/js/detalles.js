document.getElementById('formBuscar').addEventListener('submit', function(e) {
    e.preventDefault();

    const codigo = document.getElementById('codigo').value.trim();

    if (!codigo) {
        alert('Por favor ingresa un código patrimonial.');
        return;
    }

    fetch('../Backend/detalles_buscar.php?codigo=' + encodeURIComponent(codigo))
        .then(response => response.json())
        .then(data => {
            const resultadoDiv = document.getElementById('resultado');
            if (data.error) {
                resultadoDiv.innerHTML = `<div class="alert alert-warning">${data.error}</div>`;
                return;
            }

            // Construir HTML con datos recibidos
            let html = `
                <div class="card shadow-sm">
                    <div class="card-header"><h4>Detalles del dispositivo</h4></div>
                    <div class="card-body">
                        <p><strong>Código Patrimonial:</strong> ${data.dispositivo.CodigoPatrimonial}</p>
                        <p><strong>Tipo:</strong> ${data.dispositivo.NombreTipo}</p>
                        <p><strong>Estado:</strong> ${data.dispositivo.Estado}</p>
                        <p><strong>Área:</strong> ${data.dispositivo.IdArea}</p>
                        <p><strong>Fecha Registro:</strong> ${data.dispositivo.FechaRegistro}</p>
                        <p><strong>Observaciones:</strong> ${data.dispositivo.Observaciones.replace(/\n/g, '<br>')}</p>
            `;

            if (data.datosEspecializados) {
                html += `<hr/><h5>Características específicas</h5><ul class="list-group">`;

                for (const [campo, valor] of Object.entries(data.datosEspecializados)) {
                    // Ignorar ids internos
                    if (['IdCPU','IdDispositivo','IdImpresora','IdTeclado','IdCelular'].includes(campo)) continue;
                    html += `<li class="list-group-item"><strong>${campo.replace(/_/g, ' ')}:</strong> ${valor}</li>`;
                }

                html += `</ul>`;
            } else {
                html += `<p><em>No hay características específicas para este dispositivo.</em></p>`;
            }

            html += `</div></div>`;

            resultadoDiv.innerHTML = html;
        })
        .catch(err => {
            document.getElementById('resultado').innerHTML = `<div class="alert alert-danger">Error al consultar el dispositivo.</div>`;
            console.error(err);
        });
});
