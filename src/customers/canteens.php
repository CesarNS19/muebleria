<?php
session_start();
require '../../mysql/connection.php';
require 'slidebar.php';
$title = "Muebleria ┃ Comedores";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_producto"])) {
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
}

$sql = "SELECT p.id_producto, c.nombre AS categoria, m.nombre AS marca, p.nombre, p.descripcion, p.color, p.precio, p.imagen
        FROM productos p
        JOIN categorias c ON p.id_categoria = c.id_categoria
        JOIN marcas m ON p.id_marca = m.id_marca
        WHERE c.nombre = 'comedores'";
$result = $conn->query($sql);
?>
<title><?php echo $title; ?></title>

<div class="container mt-5">
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagePath = !empty($row['imagen']) ? "img/" . $row['imagen'] : "";
                echo '
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="d-flex justify-content-center align-items-center" style="height: 150px;">
                                <img src="' . $imagePath . '" alt="Imagen del Producto" style="max-height: 100%; max-width: 100%;">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">' . $row["nombre"] . '</h5>
                                <p class="card-text">Marca: ' . $row["marca"] . '</p>
                                <p class="card-text">Descripción: ' . $row["descripcion"] . '</p>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Color: ' . $row["color"] . '</li>
                                </ul>
                                <p class="mt-2 text-success text-center"><strong>Precio: $' . $row["precio"] . '</strong></p>
                                <form method="POST" action="">
                                    <input type="hidden" name="id_producto" value="' . $row["id_producto"] . '">
                                    <input type="hidden" name="nombre" value="' . $row["nombre"] . '">
                                    <input type="hidden" name="descripcion" value="' . $row["descripcion"] . '">
                                    <input type="hidden" name="imagen" value="' . $row["imagen"] . '">
                                    <input type="hidden" name="precio" value="' . $row["precio"] . '">
                                    <button class="btn btn-primary w-100">Añadir al carrito</button>
                                </form>
                            </div>
                        </div>
                    </div>';
            }
        } else {
            echo "<p>No hay productos disponibles.</p>";
        }
        $conn->close();
        ?>
    </div>
</div>

<script>
    window.onload = () => {
        const productCount = <?php echo array_sum(array_column($_SESSION["carrito"] ?? [], "cantidad")); ?>;
        const badge = document.querySelector(".nav-item .badge");
        if (badge) {
            badge.textContent = productCount > 0 ? productCount : "";
        }
    }
</script>