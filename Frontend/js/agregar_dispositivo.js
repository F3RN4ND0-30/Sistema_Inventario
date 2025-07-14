// Función para actualizar la tabla de dispositivos
async function actualizarTabla() {
    const tbody = document.getElementById('tabla-dispositivos');
    console.log('▶️ actualizarTabla disparada, tbody encontrada:', !!tbody);

    try {
        const resp = await fetch('../Backend/dispositivos/obtener_dispositivos.php');
        console.log('➡️ Fetch HTTP status:', resp.status);
        const text = await resp.text();
        console.log('📄 Respuesta raw:', text);

        let data;
        try {
            data = JSON.parse(text);
            console.log('✅ JSON parseado:', data);
        } catch (jsonErr) {
            console.error('❌ Error parseando JSON:', jsonErr);
            throw new Error('JSON inválido');
        }

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

        console.log('✅ Tabla actualizada correctamente con', data.length, 'filas');

    } catch (error) {
        console.error('❗ Error actualizando la tabla:', error);
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error cargando datos.</td></tr>';
    }
}

document.addEventListener('DOMContentLoaded', actualizarTabla);

document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('btnAgregarDispositivo');
    if (!btn) {
        console.error("No se encontró el botón 'btnAgregarDispositivo'");
        return;
    }

    btn.addEventListener('click', async () => {
        // Obtener valores de campos generales
        const tipoSelect = document.getElementById('modalTipoDispositivo');
        const tipoID = tipoSelect?.value?.trim();
        if (!tipoID) return alert('Selecciona un tipo.');

        const tipoTexto = tipoSelect.options[tipoSelect.selectedIndex].text.trim();
        const tipoKey = tipoTexto.toLowerCase();

        const mapaTipoContenedor = {
            'cpu': 'camposCPU',
            'laptop': 'camposLaptop',
            'tablet': 'camposTablet',
            'celular': 'camposCelular',
            'servidor': 'camposServidor',
            'impresora': 'camposImpresora',
            'teclado': 'camposTeclado',
            'escaner': 'camposEscaner',
            'pantalla': 'camposPantalla',
            'mouse': 'camposMouse',
            'biometrico': 'camposBiometrico',
            'lector': 'camposLector',
            'estabilizador': 'camposEstabilizador',
            'router': 'camposRouter',
            'switch': 'camposSwitch',
            'radio': 'camposRadio',
            'camara': 'camposCamaras',
            'nvr': 'camposNVR',
        };

        const selectorCont = `#${mapaTipoContenedor[tipoKey] || 'camposVariado'}`;

        const codElem = document.getElementById('cod_patrimonial');
        const areaElem = document.getElementById('modalArea');
        const estadoElem = document.getElementById('crearEstado');
        const obsElem = document.getElementById('observacion');

        if (!codElem || !areaElem || !estadoElem || !obsElem) {
            console.error('Falta algún input general');
            return alert('Faltan campos generales en el formulario.');
        }

        const cod = codElem.value.trim();
        const area = areaElem.value.trim();
        const estado = estadoElem.value.trim();
        const observacion = obsElem.value.trim();

        if (!cod || !area || !estado) return alert('Completa todos los campos requeridos.');

        const datosDisp = {
            codigo_patrimonial: cod,
            id_area: area,
            estado,
            observaciones: observacion,
            tipo_dispositivo: tipoID
        };

        // Obtener campos específicos según tipo
        const cont = document.querySelector(selectorCont);
        const datosEspec = {};
        if (cont && !cont.classList.contains('d-none')) {
            const inputs = cont.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                const key = input.name.trim();
                const val = input.value.trim();
                datosEspec[key] = val;
            });
        }

        try {
            // Guardar dispositivo general
            let resp = await fetch('../Backend/dispositivos/guardar_dispositivo.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datosDisp)
            });

            if (!resp.ok) throw new Error('Error guardando dispositivo general');

            const jsonDisp = await resp.json();
            if (!jsonDisp.success) throw new Error(jsonDisp.message || 'Error guardando dispositivo');

            // Guardar detalle
            const idDispositivo = jsonDisp.id;
            datosEspec.IdDispositivo = idDispositivo;

            const tiposConArchivo = ['cpu', 'laptop', 'tablet', 'celular', 'servidor'];
            const archivoDetalle = tiposConArchivo.includes(tipoKey)
                ? `../Backend/dispositivos/guardar_${tipoKey}.php`
                : `../Backend/dispositivos/guardar_variado.php`;

            resp = await fetch(archivoDetalle, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datosEspec)
            });

            if (!resp.ok) throw new Error('Error guardando detalle dispositivo');

            const jsonDet = await resp.json();
            if (!jsonDet.success) throw new Error(jsonDet.message || 'Error guardando detalle');

            alert('✅ Dispositivo y detalle guardados correctamente.');

            // Limpiar campos y cerrar modal
            limpiarCampos();

            const modal = document.getElementById('modalAgregarDispositivo');
            if (modal) {
                const modalBootstrap = bootstrap.Modal.getInstance(modal);
                if (modalBootstrap) modalBootstrap.hide();
            }

            // Actualizar tabla
            await actualizarTabla();

        } catch (error) {
            alert('Error: ' + error.message);
            console.error(error);
        }
    });

    function limpiarCampos() {
        const camposGenerales = ['cod_patrimonial', 'modalArea', 'crearEstado', 'observacion', 'modalTipoDispositivo'];
        camposGenerales.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        // Ocultar todos los contenedores específicos y limpiar sus inputs
        const contenedores = document.querySelectorAll('[id^="campos"]');
        contenedores.forEach(cont => {
            cont.classList.add('d-none'); // 👈 Oculta el contenedor
            const inputs = cont.querySelectorAll('input, textarea');
            inputs.forEach(input => input.value = '');
        });

        // Reiniciar select de tipo
        const tipoSelect = document.getElementById('modalTipoDispositivo');
        if (tipoSelect) {
            tipoSelect.selectedIndex = 0;
            tipoSelect.dispatchEvent(new Event('change')); // 👈 Dispara el evento para ocultar campos si hace falta
        }

        // Reiniciar select de estado
        const estadoSelect = document.getElementById('crearEstado');
        if (estadoSelect) estadoSelect.selectedIndex = 0;
    }


    // Actualiza tabla al cargar la página
    actualizarTabla();
});
