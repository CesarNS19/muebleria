<?php
session_start();
require '../../../mysql/connection.php';

$email = $_SESSION['email'] ?? null;

if (!$email) {
    $_SESSION['status_message'] = "No se ha iniciado sesión.";
    $_SESSION['status_type'] = "error";
    header("Location: ../shopping_cart.php");
    exit();
}

$sql = "SELECT id_producto, cantidad FROM cart WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['status_message'] = "No hay productos en el carrito.";
    $_SESSION['status_type'] = "warning";
    header("Location: ../shopping_cart.php");
    exit();
}

$conn->begin_transaction();

try {
    while ($row = $result->fetch_assoc()) {
        $id_producto = $row['id_producto'];
        $cantidad = $row['cantidad'];

        $sql = "UPDATE productos SET stock = stock + ? WHERE id_producto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cantidad, $id_producto);
        $stmt->execute();
    }

    $sql = "DELETE FROM cart WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $conn->commit();
    
    $_SESSION['status_message'] = "Carrito vacío con éxito.";
    $_SESSION['status_type'] = "success";
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['status_message'] = "Error al vaciar el carrito: " . $e->getMessage();
    $_SESSION['status_type'] = "error";
}

header("Location: ../shopping_cart.php");
exit();
?>
