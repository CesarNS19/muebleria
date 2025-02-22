<?php
session_start();
include '../mysql/connection.php';

$nombre_cliente = $_POST['nombre'];
$apellido_paterno = $_POST['apellido_paterno'];
$apellido_materno = $_POST['apellido_materno'] ?? '';
$telefono_personal = $_POST['telefono'];
$correo_electronico = $_POST['email'];
$contrasena = $_POST['contrasena'];
$confirmar_contrasena = $_POST['confirmar_contrasena'];
$estatus = 'activo';
$rol = 'usuario';

if (empty($nombre_cliente) || empty($apellido_paterno) || empty($telefono_personal) || empty($correo_electronico) || empty($contrasena) || empty($confirmar_contrasena)) {
    $_SESSION['status_message'] = "Todos los campos son obligatorios.";
    $_SESSION['status_type'] = "error";
    header("Location: login.php");
    exit();
}

if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['status_message'] = "El correo electrónico no tiene un formato válido.";
    $_SESSION['status_type'] = "error";
    header("Location: login.php");
    exit();
}

if (!preg_match('/^[0-9]+$/', $telefono_personal)) {
    $_SESSION['status_message'] = "El teléfono debe contener solo números.";
    $_SESSION['status_type'] = "error";
    header("Location: login.php");
    exit();
}

if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $apellido_paterno) || !preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/", $apellido_materno)) {
    $_SESSION['status_message'] = "Los apellidos deben contener solo letras y espacios.";
    $_SESSION['status_type'] = "error";
    header("Location: login.php");
    exit();
}

if ($contrasena !== $confirmar_contrasena) {
    $_SESSION['status_message'] = "Las contraseñas no coinciden.";
    $_SESSION['status_type'] = "error";
    header("Location: login.php");
    exit();
}

if (strlen($contrasena) < 8) {
    $_SESSION['status_message'] = "La contraseña debe tener al menos 8 caracteres.";
    $_SESSION['status_type'] = "error";
    header("Location: login.php");
    exit();
}

$sql_check_email = "SELECT id_cliente FROM clientes WHERE email = ?";
$stmt_check_email = $conn->prepare($sql_check_email);
$stmt_check_email->bind_param("s", $correo_electronico);
$stmt_check_email->execute();
$stmt_check_email->store_result();

if ($stmt_check_email->num_rows > 0) {
    $_SESSION['status_message'] = "El correo electrónico ya está registrado. Por favor, elija otro.";
    $_SESSION['status_type'] = "warning";
    header("Location: login.php");
    exit();
}

$hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

$sql = "INSERT INTO clientes (nombre, apellido_paterno, apellido_materno, telefono, email, contrasena, rol, estatus) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $nombre_cliente, $apellido_paterno, $apellido_materno, $telefono_personal, $correo_electronico, $hashed_password, $rol, $estatus);

if ($stmt->execute()) {
    $_SESSION['status_message'] = "Registro exitoso";
    $_SESSION['status_type'] = "success";
    header("Location: login.php");
    exit();
} else {
    $_SESSION['status_message'] = "Error al registrar al usuario: " . $stmt->error;
    $_SESSION['status_type'] = "error";
    header("Location: login.php");
    exit();
}

$stmt->close();
$conn->close();
?>
