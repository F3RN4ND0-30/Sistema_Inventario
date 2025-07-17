// Escapar caracteres especiales para evitar errores en HTML
function escapeHtmlAttr(str) {
    if (str == null) return '';
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#39;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;");
}

async function actualizarTabla() {
    const tbody = document.getElementById('cuerpo-tabla-dispositivos');

    try {
        const resp = await fetch('../Backend/dispositivos/obtener_dispositivos.php');
        const text = await resp.text();

        let data;
        try {
            data = JSON.parse(text);
        } catch (jsonErr) {
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

            const idDispositivo = escapeHtmlAttr(row.IdDispositivo);
            const estado = escapeHtmlAttr(row.Estado);
            const observacion = escapeHtmlAttr(row.Observaciones || '');
            const codigoPatrimonial = escapeHtmlAttr(row.CodigoPatrimonial || '');
            const nombreTipo = escapeHtmlAttr(row.NombreTipo || '');
            const descripcion = escapeHtmlAttr(row.descripcion || '');
            const fechaRegistro = escapeHtmlAttr(row.FechaRegistro || '');

            tbody.insertAdjacentHTML('beforeend', `
                <tr>
                    <td>${idDispositivo}</td>
                    <td>${codigoPatrimonial}</td>
                    <td>${nombreTipo}</td>
                    <td>${descripcion}</td>
                    <td>
                        <button class="btn btn-sm ${estadoClass}"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEditarEstado"
                            data-id="${idDispositivo}"
                            data-estado="${estado}"
                            data-observacion="${observacion}">
                            ${estado}
                        </button>
                    </td>
                    <td>${fechaRegistro}</td>
                    <td>${observacion}</td>
                </tr>
            `);
        });
        filtrarYPaginar(1);

    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error cargando datos.</td></tr>';
        console.error('Error en actualizarTabla:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    actualizarTabla();

    const btn = document.getElementById('btnAgregarDispositivo');
    if (!btn) {
        console.error("No se encontró el botón 'btnAgregarDispositivo'");
        return;
    }

    btn.addEventListener('click', async () => {
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
        const obsElem = document.getElementById('observaciones');

        if (!codElem || !areaElem || !estadoElem || !obsElem) {
            console.error('Falta algún input general');
            return alert('Faltan campos generales en el formulario.');
        }

        const cod = codElem.value.trim();
        const area = areaElem.value.trim();
        const estado = estadoElem.value.trim();
        const observacion = obsElem.value.trim();

        if (!cod || !area || !estado) return alert('Completa todos los campos requeridos.');

        // Validar si código patrimonial ya existe
        try {
            const checkResp = await fetch('../Backend/dispositivos/verificar_codigo.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ codigo_patrimonial: cod })
            });

            const contentType = checkResp.headers.get('content-type');
            if (!checkResp.ok || !contentType || !contentType.includes('application/json')) {
                throw new Error('Respuesta inesperada del servidor al verificar código patrimonial');
            }

            const checkJson = await checkResp.json();

            if (checkJson.existe) {
                alert('⚠️ El código patrimonial ya existe. Por favor, usa uno diferente.');
                return;
            }

        } catch (err) {
            alert('Error verificando el código patrimonial: ' + err.message);
            console.error(err);
            return;
        }

        // Obtener campos específicos
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

        const datosDisp = {
            codigo_patrimonial: cod,
            id_area: area,
            estado,
            observaciones: observacion,
            tipo_dispositivo: tipoID
        };

        try {
            // Guardar dispositivo general
            let resp = await fetch('../Backend/dispositivos/guardar_dispositivo.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(datosDisp)
            });

            const jsonDisp = await resp.json();
            if (!resp.ok || !jsonDisp.success) {
                throw new Error(jsonDisp.message || 'Error guardando dispositivo general');
            }

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

            const jsonDet = await resp.json();
            if (!resp.ok || !jsonDet.success) {
                throw new Error(jsonDet.message || 'Error guardando detalle del dispositivo');
            }

            alert('✅ Dispositivo y detalle guardados correctamente.');

            limpiarCampos();

            const modal = document.getElementById('modalAgregarDispositivo');
            if (modal) {
                const modalBootstrap = bootstrap.Modal.getInstance(modal);
                if (modalBootstrap) modalBootstrap.hide();
            }

            await actualizarTabla();
            filtrarYPaginar(1);

        } catch (error) {
            alert('🚫 Error al guardar dispositivo: ' + error.message);
            console.error(error);
        }

    });

    function limpiarCampos() {
        const camposGenerales = ['cod_patrimonial', 'modalArea', 'crearEstado', 'observaciones', 'modalTipoDispositivo'];
        camposGenerales.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        const contenedores = document.querySelectorAll('[id^="campos"]');
        contenedores.forEach(cont => {
            cont.classList.add('d-none');
            const inputs = cont.querySelectorAll('input, textarea');
            inputs.forEach(input => input.value = '');
        });

        const tipoSelect = document.getElementById('modalTipoDispositivo');
        if (tipoSelect) {
            tipoSelect.selectedIndex = 0;
            tipoSelect.dispatchEvent(new Event('change'));
        }

        const estadoSelect = document.getElementById('crearEstado');
        if (estadoSelect) estadoSelect.selectedIndex = 0;
    }
});
