<?php
session_start();
include "../../../mysql/connection.php";

if (!isset($_SESSION['id_cliente'])) {
    $_SESSION['status_message'] = "Debes iniciar sesión para editar tu perfil.";
    $_SESSION['status_type'] = "error";
    header("Location: ../../../login.php");
    exit;
}

$id_cliente = $_SESSION['id_cliente'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre']);
    $apellido_paterno = trim($_POST['apellido_paterno']);
    $apellido_materno = trim($_POST['apellido_materno']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);

    if (empty($nombre) || empty($apellido_paterno) || empty($telefono) || empty($email)) {
        $_SESSION['status_message'] = "Todos los campos son obligatorios.";
        $_SESSION['status_type'] = "error";
        header("Location: ../profile.php");
        exit;
    }

    $sql = "UPDATE clientes 
            SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, telefono = ?, email = ? 
            WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nombre, $apellido_paterno, $apellido_materno, $telefono, $email, $id_cliente);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = "Perfil actualizado exitosamente.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al actualizar el perfil.";
        $_SESSION['status_type'] = "error";
    }

    $stmt->close();
    $conn->close();

    header("Location: ../profile.php");
    exit;
}
?>
