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
    const noClientMsg = document.getElementById('noClientMessage');
    const clientContainer = document.getElementById('clientContainer');

    // 1. Ocultar mensaje de vacío y abrir puertas
    if (noClientMsg) {
        noClientMsg.style.display = 'none';
    }
    counterBox.classList.add('opening');

    // 2. Generar retrato aleatorio (hoja 1-29, celda 0-7)
    const numeroHoja = Math.floor(Math.random() * 29) + 1;
    const indexRetrato = Math.floor(Math.random() * 8);
    
    const columnas = 4;
    const filas = 2;
    const col = indexRetrato % columnas;
    const fila = Math.floor(indexRetrato / columnas);
    
    const posX = (col / (columnas - 1)) * 100;
    const posY = (fila / (filas - 1)) * 100;

    const portraitElement = document.getElementById('clientPortrait');
    portraitElement.style.backgroundImage = `url('assets/portraits/Cyberportraits transparent (${numeroHoja}).png')`;
    portraitElement.style.backgroundSize = `${columnas * 100}% ${filas * 100}%`;
    portraitElement.style.backgroundPosition = `${posX}% ${posY}%`;

    // 3. Mostrar el contenedor del cliente y activar botones tras la animación de las puertas
    setTimeout(() => {
        clientContainer.style.display = 'flex';
        document.getElementById('negotiationButtons').style.display = 'flex';
        document.getElementById('clientName').innerText = "Ciudadano del Sector";
        document.getElementById('clientDialogueText').innerText = '"Escúchame, necesito vender esto rápido antes de que la corporación me localice."';
    }, 750);
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