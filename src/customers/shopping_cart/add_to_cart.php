<?php
session_start();
require '../../../mysql/connection.php';

if (!isset($_SESSION['id_cliente'])) {
    echo json_encode(["status" => "error", "message" => "Debes iniciar sesión para agregar productos al carrito"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_producto = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $imagen = $_POST['imagen'];
    $precio = $_POST['precio'];
    $email_usuario = $_SESSION['email'];
    
    $sql_stock = "SELECT stock FROM productos WHERE id_producto = ?";
    $stmt_stock = $conn->prepare($sql_stock);
    $stmt_stock->bind_param("i", $id_producto);
    $stmt_stock->execute();
    $result_stock = $stmt_stock->get_result();
    $row_stock = $result_stock->fetch_assoc();
    $stock_disponible = $row_stock['stock'] ?? 0;
    $stmt_stock->close();

    if ($stock_disponible <= 0) {
        echo json_encode(["status" => "warning", "message" => "No se puede agregar, producto agotado"]);
        exit;
    }

    $cantidad = 1;
    $subtotal = $precio * $cantidad;

    $sql = "INSERT INTO cart (email, id_producto, nombre, descripcion, imagen, precio, cantidad, subtotal)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE cantidad = cantidad + 1, subtotal = precio * cantidad";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siissdii", $email_usuario, $id_producto, $nombre, $descripcion, $imagen, $precio, $cantidad, $subtotal);

    if ($stmt->execute()) {
        $sql_update_stock = "UPDATE productos SET stock = stock - 1 WHERE id_producto = ?";
        $stmt_update_stock = $conn->prepare($sql_update_stock);
        $stmt_update_stock->bind_param("i", $id_producto);
        $stmt_update_stock->execute();
        $stmt_update_stock->close();
        
        $sql_cart_count = "SELECT SUM(cantidad) AS total FROM cart WHERE email = ?";
        $stmt_cart_count = $conn->prepare($sql_cart_count);
        $stmt_cart_count->bind_param("s", $email_usuario);
        $stmt_cart_count->execute();
        $result_cart_count = $stmt_cart_count->get_result();
        $row_cart_count = $result_cart_count->fetch_assoc();
        $total_cart = $row_cart_count['total'] ?? 0;
        $stmt_cart_count->close();

        echo json_encode(["status" => "success", "message" => "Producto agregado al carrito", "total_cart" => $total_cart]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al agregar el producto"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>