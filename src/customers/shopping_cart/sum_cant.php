<?php
session_start();
require "../../../mysql/connection.php";

if (!isset($_SESSION['email'])) {
    echo json_encode(["ok" => false, "message" => "Debes iniciar sesión para continuar."]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_carrito = $_POST['id'];
    $sql = "SELECT c.cantidad, c.id_producto, p.stock, p.precio 
            FROM cart c 
            JOIN productos p ON c.id_producto = p.id_producto 
            WHERE c.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_carrito);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cantidad = $row['cantidad'];
        $stock = $row['stock'];
        $precio = $row['precio'];
        
        if ($stock > 0) {
            $new_quantity = $cantidad + 1;
            $new_subtotal = $new_quantity * $precio;
            
            $update_cart = "UPDATE cart SET cantidad = ?, subtotal = ? WHERE id = ?";
            $stmt = $conn->prepare($update_cart);
            $stmt->bind_param("idi", $new_quantity, $new_subtotal, $id_carrito);
            $stmt->execute();
            
            $update_stock = "UPDATE productos SET stock = stock - 1 WHERE id_producto = ?";
            $stmt = $conn->prepare($update_stock);
            $stmt->bind_param("i", $row['id_producto']);
            $stmt->execute();
            
            echo json_encode(["ok" => true, "message" => "Cantidad actualizada correctamente."]);
        } else {
            echo json_encode(["ok" => false, "message" => "No hay suficiente stock disponible."]);
        }
    } else {
        echo json_encode(["ok" => false, "message" => "Producto no encontrado en el carrito."]);
    }
    
    $stmt->close();
    $conn->close();
}
?>
