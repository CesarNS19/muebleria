<?php
session_start();
require "../../../mysql/connection.php";

if (!isset($_SESSION['email'])) {
    echo json_encode(["ok" => false, "message" => "Debes iniciar sesión para continuar."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_carrito = $_POST['id'];
    $email_usuario = $_SESSION['email'];

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

        $sql_cart_count = "SELECT SUM(cantidad) AS total FROM cart WHERE email = ?";
        $stmt_cart_count = $conn->prepare($sql_cart_count);
        $stmt_cart_count->bind_param("s", $email_usuario);
        $stmt_cart_count->execute();
        $result_cart_count = $stmt_cart_count->get_result();
        $row_cart_count = $result_cart_count->fetch_assoc();
        $total_cart = $row_cart_count['total'] ?? 0;
        $stmt_cart_count->close();

        echo json_encode([
            "ok" => true, 
            "message" => "Producto eliminado del carrito.", 
            "total_cart" => $total_cart
        ]);
    } else {
        echo json_encode(["ok" => false, "message" => "Producto no encontrado en el carrito."]);
    }

    $stmt->close();
    $conn->close();
}
?>
