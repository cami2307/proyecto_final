<?php
require 'assets/db/config.php'; // Conexión PDO
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $clave = $_POST['clave'] ?? '';
    $clave2 = $_POST['clave2'] ?? '';

    // Validar campos
    if (empty($name) || empty($email) || empty($clave) || empty($clave2)) {
        echo "error_1"; // Campos vacíos
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "error_2"; // Correo inválido
        exit;
    }

    if ($clave !== $clave2) {
        echo "error_3"; // Contraseñas no coinciden
        exit;
    }

    try {
        // Verificar si el correo ya está registrado
        $stmt = $connect->prepare("SELECT 1 FROM usuarios WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo "error_4"; // Correo ya registrado
            exit;
        }

        // Insertar usuario en la base de datos
        $hashed_password = password_hash($clave, PASSWORD_DEFAULT);

        $stmt = $connect->prepare("INSERT INTO usuarios (nombre, email, clave) VALUES (:name, :email, :clave)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':clave', $hashed_password);

        if ($stmt->execute()) {
            echo "success"; // Registro exitoso
        } else {
            echo "error_5"; // Error al insertar
        }
    } catch (PDOException $e) {
        echo "error_6: " . $e->getMessage(); // Error del servidor
    }
} else {
    echo "error_7"; // Método no permitido
}
?>