<?php
// Iniciar la sesión
session_start();

// Verificar si los datos de conexión están en la sesión
if (!isset($_SESSION['ip']) || !isset($_SESSION['username']) || !isset($_SESSION['password'])) {
    header('Location: login.php'); // Redirigir al login si no hay credenciales
    exit;
}

include_once("routeros_api.class.php");

$API = new RouterosAPI();

if ($API->connect($_SESSION['ip'], $_SESSION['username'], $_SESSION['password'])) {
    // Obtener la lista de usuarios PPPOE
    $API->write('/ppp/secret/print');
    $usuarios = $API->read();
    $API->disconnect();

    // Contadores para usuarios activos y deshabilitados
    $activos = 0;
    $inactivos = 0;

    foreach ($usuarios as $usuario) {
        if ($usuario['disabled'] === 'false') {
            $activos++;
        } else {
            $inactivos++;
        }
    }
} else {
    echo "No se pudo conectar al Mikrotik.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios PPPOE</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        .table tbody tr td {
            vertical-align: middle;
        }
        .enabled {
            background-color: #f8f9fa; /* Gris claro */
            color: #343a40; /* Texto oscuro */
        }
        .disabled {
            background-color: #f8d7da;
            color: #721c24;
        }
        .card-header {
            font-weight: bold;
            text-transform: uppercase;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,.05);
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="mt-5 text-center">Lista de Usuarios PPPOE</h2>

    <!-- Botón para cerrar la conexión -->
    <div class="d-flex justify-content-end mb-3">
        <a href="cerrar_sesion.php" class="btn btn-danger">Cerrar Conexión</a>
    </div>

    <!-- Tarjetas de resumen -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-header">Usuarios Activos</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $activos ?></h5>
                    <p class="card-text">Total de usuarios habilitados en el sistema.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Usuarios Deshabilitados</div>
                <div class="card-body">
                    <h5 class="card-title"><?= $inactivos ?></h5>
                    <p class="card-text">Total de usuarios deshabilitados en el sistema.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <table class="table table-bordered table-striped mt-4">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Servicio</th>
                <th>Perfil</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr class="<?= $usuario['disabled'] === 'false' ? 'enabled' : 'disabled' ?>">
                    <td><?= htmlspecialchars($usuario['.id']) ?></td>
                    <td><?= htmlspecialchars($usuario['name']) ?></td>
                    <td><?= htmlspecialchars($usuario['service']) ?></td>
                    <td><?= htmlspecialchars($usuario['profile']) ?></td>
                    <td><?= $usuario['disabled'] === 'false' ? 'Habilitado' : 'Deshabilitado' ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-mQ93NI26c10cDujAxZRn5D9kR2RxXyS3E3BzcnXusA2kaLIHkGga9/Ia5rYIIyIV" crossorigin="anonymous"></script>
</body>
</html>
