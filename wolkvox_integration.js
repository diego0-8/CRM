// --- Archivo: views/js/wolkvox_integration.js (VERSIÓN FINAL Y COMPLETA) ---
// Contiene todas las funciones para interactuar con la API Local de Agente de Wolkvox.

// --- CONFIGURACIÓN GLOBAL ---
const wolkvoxAgentUrl = 'http://localhost:8080/apiagentbox';

/**
 * Función genérica para enviar comandos al agente de Wolkvox.
 * @param {string} action - La acción a realizar (ej. 'dial', 'haup', 'hold').
 * @param {object} params - Un objeto con los parámetros adicionales para la URL.
 * @param {function} callback - Una función para manejar la respuesta exitosa.
 * @param {function} errorCallback - Una función para manejar los errores.
 */
function enviarComandoWolkvox(action, params = {}, callback, errorCallback) {
    let queryString = `action=${action}`;
    for (const key in params) {
        // Codificamos los parámetros para asegurar que los nombres con espacios se envíen correctamente.
        queryString += `&${key}=${encodeURIComponent(params[key])}`;
    }
    const fullUrl = `${wolkvoxAgentUrl}?${queryString}&callback=?`;

    console.log("Enviando comando a Wolkvox:", fullUrl);

    $.getJSON(fullUrl, function(response) {
        console.log(`Respuesta de Wolkvox para la acción '${action}':`, response);
        if (callback) callback(response);
    }).fail(function() {
        console.error(`Error al conectar con el agente de Wolkvox para la acción '${action}'.`);
        if (errorCallback) errorCallback();
        else alert('Error: No se pudo conectar con el agente de Wolkvox. Asegúrate de que la aplicación esté corriendo en tu PC.');
    });
}


/**
 * Inicia la llamada principal a un cliente.
 * @param {string} agenteId - El ID del agente/usuario en tu sistema.
 * @param {string} numeroCliente - El número de teléfono al que se va a llamar.
 * @param {HTMLElement} boton - El botón que fue presionado para dar feedback.
 */
function iniciarLlamada(agenteId, numeroCliente, boton) {
    const textoOriginal = boton.innerHTML;
    boton.disabled = true;
    boton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Conectando...`;

    const params = {
        phone: numeroCliente,
        id_customer: agenteId
    };

    enviarComandoWolkvox('dial', params, 
        (response) => { // Callback de éxito
            if (response && response.status) {
                alert(`Llamada iniciada. Estado: ${response.status}`);
            } else {
                alert('La llamada fue iniciada. Revisa el agente de Wolkvox.');
            }
            boton.disabled = false;
            boton.innerHTML = textoOriginal;
        },
        () => { // Callback de error
            boton.disabled = false;
            boton.innerHTML = textoOriginal;
        }
    );
}

/**
 * Cuelga la llamada activa.
 */
function colgarLlamada() {
    enviarComandoWolkvox('haup', {}, (response) => {
        alert('Comando "Colgar" enviado.');
    });
}

/**
 * Pone o quita la llamada de la espera (Hold).
 */
function ponerEnEspera() {
    enviarComandoWolkvox('hold', {}, (response) => {
        alert('Comando "Hold/Espera" enviado.');
    });
}

/**
 * Silencia o quita el silencio del micrófono del agente (Mute).
 */
function silenciarLlamada() {
    enviarComandoWolkvox('mute', {}, (response) => {
        alert('Comando "Mute/Silenciar" enviado.');
    });
}

/**
 * Pone al agente en estado "Ready" o "Listo para recibir llamadas".
 */
function marcarComoListo() {
    enviarComandoWolkvox('redy', {}, (response) => {
        alert('Agente puesto en estado "Ready".');
    });
}

/**
 * Realiza una llamada auxiliar a otro número.
 * @param {string} numeroAuxiliar - El número de teléfono al que se va a llamar.
 */
function realizarLlamadaAuxiliar(numeroAuxiliar) {
    if (!numeroAuxiliar || isNaN(numeroAuxiliar)) {
        alert("Por favor, ingresa un número de teléfono válido.");
        return;
    }
    const params = { phone: numeroAuxiliar };
    enviarComandoWolkvox('diax', params, (response) => {
        alert(`Iniciando llamada auxiliar a ${numeroAuxiliar}.`);
    });
}

/**
 * Cambia la visibilidad del Agente Wolkvox en el escritorio.
 * @param {boolean} visible - true para hacerlo visible, false para hacerlo invisible.
 */
function hacerAgenteVisible(visible) {
    const action = visible ? 'visi' : 'invi';
    enviarComandoWolkvox(action, {}, (response) => {
        alert(`El agente ahora es ${visible ? 'visible' : 'invisible'}.`);
    });
}

// --- NUEVAS FUNCIONES AÑADIDAS ---

/**
 * Pone al agente en estado ACW (After Call Work) para tipificar la llamada.
 */
function entrarEnACW() {
    enviarComandoWolkvox('acw', {}, (response) => {
        alert('Agente puesto en estado "ACW".');
    });
}

/**
 * Envía un tono de teclado DTMF durante una llamada (para navegar en un IVR).
 * @param {string} tecla - El dígito o símbolo a enviar (ej. '1', '5', '#').
 */
function enviarTonoDTMF(tecla) {
    const params = { key: tecla };
    enviarComandoWolkvox('keyp', params, (response) => {
        alert(`Tono DTMF '${tecla}' enviado.`);
    });
}

/**
 * Transfiere la llamada actual a otro número o extensión.
 * @param {string} numeroDestino - El número o extensión al que se transferirá la llamada.
 */
function transferirLlamada(numeroDestino) {
    if (!numeroDestino || isNaN(numeroDestino)) {
        alert("Por favor, ingresa un número de destino válido para la transferencia.");
        return;
    }
    const params = { phone: numeroDestino };
    enviarComandoWolkvox('tran', params, (response) => {
        alert(`Iniciando transferencia al número ${numeroDestino}.`);
    });
}

/**
 * Inicia una llamada principal a un cliente, adjuntando su nombre para futuras transferencias.
 * @param {string} agenteId - El ID del agente/usuario en tu sistema.
 * @param {string} numeroCliente - El número de teléfono al que se va a llamar.
 * @param {string} nombreCliente - El nombre completo del cliente.
 * @param {HTMLElement} boton - El botón que fue presionado para dar feedback.
 */
function iniciarLlamadaConDatos(agenteId, numeroCliente, nombreCliente, boton) {
    const textoOriginal = boton.innerHTML;
    boton.disabled = true;
    boton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Conectando...`;

    const params = {
        phone: numeroCliente,
        id_customer: agenteId,
        name_customer: nombreCliente
    };

    enviarComandoWolkvox('dia2', params, 
        (response) => { // Callback de éxito
            alert('Llamada con datos adicionales iniciada.');
            boton.disabled = false;
            boton.innerHTML = textoOriginal;
        },
        () => { // Callback de error
            boton.disabled = false;
            boton.innerHTML = textoOriginal;
        }
    );
}
