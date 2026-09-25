function actualizarPrecio(objetoId, inputElement) {
    let valor = inputElement.value.replace(',', '.');
    let numero = parseFloat(valor);
    if (isNaN(numero) || numero < 0) {
        numero = 0;
    }
    inputElement.value = numero.toFixed(2);
    console.log(`[TASACIÓN] Objeto ID ${objetoId} actualizado a ${inputElement.value} €`);
}

// Secuencia al llamar a un cliente
function llamarCliente() {
    const counterBox = document.getElementById('counterBox');
    const counterText = document.getElementById('counterText');
    const btnLlamar = document.getElementById('btnLlamarCliente');

    // Desactivar el botón temporalmente para evitar spam
    if (btnLlamar) btnLlamar.disabled = true;

    // 1. Desvanecer el texto poco a poco
    if (counterText) counterText.classList.add('fade-out');

    // 2. Tras un breve momento, abrir las puertas del fondo por la mitad
    setTimeout(() => {
        counterBox.classList.add('opening');
    }, 600);

    // 3. Mostrar la burbuja de texto pixelada con el placeholder cuando termine la apertura
    setTimeout(() => {
        mostrarBurbujaDialogo("... (El cliente cruza el umbral y espera en silencio a que le hables) ...");
    }, 1500);
}

function mostrarBurbujaDialogo(textoPlaceholder) {
    const counterBox = document.getElementById('counterBox');
    
    // Evitar duplicados si ya hay una burbuja
    const burbujaExistente = document.getElementById('dialogueBubble');
    if (burbujaExistente) burbujaExistente.remove();

    // Crear la burbuja de diálogo
    const bubble = document.createElement('div');
    bubble.id = 'dialogueBubble';
    bubble.className = 'dialogue-bubble';
    bubble.innerText = textoPlaceholder;

    counterBox.appendChild(bubble);

    // Forzar reflow y activar la transición de aparición
    setTimeout(() => {
        bubble.classList.add('show');
    }, 50);
}