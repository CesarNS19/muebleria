<?php
session_start();
require '../../mysql/connection.php';
require 'slidebar.php';

$title = "Muebleria ┃ Carrito";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["update_quantity"])) {
        $id_producto = $_POST["id_producto"];
        $cantidad = intval($_POST["cantidad"]);
        
        if (isset($_SESSION["carrito"][$id_producto])) {
            if ($cantidad > 0) {
                $_SESSION["carrito"][$id_producto]["cantidad"] = $cantidad;
            } else {
                unset($_SESSION["carrito"][$id_producto]);
            }
        }
    }
}
?>
<title><?php echo $title; ?></title>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Mi Carrito de Compras</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Descripción</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            if (!empty($_SESSION["carrito"])) {
                foreach ($_SESSION["carrito"] as $id_producto => $producto) {
                    $subtotal = $producto["precio"] * $producto["cantidad"];
                    $total += $subtotal;
                    $imagePath = !empty($producto["imagen"]) ? "img/" . $producto["imagen"] : "img/default.jpg";

                    echo "<tr>
                        <td>{$producto["nombre"]}</td>
                        <td>{$producto["descripcion"]}</td>
                        <td>
                            <img src='{$imagePath}' alt='Imagen del Producto' style='width: 80px; height: 80px; object-fit: cover;'>
                        </td>
                        <td>\${$producto["precio"]}</td>
                        <td>
                            <form method='POST' class='d-inline'>
                                <input type='hidden' name='id_producto' value='{$id_producto}'>
                                <input type='number' name='cantidad' value='{$producto["cantidad"]}' min='0' class='form-control d-inline' style='width: 80px;'>
                                <button type='submit' name='update_quantity' class='btn btn-primary btn-sm'>Actualizar</button>
                            </form>
                        </td>
                        <td>\${$subtotal}</td>
                        <td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id_producto' value='{$id_producto}'>
                                <input type='hidden' name='cantidad' value='0'>
                                <button type='submit' name='update_quantity' class='btn btn-danger btn-sm'>Eliminar</button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>El carrito está vacío.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="text-end">
        <p style="margin-left: 90%;">Total: $<?php echo $total; ?></p>
    </div>

    <div class="text-end mt-4">
        <form method="POST">
            <button type="submit" name="buy_now" class="btn btn-success" style="margin-left: 92%;">Comprar</button>
        </form>
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
