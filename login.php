<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mikrotik</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>

    <div class="container">
        <h2 class="mt-5">Mikrotik Login</h2>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ip = $_POST['ip'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            include_once('conexion.php');
            $resultado = conectarMikrotik($ip, $username, $password);

            if ($resultado['success']) {
                echo '<div class="alert alert-success" role="alert">
                    Conexión exitosa! Nombre del Mikrotik: ' . htmlspecialchars($resultado['name']) . '
                  </div>';
                session_start();
                $_SESSION['ip'] = $ip;
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;

                // Redirigir a usuarios.php
                header('Location: usuarios.php');
            } else {
                echo '<div class="alert alert-danger" role="alert">
                    Conexión fallida. Por favor, verifica los datos e intenta nuevamente.
                  </div>';
            }
        }
        ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="ip" class="form-label">IP Address</label>
                <input type="text" class="form-control" id="ip" name="ip" placeholder="Ingresa el ip" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="usuario" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-mQ93NI26c10cDujAxZRn5D9kR2RxXyS3E3BzcnXusA2kaLIHkGga9/Ia5rYIIyIV"
        crossorigin="anonymous"></script>
</body>

</html>