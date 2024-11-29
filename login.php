<?php
// Verifica si la sesión ya está iniciada antes de llamarlo
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir configuración de la base de datos
require 'assets/db/config.php';

if (isset($_POST['login'])) {
    $errMsg = '';

    // Obtiene datos del formulario
    $usuario = trim($_POST['usuario']);
    $clave = md5($_POST['clave']);

    if ($usuario == '') {
        $errMsg = 'Por favor, digite su usuario.';
    }
    if ($clave == '') {
        $errMsg = 'Por favor, digite su contraseña.';
    }

    if ($errMsg == '') {
        try {
            // Consulta a la base de datos
            $stmt = $connect->prepare(
                'SELECT id, nombre, usuario, email, clave, cargo 
                 FROM usuarios 
                 WHERE usuario = :usuario
                 UNION 
                 SELECT codpaci, nombrep, apellidop, usuario, clave, cargo 
                 FROM customers 
                 WHERE usuario = :usuario'
            );

            $stmt->execute([':usuario' => $usuario]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                $errMsg = "Usuario $usuario no encontrado.";
            } else {
                if ($clave === $data['clave']) {
                    // Configura las variables de sesión
                    $_SESSION['id'] = $data['id'];
                    $_SESSION['nombre'] = $data['nombre'];
                    $_SESSION['usuario'] = $data['usuario'];
                    $_SESSION['email'] = $data['email'];
                    $_SESSION['clave'] = $data['clave'];
                    $_SESSION['cargo'] = $data['cargo'];

                    // Redirige según el rol del usuario
                    if ($_SESSION['cargo'] == 1) {
                        header('Location: view/admin/admin.php');
                    } elseif ($_SESSION['cargo'] == 2) {
                        header('Location: view/user/user.php');
                    }
                    exit;
                } else {
                    $errMsg = 'Contraseña incorrecta.';
                }
            }
        } catch (PDOException $e) {
            $errMsg = 'Error en la consulta: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie-edge">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="assets/css/css/all.min.css">
    <link rel="stylesheet" href="assets/css/sweetalert.css">
    <link rel="icon" href="assets/img/logo.png" type="image/x-icon"/>
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
</head>
<body>
    <div class="contenedor">
        <div class="img">
            <img src="assets/img/bd.jpg" alt="">
        </div>
        <div class="contenido-login">
            <form autocomplete="off" method="POST" role="form">
                <img src="assets/img/logo_2.png" alt="">
                <h2>Inicio Sesion</h2>
                <?php
                if (isset($errMsg)) {
                    echo '<div style="color:#FF0000;text-align:center;font-size:20px;">' . $errMsg . '</div>';
                }
                ?>
                <div class="input-div nit">
                    <div class="i">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="div">
                        <input type="text" name="usuario" value="<?php if (isset($_POST['usuario'])) echo $_POST['usuario'] ?>" autocomplete="off" placeholder="USUARIO">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="div">
                        <input type="password" required="true" name="clave" value="<?php if (isset($_POST['clave'])) echo MD5($_POST['clave']) ?>" placeholder="CONTRASEÑA">
                    </div>
                </div>
                <div class="row" id="load" hidden="hidden">
                    <div class="col-xs-4 col-xs-offset-4 col-md-2 col-md-offset-5">
                        <img src="assets/img/load.gif" width="100%" alt=""/>
                    </div>
                    <div class="col-xs-12 center text-accent">
                        <span>Validando información...</span>
                    </div>
                </div>
                <button class="btn" name="login" type="submit">Iniciar sesión</button>

                <!-- Enlace al formulario de registro -->
                <div class="link-registro" style="text-align: center; margin-top: 20px;">
                    <a href="registro.php">¿No tienes una cuenta? Regístrate aquí</a>
                </div>
            </form>
            <div id="msg_error" class="alert alert-danger" role="alert" style="display: none"></div>
        </div>
    </div>
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/sweetalert.min.js"></script>
</body>
</html>
