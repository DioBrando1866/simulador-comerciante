function actualizarPrecio(objetoId, inputElement) {
    let valor = inputElement.value.replace(',', '.');
    let numero = parseFloat(valor);
    if (isNaN(numero) || numero < 0) {
        numero = 0;
    }
    inputElement.value = numero.toFixed(2);
    console.log(`[TASACIÓN] Objeto ID ${objetoId} actualizado a ${inputElement.value} €`);
}

function llamarCliente() {
    const counterBox = document.getElementById('counterBox');
    const counterText = document.getElementById('counterText');
    const btnLlamar = document.getElementById('btnLlamarCliente');

    if (btnLlamar) btnLlamar.disabled = true;

    if (counterText) {
        counterText.classList.add('fade-out');
    }

    setTimeout(() => {
        if (counterBox) {
            counterBox.classList.add('opening');
        }
    }, 400);

    setTimeout(() => {
        mostrarBurbujaDialogo("... (El cliente se acerca al mostrador y te observa en silencio) ...");
    }, 1200);
}

function mostrarBurbujaDialogo(textoPlaceholder) {
    const counterBox = document.getElementById('counterBox');
    if (!counterBox) return;
    
    const burbujaExistente = document.getElementById('dialogueBubble');
    if (burbujaExistente) {
        burbujaExistente.remove();
    }

    const bubble = document.createElement('div');
    bubble.id = 'dialogueBubble';
    bubble.className = 'dialogue-bubble';
    bubble.innerText = textoPlaceholder;

    counterBox.appendChild(bubble);

    setTimeout(() => {
        bubble.classList.add('show');
    }, 50);
}

function abrirMenuPausa() {
    const modal = document.getElementById('pauseModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function cerrarMenuPausa() {
    const modal = document.getElementById('pauseModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function guardarPartida() {
    alert("Partida guardada correctamente (Simulación).");
    cerrarMenuPausa();
}

function cargarPartida() {
    alert("No hay partidas guardadas recientes.");
}

function abrirOpciones() {
    alert("Panel de opciones en desarrollo.");
}