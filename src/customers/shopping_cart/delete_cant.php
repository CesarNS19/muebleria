<?php
session_start();
require "../../../mysql/connection.php";

if (!isset($_SESSION['email'])) {
    echo json_encode(["ok" => false, "message" => "Debes iniciar sesión para continuar."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_carrito = $_POST['id'];

    $sql = "SELECT c.cantidad, c.id_producto 
            FROM cart c 
            WHERE c.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_carrito);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cantidad = $row['cantidad'];

        $update_stock = "UPDATE productos SET stock = stock + ? WHERE id_producto = ?";
        $stmt = $conn->prepare($update_stock);
        $stmt->bind_param("ii", $cantidad, $row['id_producto']);
        $stmt->execute();

        $delete_cart = "DELETE FROM cart WHERE id = ?";
        $stmt = $conn->prepare($delete_cart);
        $stmt->bind_param("i", $id_carrito);
        $stmt->execute();

        echo json_encode(["ok" => true, "message" => "Producto eliminado del carrito."]);
    } else {
        echo json_encode(["ok" => false, "message" => "Producto no encontrado en el carrito."]);
    }

    $stmt->close();
    $conn->close();
}
