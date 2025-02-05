<?php
require '../../../mysql/connection.php';
session_start();

if (isset($_GET['id']) && isset($_GET['estatus'])) {
    $id_empleado = intval($_GET['id']);
    $nuevo_estatus = $_GET['estatus'];

    if ($nuevo_estatus === 'activo' || $nuevo_estatus === 'inactivo') {
        $sql = "UPDATE empleados SET estatus = ? WHERE id_empleado = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $nuevo_estatus, $id_empleado);

        if ($stmt->execute()) {
            $_SESSION['status_message'] = $nuevo_estatus === 'activo' ? 'Empleado activado correctamente.' : 'Empleado desactivado correctamente.';
            $_SESSION['status_type'] = 'success';
        } else {
            $_SESSION['status_message'] = 'Error al actualizar el estado del empleado: ' . $stmt->error;
            $_SESSION['status_type'] = 'danger';
        }

        $stmt->close();
    } else {
        $_SESSION['status_message'] = 'Estatus inválido.';
        $_SESSION['status_type'] = 'warning';
    }
} else {
    $_SESSION['status_message'] = 'ID o estatus no especificado.';
    $_SESSION['status_type'] = 'warning';
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();

$conn->close();
?>
