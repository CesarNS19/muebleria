<?php
require '../../../mysql/connection.php';
session_start();

if (isset($_POST['id_empleado'])) {
    $id = $_POST['id_empleado'];

    $sql = "SELECT * FROM empleados WHERE id_empleado = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $empleado = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nombre = $_POST['nombre'];
        $apellido_paterno = $_POST['apellido_paterno'];
        $apellido_materno = $_POST['apellido_materno'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];

        $checkEmailSql = "SELECT id_empleado FROM empleados WHERE email = ? AND id_empleado != ?";
        $checkEmailStmt = $conn->prepare($checkEmailSql);
        $checkEmailStmt->bind_param("si", $email, $id);
        $checkEmailStmt->execute();
        $checkEmailResult = $checkEmailStmt->get_result();

        if ($checkEmailResult->num_rows > 0) {
            $_SESSION['status_message'] = 'El correo electrónico ya está registrado en otro empleado.';
            $_SESSION['status_type'] = 'danger';
            $checkEmailStmt->close();
            header("Location: ../employees.php");
            exit();
        }

        $sql = "UPDATE empleados SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, telefono = ?, email = ? WHERE id_empleado = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nombre, $apellido_paterno, $apellido_materno, $telefono, $email, $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['status_message'] = 'Empleado actualizado exitosamente.';
                $_SESSION['status_type'] = 'success';
            } else {
                $_SESSION['status_message'] = 'No se realizaron cambios. Verifica los datos.';
                $_SESSION['status_type'] = 'warning';
            }
        } else {
            $_SESSION['status_message'] = 'Error al actualizar los datos: ' . $stmt->error;
            $_SESSION['status_type'] = 'danger';
        }

        $stmt->close();
        header("Location: ../employees.php");
        exit();
    }
}

$conn->close();
?>
