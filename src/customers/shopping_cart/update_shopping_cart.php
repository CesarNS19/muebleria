<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["update_quantity"])) {
        $id_producto = $_POST["id_producto"];
        $cantidad = intval($_POST["cantidad"]);
        
        if (isset($_SESSION["carrito"][$id_producto])) {
            if ($cantidad > 0) {
                $_SESSION["carrito"][$id_producto]["cantidad"] = $cantidad;
                $_SESSION['status_message'] = 'Cantidad actualizada correctamente.';
                $_SESSION['status_type'] = 'success';
            } else {
                unset($_SESSION["carrito"][$id_producto]);
                $_SESSION['status_message'] = 'Producto eliminado del carrito.';
                $_SESSION['status_type'] = 'success';
            }
        }
    }

    if (isset($_POST["vaciar_carrito"])) {
        if (empty($_SESSION["carrito"])) {
            $_SESSION['status_message'] = "El carrito ya está vacío y no se puede vaciar.";
            $_SESSION['status_type'] = "warning";
        } else {
            unset($_SESSION["carrito"]);
            $_SESSION['status_message'] = "El carrito ha sido vaciado con éxito.";
            $_SESSION['status_type'] = "success";
        }
    }
}
    header("Location: ../shopping_cart.php");
?>