// Contiene toda la lógica para el teléfono web usando SIP.js - VERSIÓN CORREGIDA

// --- 1. CONFIGURACIÓN Y ELEMENTOS DEL DOM ---
const SERVER_IP = 'pbxnext.controlnextapp.com';

const softphoneModalEl = document.getElementById('softphoneModal');
const softphoneModal = new bootstrap.Modal(softphoneModalEl);
const display = document.getElementById('softphone-display');
const callButton = document.getElementById('call-button');
const hangupButton = document.getElementById('hangup-button');
const callStatus = document.getElementById('call-status');

let userAgent;
let currentSession;

// --- 2. LÓGICA DEL SOFTPHONE CORREGIDA ---

function initializeSipAgent() {
    if (!sipUser || !sipPassword) {
        console.error("Error: Las credenciales SIP no están definidas.");
        updateCallStatus('Error de Credenciales', 'status-disconnected');
        return;
    }

    // Crear la URI correctamente
    const uri = SIP.UserAgent.makeURI(`sip:${sipUser}@${SERVER_IP}`);
    if (!uri) {
        console.error(`URI inválida: sip:${sipUser}@${SERVER_IP}`);
        updateCallStatus('Error de Configuración', 'status-disconnected');
        return;
    }

    const userAgentOptions = {
        uri: uri,
        transportOptions: {
            server: `wss://${SERVER_IP}:8089/ws`,
            traceSip: true
        },
        // CORRECCIÓN: Usar displayName en lugar de authorizationUser
        displayName: sipUser,
        authorizationUsername: sipUser,
        password: sipPassword,
        // NUEVAS OPCIONES PARA MEJORAR COMPATIBILIDAD
        sessionDescriptionHandlerFactoryOptions: {
            constraints: {
                audio: true,
                video: false
            },
            // Configuración de RTC mejorada
            peerConnectionConfiguration: {
                iceServers: [
                    { urls: 'stun:stun.l.google.com:19302' }
                ]
            }
        }
    };

    userAgent = new SIP.UserAgent(userAgentOptions);

    // Mejorar el manejo de estados
    userAgent.stateChange.addListener((newState) => {
        console.log(`Estado del agente: ${newState}`);
        switch (newState) {
            case SIP.UserAgentState.Started:
                updateCallStatus('Listo para llamar', 'status-connected');
                break;
            case SIP.UserAgentState.Stopped:
                updateCallStatus('Desconectado', 'status-disconnected');
                break;
            case SIP.UserAgentState.Starting:
                updateCallStatus('Conectando...', 'status-connecting');
                break;
        }
    });

    // Manejar llamadas entrantes
    userAgent.delegate = {
        onInvite: (invitation) => {
            console.log('Llamada entrante recibida');
            currentSession = invitation;
            setupIncomingCall(invitation);
        }
    };

    // Iniciar con mejor manejo de errores
    userAgent.start().catch(error => {
        console.error("Fallo al iniciar el agente SIP:", error);
        updateCallStatus('Error de conexión', 'status-disconnected');
        
        // Información adicional para debug
        if (error.message && error.message.includes('401')) {
            updateCallStatus('Error de autenticación', 'status-disconnected');
        } else if (error.message && error.message.includes('timeout')) {
            updateCallStatus('Timeout de conexión', 'status-disconnected');
        }
    });
}

function setupIncomingCall(invitation) {
    // Configurar eventos para llamada entrante
    invitation.stateChange.addListener((newState) => {
        console.log(`Estado de llamada entrante: ${newState}`);
        handleCallStateChange(newState);
    });
}

function showPhone(number) {
    display.textContent = number || '';
    softphoneModal.show();
}

function pressKey(key) {
    display.textContent += key;
}

function backspace() {
    const currentText = display.textContent;
    display.textContent = currentText.slice(0, -1);
}

function makeCall() {
    const targetNumber = display.textContent;
    
    if (!targetNumber) {
        console.warn("No hay número para marcar.");
        updateCallStatus('Ingrese un número', 'status-disconnected');
        return;
    }

    if (!userAgent || userAgent.state !== SIP.UserAgentState.Started) {
        console.warn("El agente no está conectado.");
        updateCallStatus('No conectado', 'status-disconnected');
        return;
    }

    // VALIDACIÓN MEJORADA PARA NÚMEROS DE COLOMBIA
    if (!isValidColombianNumber(targetNumber)) {
        console.warn("Formato de número no válido.");
        updateCallStatus('Número inválido', 'status-disconnected');
        display.classList.add('shake');
        setTimeout(() => display.classList.remove('shake'), 500);
        return;
    }

    // Crear URI de destino
    const targetUri = SIP.UserAgent.makeURI(`sip:${targetNumber}@${SERVER_IP}`);
    if (!targetUri) {
        console.error("No se pudo crear URI de destino.");
        updateCallStatus('Error de marcación', 'status-disconnected');
        return;
    }

    // Opciones mejoradas para la llamada
    const inviterOptions = {
        sessionDescriptionHandlerOptions: {
            constraints: { 
                audio: true, 
                video: false 
            },
            peerConnectionConfiguration: {
                iceServers: [
                    { urls: 'stun:stun.l.google.com:19302' }
                ]
            }
        }
    };
    
    try {
        currentSession = new SIP.Inviter(userAgent, targetUri, inviterOptions);
        setupOutgoingCall(currentSession);

        // Iniciar la llamada
        currentSession.invite().catch(error => {
            console.error("Error al iniciar llamada:", error);
            updateCallStatus('Error al llamar', 'status-disconnected');
            resetCallButtons();
        });

    } catch (error) {
        console.error("Error creando sesión:", error);
        updateCallStatus('Error de configuración', 'status-disconnected');
    }
}

function setupOutgoingCall(session) {
    session.stateChange.addListener((newState) => {
        console.log(`Estado de la llamada saliente: ${newState}`);
        handleCallStateChange(newState);
        
        // Manejar eventos de medios solo cuando la sesión esté estableciendo
        if (newState === SIP.SessionState.Establishing && session.sessionDescriptionHandler) {
            try {
                session.sessionDescriptionHandler.on('setLocalDescription', () => {
                    console.log('Descripción local establecida');
                });

                session.sessionDescriptionHandler.on('setRemoteDescription', () => {
                    console.log('Descripción remota establecida');
                });
            } catch (error) {
                console.log('Error configurando eventos de medios:', error);
            }
        }
    });
}

function handleCallStateChange(newState) {
    switch (newState) {
        case SIP.SessionState.Initial:
            updateCallStatus('Iniciando...', 'status-connecting');
            break;
        case SIP.SessionState.Establishing:
            updateCallStatus('Llamando...', 'status-connecting');
            callButton.disabled = true;
            hangupButton.disabled = false;
            break;
        case SIP.SessionState.Established:
            updateCallStatus('Llamada Conectada', 'status-connected');
            callButton.disabled = true;
            hangupButton.disabled = false;
            break;
        case SIP.SessionState.Terminating:
            updateCallStatus('Finalizando...', 'status-connecting');
            break;
        case SIP.SessionState.Terminated:
            updateCallStatus('Llamada Finalizada', 'status-disconnected');
            resetCallButtons();
            currentSession = null;
            break;
    }
}

function resetCallButtons() {
    callButton.disabled = false;
    hangupButton.disabled = true;
}

function hangupCall() {
    if (currentSession && currentSession.state !== SIP.SessionState.Terminated) {
        try {
            if (currentSession.state === SIP.SessionState.Established) {
                currentSession.bye();
            } else {
                currentSession.cancel();
            }
        } catch (error) {
            console.error("Error al colgar:", error);
        }
    }
}

function updateCallStatus(text, className) {
    callStatus.textContent = text;
    callStatus.className = 'text-center fw-bold mb-2 p-2 rounded ' + className;
}

// NUEVA FUNCIÓN: Validación mejorada para números colombianos
function isValidColombianNumber(number) {
    // Remover espacios y caracteres especiales
    const cleanNumber = number.replace(/[\s\-\(\)]/g, '');
    
    // Validaciones para diferentes formatos de números colombianos:
    // - Celulares: 3xxxxxxxxx (10 dígitos)
    // - Fijos Bogotá: 1xxxxxxx (8 dígitos) o 601xxxxxxx (10 dígitos)
    // - Fijos otras ciudades: ejemplo 4xxxxxxx (8 dígitos) o 604xxxxxxx (10 dígitos)
    // - Con código país: +57xxxxxxxxxx o 57xxxxxxxxxx
    
    const patterns = [
        /^3\d{9}$/,           // Celular 10 dígitos
        /^[1-8]\d{7}$/,       // Fijo 8 dígitos
        /^60[1-8]\d{7}$/,     // Fijo con código área 10 dígitos
        /^\+?57[1-8]\d{9}$/,  // Con código país +57 o 57
        /^\+?573\d{9}$/       // Celular con código país
    ];
    
    return patterns.some(pattern => pattern.test(cleanNumber));
}

// --- 3. INICIALIZACIÓN ---
document.addEventListener('DOMContentLoaded', () => {
    console.log('Inicializando softphone...');
    console.log('Usuario SIP:', sipUser);
    console.log('Servidor:', SERVER_IP);
    
    // Verificar que las librerías estén cargadas
    if (typeof SIP === 'undefined') {
        console.error('SIP.js no está cargado');
        updateCallStatus('Error de librería', 'status-disconnected');
        return;
    }
    
    initializeSipAgent();
});