<?php
require '../../../mysql/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
        $_SESSION['status_message'] = "El carrito está vacío. No se puede realizar la compra.";
        $_SESSION['status_type'] = "warning";
        header("Location: ../shopping_cart.php");
        exit();
    }

    $id_cliente = $_SESSION['id_cliente'] ?? null;

    if (!$id_cliente) {
        $_SESSION['status_message'] = "Debes iniciar sesión para realizar una compra.";
        $_SESSION['status_type'] = "danger";
        header("Location: ../shopping_cart.php");
        exit();
    }

    $total_venta = 0;
    $productos_vendidos = [];

    foreach ($_SESSION['carrito'] as $producto) {
        $subtotal = $producto['precio'] * $producto['cantidad'];
        $total_venta += $subtotal;
        $productos_vendidos[] = [
            'id_producto' => $producto['id_producto'],
            'cantidad' => $producto['cantidad'],
            'subtotal' => $subtotal
        ];
    }

    $conn->begin_transaction();

    try {
        $sql_venta = "INSERT INTO ventas (id_cliente, fecha, hora, total) 
                      VALUES (?, CURRENT_DATE(), CURRENT_TIME(), ?)";
        $stmt_venta = $conn->prepare($sql_venta);
        $stmt_venta->bind_param("id", $id_cliente, $total_venta);

        if (!$stmt_venta->execute()) {
            throw new Exception("Error al registrar la venta principal: " . $stmt_venta->error);
        }

        $id_venta = $stmt_venta->insert_id;
        $stmt_venta->close();

        $sql_detalle = "INSERT INTO detalle_venta (id_producto, id_venta, cantidad, subtotal) 
                        VALUES (?, ?, ?, ?)";
        $stmt_detalle = $conn->prepare($sql_detalle);

        foreach ($productos_vendidos as $producto) {
            $stmt_detalle->bind_param("iiid", $producto['id_producto'], $id_venta, $producto['cantidad'], $producto['subtotal']);
            if (!$stmt_detalle->execute()) {
                throw new Exception("Error al registrar el detalle de venta: " . $stmt_detalle->error);
            }
        }

        $stmt_detalle->close();

        $conn->commit();

        unset($_SESSION['carrito']);

        $_SESSION['status_message'] = "Compra realizada exitosamente.";
        $_SESSION['status_type'] = "success";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['status_message'] = "Error al procesar la compra: " . $e->getMessage();
        $_SESSION['status_type'] = "danger";
    }

    $conn->close();
    header("Location: ../shopping_cart.php");
    exit();
}
?>
