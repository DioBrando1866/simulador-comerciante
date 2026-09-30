<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'includes/db.php';

$stmt = $pdo->query("SELECT * FROM usuarios LIMIT 1");
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $passwordTemp = password_hash('1234', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, password, dinero) VALUES (?, ?, ?)");
    $stmt->execute(['Javier', $passwordTemp, 500.00]);
    
    $stmt = $pdo->query("SELECT * FROM usuarios LIMIT 1");
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
}

$stmtCatalogo = $pdo->query("SELECT * FROM catalogo_objetos");
$objetosCatalogo = $stmtCatalogo->fetchAll(PDO::FETCH_ASSOC);

$stmtInventario = $pdo->prepare("
    SELECT i.*, c.nombre, c.descripcion, c.imagen 
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
        <header>
            <h1>Oficina de Empeños</h1>
            <div class="header-right">
                 <button onclick="abrirMenuPausa()" style="background: #24201c; border-color: var(--border-vintage); color: var(--text-muted);">Menú</button>
                <button id="btnLlamarCliente" onclick="llamarCliente()">Llamar a un cliente</button>
                <div class="stats">
                    <div class="stat-badge-vintage">Jornada: <span>Día <?php echo $usuario['dia_actual']; ?></span></div>
                    <div class="stat-badge-vintage">Capital: <span><?php echo number_format($usuario['dinero'], 2, ',', '.'); ?> €</span></div>
                </div>
            </div>
        </header>

        <div class="container">
            <div class="panel">
                <div class="left-panel-split">
                    <div class="client-section">
                        <h2>Mostrador de Atención</h2>
                        <div class="counter-box" id="counterBox">
                            <!-- Puertas correderas de animación -->
                            <div class="counter-door door-left"></div>
                            <div class="counter-door door-right"></div>

                            <!-- Contenedor principal del cliente (alineado a la izquierda) -->
                            <div id="clientContainer" class="client-container" style="display: none;">
                                <div class="client-portrait-frame">
                                    <div id="clientPortrait" class="client-portrait-sprite"></div>
                                </div>
                                
                                <!-- Burbuja de diálogo flotante -->
                                <div class="dialogue-bubble" id="dialogueBubble">
                                    <h3 id="clientName">Ciudadano del Sector</h3>
                                    <p id="clientDialogueText">"Escúchame, necesito vender esto rápido..."</p>
                                    <div class="negotiation-buttons" id="negotiationButtons" style="display: none;">
                                        <button class="btn-action btn-accept" onclick="aceptarTrato()">Aceptar Trato</button>
                                        <button class="btn-action btn-reject" onclick="rechazarTrato()">Rechazar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                        <?php 
                                            $imagenItem = !empty($item['imagen']) ? $item['imagen'] : 'Icon14_01.png'; 
                                        ?>
                                        <img src="assets/icons/<?php echo htmlspecialchars($imagenItem); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>" class="slot-icon">
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <h2>Catálogo de Tasación</h2>
                <div class="market-scroll">
                    <?php foreach ($objetosCatalogo as $obj): ?>
                        <div class="item-card" data-id="<?php echo $obj['id']; ?>">
                            <?php 
                                $imagenObj = !empty($obj['imagen']) ? $obj['imagen'] : 'Icon14_01.png'; 
                            ?>
                            <img src="assets/icons/<?php echo htmlspecialchars($imagenObj); ?>" alt="" class="item-icon">
                            
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
    
<div id="pauseModal" class="modal-overlay">
    <div class="modal-box">
        <h2>Menú de Pausa</h2>
        <div class="modal-buttons">
            <button class="menu-btn" onclick="guardarPartida()">Guardar Partida</button>
            <button class="menu-btn" onclick="cargarPartida()">Cargar Partida</button>
            <button class="menu-btn" onclick="abrirOpciones()">Opciones</button>
            <button class="menu-btn btn-danger" onclick="location.href='index.php'">Volver al Menú</button>
            <button class="menu-btn btn-secondary" onclick="cerrarMenuPausa()">Reanudar</button>
        </div>
    </div>
</div>
    
    <script src="assets/js/game.js?v=2"></script>
</body>
</html>