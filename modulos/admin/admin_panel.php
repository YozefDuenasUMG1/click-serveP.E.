<?php
// Iniciar sesión (necesario para el navbar)
session_start();
// Puedes forzar el rol de admin para propósitos de demostración
$_SESSION['nombre_usuario'] = 'Admin'; // Esto es temporal, en producción usarías tu sistema real de autenticación
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir los mismos estilos que usa tu navbar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <style>
        body {
            padding-top: 70px; /* Ajuste para el navbar fijo */
        }
        .admin-panel {
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Incluir el navbar -->
    <?php include '../cliente/navbar.php'; ?>

    <!-- Contenido del panel -->
    <div class="container admin-panel py-5">
        <h1 class="text-center mb-4">Panel de Administrador</h1>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Gestión de Menú</h5>
                        <a href="../../restaurante/admin_menu.php" class="btn btn-primary mt-3">Administrar Productos</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Sistema de Pedidos</h5>
                        <a href="/new_sitem_pedido/modulos/cliente/index.php" class="btn btn-success mt-3">Ver Menú Cliente</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Gestión de Usuarios</h5>
                        <a href="userscontrol.html" class="btn btn-info mt-3">Administrar Usuarios</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Cocina</h5>
                        <a href="/new_sitem_pedido/cocina.html" class="btn btn-warning mt-3">Ver Cocina</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Dashboard</h5>
                        <a href="../../dashboard/analytics/stadistics.html" class="btn btn-secondary mt-3">Ver Estadísticas</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">Módulo de Cajero</h5>
                        <a href="../cajero/cajero_panel.html" class="btn btn-dark mt-3">Acceder a Cajero</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Scripts del navbar si son necesarios -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>