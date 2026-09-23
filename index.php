<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'includes/db.php';

// Para pruebas rápidas hoy: pillamos el primer usuario de la tabla o creamos uno temporal
$stmt =$pdo->query("SELECT * FROM usuarios LIMIT 1");
$usuario =$stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    // Hasheamos una contraseña temporal por defecto
    $passwordTemp = password_hash('1234', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, password, dinero) VALUES (?, ?, ?)");
    $stmt->execute(['Javier', $passwordTemp, 500.00]);
    
    $stmt = $pdo->query("SELECT * FROM usuarios LIMIT 1");
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtenemos el catálogo de objetos para mostrar qué cosas pueden entrar en la tienda
$catalogoStmt =$pdo->query("SELECT * FROM catalogo_objetos");
$objetosCatalogo =$catalogoStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador de Comerciante - Mostrador</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            margin: 0;
            padding: 20px;
        }
        header {
            display: flex;
            justify-content: space-between;
            background: #1e1e1e;
            padding: 15px 25px;
            border-radius: 8px;
            border: 1px solid #333;
            margin-bottom: 20px;
        }
        .stats span {
            margin-left: 20px;
            font-weight: bold;
            color: #4CAF50;
        }
        .container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        .panel {
            background: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #333;
            min-height: 350px;
        }
        h2 { border-bottom: 2px solid #333; padding-bottom: 10px; margin-top: 0; }
        .item-card {
            background: #2a2a2a;
            padding: 10px 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        button {
            background: #ff9800;
            border: none;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover { background: #f57c00; }
    </style>
</head>
<body>

    <header>
        <div>
            <h1>🏪 Tienda de Empeños / Comerciante</h1>
            <p>Bienvenido al mostrador, <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong></p>
        </div>
        <div class="stats">
            <div>Día: <span><?php echo $usuario['dia_actual']; ?></span></div>
            <div>Dinero: <span><?php echo number_format($usuario['dinero'], 2); ?> €</span></div>
        </div>
    </header>

    <div class="container">
        <!-- Panel Izquierdo: El Mostrador / Acción Actual -->
        <div class="panel">
            <h2>El Mostrador</h2>
            <p>Esperando a que llegue un cliente...</p>
            <div id="cliente-zona" style="background: #252525; padding: 20px; border-radius: 6px; text-align: center; margin-top: 20px;">
                <em>No hay clientes en este momento.</em>
                <br><br>
                <button onclick="simularCliente()">Hacer venir a un cliente</button>
            </div>
        </div>

        <!-- Panel Derecho: Catálogo / Inventario rápido -->
        <div class="panel">
            <h2>Objetivos del Mercado</h2>
            <div style="max-height: 300px; overflow-y: auto;">
                <?php foreach ($objetosCatalogo as$obj): ?>
                    <div class="item-card">
                        <div>
                            <strong><?php echo htmlspecialchars($obj['nombre']); ?></strong><br>
                            <small style="color: #aaa;"><?php echo htmlspecialchars($obj['descripcion']); ?></small>
                        </div>
                        <div>
                            <span><?php echo number_format($obj['valor_base'], 2); ?> €</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        function simularCliente() {
            // Esto lo conectaremos luego con JS y PHP para generar tratos aleatorios
            const zona = document.getElementById('cliente-zona');
            zona.innerHTML = `
                <p><strong>¡Ha entrado un cliente sospechoso!</strong> Te ofrece un objeto.</p>
                <button style="background: #4CAF50;" onclick="alert('¡Pronto programaremos la compra!')">Negociar</button>
                <button style="background: #f44336;" onclick="location.reload()">Rechazar</button>
            `;
        }
    </script>

</body>
</html>