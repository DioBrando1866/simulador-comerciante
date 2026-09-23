<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'includes/db.php';

$stmt =$pdo->query("SELECT * FROM usuarios LIMIT 1");
$usuario =$stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $passwordTemp = password_hash('1234', PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, password, dinero) VALUES (?, ?, ?)");
    $stmt->execute(['Javier', $passwordTemp, 500.00]);
    
    $stmt = $pdo->query("SELECT * FROM usuarios LIMIT 1");
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}

$catalogoStmt =$pdo->query("SELECT * FROM catalogo_objetos");
$objetosCatalogo =$catalogoStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulador de Comerciante - Mostrador</title>
   <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>

    <header>
        <div>
            <h1>Tienda de Empeños / Comerciante</h1>
            <p>Bienvenido al mostrador, <strong><?php echo htmlspecialchars($usuario['nombre']); ?></strong></p>
        </div>
        <div class="stats">
            <div>Día: <span><?php echo $usuario['dia_actual']; ?></span></div>
            <div>Dinero: <span><?php echo number_format($usuario['dinero'], 2); ?> €</span></div>
        </div>
    </header>

    <div class="container">
        <div class="panel">
            <h2>El Mostrador</h2>
            <p>Esperando a que llegue un cliente...</p>
            <div id="cliente-zona" style="background: #252525; padding: 20px; border-radius: 6px; text-align: center; margin-top: 20px;">
                <em>No hay clientes en este momento.</em>
                <br><br>
                <button onclick="simularCliente()">Hacer venir a un cliente</button>
            </div>
        </div>

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

    <script src="/assets/js/game.js"></script> 
</body>
</html>