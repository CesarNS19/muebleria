<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["id_cliente"])) {
        echo json_encode(["status" => "warning", "message" => "Debe iniciar sesión para agregar productos al carrito."]);
        exit;
    }

    $id_producto = $_POST["id_producto"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $imagen = $_POST["imagen"];
    $cantidad = 1;

    if (!isset($_SESSION["carrito"])) {
        $_SESSION["carrito"] = [];
    }

    $existe = false;
    foreach ($_SESSION["carrito"] as &$item) {
        if ($item["id_producto"] == $id_producto) {
            $item["cantidad"] += 1;
            $existe = true;
            break;
        }
    }

    if (!$existe) {
        $_SESSION["carrito"][] = [
            "id_producto" => $id_producto,
            "nombre" => $nombre,
            "descripcion" => $descripcion,
            "precio" => $precio,
            "imagen" => $imagen,
            "cantidad" => $cantidad
        ];
    }

    $totalProductos = array_sum(array_column($_SESSION["carrito"], "cantidad"));

    echo json_encode([
        "status" => "success",
        "message" => "Producto agregado al carrito.",
        "total" => $totalProductos
    ]);
}
?>
