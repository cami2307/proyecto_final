<?php
session_start(); // Asegúrate de iniciar la sesión antes de cualquier redirección

require 'assets/db/config.php'; // Asegúrate de que la conexión esté incluida

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener datos del formulario
    $name = $_POST['name'];
    $email = $_POST['email'];
    $clave = md5($_POST['clave']); // Usar MD5 para el hash, pero considera usar funciones más seguras como bcrypt
    $clave2 = md5($_POST['clave2']); // Confirmar que las contraseñas coinciden

    // Validación de contraseñas
    if ($clave !== $clave2) {
        echo 'Las contraseñas no coinciden.';
        exit; // Detener el script si las contraseñas no coinciden
    }

    try {
        // Insertar en la base de datos
        $stmt = $connect->prepare("INSERT INTO usuarios (nombre, email, clave) VALUES (:name, :email, :clave)");

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':clave' => $clave
        ]);

        // Si la inserción fue exitosa, redirigir al login
        header('Location: login.php');
        exit; // Asegúrate de llamar a exit después de la redirección

    } catch (PDOException $e) {
        echo 'Error al registrar: ' . $e->getMessage();
        exit; // Detener el script si ocurre un error
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Registrate</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/css/all.min.css">
    <link rel="stylesheet" href="assets/css/sweetalert.css">
</head>
<body>
    <div class="contenedor">
        <div class="img">
            <img src="assets/img/bd.svg" alt="">
        </div>
        <div class="contenido-login">
            <form method="POST" role="form">
                <img src="assets/img/logo.png" alt="">
                <h2>Registrate</h2>

                <!-- Nombre -->
                <div class="input-div nit">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <input type="text" name="name" required placeholder="NOMBRE">
                    </div>
                </div>

                <!-- Correo -->
                <div class="input-div nit">
                    <div class="i">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="div">
                        <input type="email" name="email" required placeholder="CORREO">
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <input type="password" name="clave" required placeholder="CONTRASEÑA">
                    </div>
                </div>

                <!-- Confirmar Contraseña -->
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <input type="password" name="clave2" required placeholder="CONFIRMAR CONTRASEÑA">
                    </div>
                </div>

                <div class="row" id="load" hidden="hidden">
                    <div class="col-xs-4 col-xs-offset-4 col-md-2 col-md-offset-5">
                        <img src="assets/img/load.gif" width="100%" alt="">
                    </div>
                    <div class="col-xs-12 center text-accent">
                        <span>Validando información...</span>
                    </div>
                </div>

                <button type="submit" class="btn">Registrate</button>
            </form>
        </div>
    </div>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/sweetalert.min.js"></script>
    <script src="assets/js/operaciones.js"></script>
</body>
</html>