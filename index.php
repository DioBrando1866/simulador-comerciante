<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oficina de Empeños - Menú Principal</title>
    <link rel="stylesheet" href="assets/css/style.css?v=5">
</head>
<body class="menu-body">
    <div class="menu-container">
        <div class="menu-header">
            <h1>Oficina de Empeños</h1>
            <p>Simulador de Tasación & Trueques Clandestinos</p>
        </div>

        <div class="menu-box">
            <button class="menu-btn" onclick="location.href='game.php'">Nueva Partida</button>
            <button class="menu-btn" onclick="alert('Funcionalidad de carga en desarrollo')">Cargar Partida</button>
            <button class="menu-btn" onclick="alert('Opciones de audio y pantalla próximamente')">Opciones</button>
        </div>
    </div>
</body>
</html>