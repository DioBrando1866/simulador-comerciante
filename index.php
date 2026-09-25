<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'includes/db.php';

// Consulta para obtener al usuario (Javier)
$stmt = $pdo->query("SELECT * FROM usuarios LIMIT 1");
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $passwordTemp = password_hash('1234', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, password, dinero) VALUES (?, ?, ?)");
    $stmt->execute(['Javier', $passwordTemp, 500.00]);
    
    $stmt = $pdo->query("SELECT * FROM usuarios LIMIT 1");
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Obtener catálogo de objetos
$stmtCatalogo = $pdo->query("SELECT * FROM catalogo_objetos");
$objetosCatalogo = $stmtCatalogo->fetchAll(PDO::FETCH_ASSOC);

// Obtener inventario actual del usuario
$stmtInventario = $pdo->prepare("
    SELECT i.*, c.nombre, c.descripcion 
    FROM inventario i 
    JOIN catalogo_objetos c ON i.objeto_id = c.id 
    WHERE i.usuario_id = ?
");
$stmtInventario->execute([$usuario['id']]);
$inventarioJugador = $stmtInventario->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casa de Empeños - Simulador</title>
    <link rel="stylesheet" href="assets/css/style.css?v=4">
</head>
<body>
    <div class="main-container">
        <!-- Cabecera con estadísticas y botón de acción principal -->
        <header>
            <h1>⚖️ Oficina de Empeños</h1>
            <div class="header-right">
                <!-- Botón conectado a la función JS -->
                <button id="btnLlamarCliente" onclick="llamarCliente()">Llamar a un cliente</button>
                <div class="stats">
                    <div class="stat-badge-vintage">Jornada: <span>Día <?php echo $usuario['dia_actual']; ?></span></div>
                    <div class="stat-badge-vintage">Capital: <span><?php echo number_format($usuario['dinero'], 2, ',', '.'); ?> €</span></div>
                </div>
            </div>
        </header>

        <!-- Contenido Principal -->
        <div class="container">
            <!-- Panel izquierdo partido por la mitad -->
            <div class="panel">
                <div class="left-panel-split">
                    <!-- Mitad Superior: Mostrador / Cliente -->
                    <div class="client-section">
                        <h2>Mostrador de Atención</h2>
                        <div class="counter-box" id="counterBox">
                            <!-- Puertas que se abrirán por la mitad -->
                            <div class="door-panel door-left"></div>
                            <div class="door-panel door-right"></div>

                            <!-- Texto que se desvanece -->
                            <p id="counterText" class="counter-text">El local está en silencio. Nadie ha cruzado la puerta todavía...</p>
                        </div>
                    </div>

                    <!-- Mitad Inferior: Cuadrícula de Inventario -->
                    <div class="inventory-section">
                        <h2>Inventario de la Tienda (Almacén)</h2>
                        <div class="inventory-grid">
                            <?php if (empty($inventarioJugador)): ?>
                                <?php for ($i = 0; $i < 8; $i++): ?>
                                    <div class="inventory-slot">[ Vacío ]</div>
                                <?php endfor; ?>
                            <?php else: ?>
                                <?php foreach ($inventarioJugador as $item): ?>
                                    <div class="inventory-slot" title="<?php echo htmlspecialchars($item['nombre']); ?>">
                                        <?php echo htmlspecialchars($item['nombre']); ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel derecho: Catálogo del Mercado -->
            <div class="panel">
                <h2>Catálogo de Tasación</h2>
                <div class="market-scroll">
                    <?php foreach ($objetosCatalogo as $obj): ?>
                        <div class="item-card" data-id="<?php echo $obj['id']; ?>">
                            <div class="item-info">
                                <h4><?php echo htmlspecialchars($obj['nombre']); ?></h4>
                                <p title="<?php echo htmlspecialchars($obj['descripcion']); ?>">
                                    <?php echo htmlspecialchars($obj['descripcion']); ?>
                                </p>
                            </div>
                            <div class="price-input-wrapper">
                                <input type="text"
                                       inputmode="decimal"
                                       class="item-price-input" 
                                       value="<?php echo number_format($obj['valor_base'], 2, '.', ''); ?>"
                                       step="0.50" 
                                       min="0"
                                       onblur="actualizarPrecio(<?php echo $obj['id']; ?>, this)"
                                       onchange="actualizarPrecio(<?php echo $obj['id']; ?>, this)">
                                <span class="currency-symbol">€</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Archivo JS apuntando a game.js con versión para evitar caché -->
    <script src="assets/js/game.js?v=2"></script>
</body>
</html>