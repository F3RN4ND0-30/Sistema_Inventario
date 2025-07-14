document.addEventListener('DOMContentLoaded', () => {
    const tipoSelect = document.getElementById('modalTipoDispositivo');
    const camposCPU = document.getElementById('camposCPU');
    const camposCelular = document.getElementById('camposCelular');
    const camposLaptop = document.getElementById('camposLaptop');
    const camposTablet = document.getElementById('camposTablet');
    const camposServidor = document.getElementById('camposServidor');
    const camposImpresora = document.getElementById('camposImpresora');
    const camposTeclado = document.getElementById('camposTeclado');
    const camposEscaner = document.getElementById('camposEscaner');
    const camposPantalla = document.getElementById('camposPantalla');
    const camposMouse = document.getElementById('camposMouse');
    const camposBiometrico = document.getElementById('camposBiometrico');
    const camposLector = document.getElementById('camposLector');
    const camposEstabilizador = document.getElementById('camposEstabilizador');
    const camposRouter = document.getElementById('camposRouter');
    const camposSwitch = document.getElementById('camposSwitch');
    const camposRadio = document.getElementById('camposRadio');
    const camposCamaras = document.getElementById('camposCamaras');
    const camposNVR = document.getElementById('camposNVR');

    tipoSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex].text.toLowerCase();

        // Ocultar todos
        camposCPU.classList.add('d-none');
        camposCelular.classList.add('d-none');
        camposLaptop.classList.add('d-none');
        camposTablet.classList.add('d-none');
        camposServidor.classList.add('d-none');
        camposImpresora.classList.add('d-none');
        camposTeclado.classList.add('d-none');
        camposEscaner.classList.add('d-none');
        camposPantalla.classList.add('d-none');
        camposMouse.classList.add('d-none');
        camposBiometrico.classList.add('d-none');
        camposLector.classList.add('d-none');
        camposEstabilizador.classList.add('d-none');
        camposRouter.classList.add('d-none');
        camposSwitch.classList.add('d-none');
        camposRadio.classList.add('d-none');
        camposCamaras.classList.add('d-none');
        camposNVR.classList.add('d-none');

        // Mostrar los campos según el tipo seleccionado
        if (selected.includes('cpu')) {
            camposCPU.classList.remove('d-none');
        } else if (selected.includes('celular')) {
            camposCelular.classList.remove('d-none');
        } else if (selected.includes('laptop')){
            camposLaptop.classList.remove('d-none');
        } else if (selected.includes('tablet')){
            camposTablet.classList.remove('d-none');
        } else if (selected.includes('servidor')){
            camposServidor.classList.remove('d-none');
        } else if (selected.includes('impresora')){
            camposImpresora.classList.remove('d-none');
        } else if (selected.includes('teclado')){
            camposTeclado.classList.remove('d-none');
        } else if (selected.includes('escaner')){
            camposEscaner.classList.remove('d-none');
        } else if (selected.includes('pantalla')){
            camposPantalla.classList.remove('d-none');
        } else if (selected.includes('mouse')){
            camposMouse.classList.remove('d-none');
        } else if (selected.includes('biometrico')){
            camposBiometrico.classList.remove('d-none');
        } else if (selected.includes('lector')){
            camposLector.classList.remove('d-none');
        } else if (selected.includes('estabilizador')){
            camposEstabilizador.classList.remove('d-none');
        } else if (selected.includes('router')){
            camposRouter.classList.remove('d-none');
        } else if (selected.includes('switch')){
            camposSwitch.classList.remove('d-none');
        } else if (selected.includes('radio')){
            camposRadio.classList.remove('d-none');
        } else if (selected.includes('camaras')){
            camposCamaras.classList.remove('d-none');
        } else if (selected.includes('nvr')){
            camposNVR.classList.remove('d-none');
        }
    });
});