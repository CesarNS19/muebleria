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
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($nombre) || empty($apellido_paterno) || empty($telefono) || empty($email)) {
        $_SESSION['status_message'] = "Todos los campos son obligatorios.";
        $_SESSION['status_type'] = "error";
        header("Location: ../profile.php");
        exit;
    }

    $sql = "SELECT contrasena FROM clientes WHERE id_cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $current_password_hash = $row['contrasena'];
    $stmt->close();

    if (!empty($new_password) || !empty($confirm_password)) {
        if ($new_password !== $confirm_password) {
            $_SESSION['status_message'] = "Las contraseñas no coinciden.";
            $_SESSION['status_type'] = "error";
            header("Location: ../profile.php");
            exit;
        }

        if (password_verify($new_password, $current_password_hash)) {
            $_SESSION['status_message'] = "No puedes usar la misma contraseña actual.";
            $_SESSION['status_type'] = "error";
            header("Location: ../profile.php");
            exit;
        }

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $sql = "UPDATE clientes 
                SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, telefono = ?, email = ?, contrasena = ?
                WHERE id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $nombre, $apellido_paterno, $apellido_materno, $telefono, $email, $hashed_password, $id_cliente);
    } else {
        $sql = "UPDATE clientes 
                SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, telefono = ?, email = ?
                WHERE id_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nombre, $apellido_paterno, $apellido_materno, $telefono, $email, $id_cliente);
    }

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
