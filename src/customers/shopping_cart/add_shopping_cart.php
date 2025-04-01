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

$sql = "SELECT id_cliente FROM clientes WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['status_message'] = "No se encontró un cliente asociado al email.";
    $_SESSION['status_type'] = "error";
    header("Location: ../shopping_cart.php");
    exit();
}

$row = $result->fetch_assoc();
$id_cliente = $row['id_cliente'];

$sql = "SELECT id_producto, cantidad, subtotal FROM cart WHERE email = ?";
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
    $total = 0;
    $detalle_venta = [];

    while ($row = $result->fetch_assoc()) {
        $total += $row['subtotal'];
        $detalle_venta[] = $row;
    }

    $sql = "INSERT INTO ventas (id_cliente, total, fecha, hora) VALUES (?, ?, NOW(), CURTIME())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("id", $id_cliente, $total);
    $stmt->execute();
    $id_venta = $stmt->insert_id;

    foreach ($detalle_venta as $producto) {
        $sql = "SELECT id_producto, cantidad FROM detalle_venta WHERE id_venta = ? AND id_producto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_venta, $producto['id_producto']);
        $stmt->execute();
        $existing_product = $stmt->get_result()->fetch_assoc();

        if ($existing_product) {
            $new_quantity = $existing_product['cantidad'] + $producto['cantidad'];
            $sql = "UPDATE detalle_venta SET cantidad = ?, subtotal = cantidad * (SELECT precio FROM productos WHERE id_producto = ?) WHERE id_venta = ? AND id_producto = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiii", $new_quantity, $producto['id_producto'], $id_venta, $producto['id_producto']);
            $stmt->execute();
        } else {
            $sql = "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, subtotal) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiid", $id_venta, $producto['id_producto'], $producto['cantidad'], $producto['subtotal']);
            $stmt->execute();
        }
    }

    $sql = "DELETE FROM cart WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $conn->commit();
    $_SESSION['status_message'] = "Compra realizada con éxito.";
    $_SESSION['status_type'] = "success";
    header("Location: ../ticket/view_ticket.php?id_venta=" . $id_venta);
exit;

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['status_message'] = "Error al procesar la compra: " . $e->getMessage();
    $_SESSION['status_type'] = "error";
}

header("Location: ../shopping_cart.php");
exit();

?>
