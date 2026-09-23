function simularCliente() {
            // Esto lo conectaremos luego con JS y PHP para generar tratos aleatorios
            const zona = document.getElementById('cliente-zona');
            zona.innerHTML = `
                <p><strong>¡Ha entrado un cliente sospechoso!</strong> Te ofrece un objeto.</p>
                <button style="background: #4CAF50;" onclick="alert('¡Pronto programaremos la compra!')">Negociar</button>
                <button style="background: #f44336;" onclick="location.reload()">Rechazar</button>
            `;
        }