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

?>

<title><?php echo $title; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div id="Alert" class="container mt-3"></div>

<div class="container mt-3">
    <h1 class="mb-4 text-center">Mi Carrito de Compras</h1>
    <div class="text-end mt-4">
        <form method="POST">
            <button type="submit" name="vaciar_carrito" class="btn btn-sm btn-warning rounded-pill shadow-sm">
                <i class="fa-solid fa-trash"></i> Vaciar Carrito
            </button>
        </form>
    </div>
</div>

<section class="services-table container my-2">
<div class="table-responsive">
        <table class="table table-hover table-bordered text-center align-middle shadow-sm rounded-3">
            <thead class="bg-primary text-white">
            <tr>
                <th>Producto</th>
                <th>Descripción</th>
                <th>Imágen</th>
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
                            <img src='{$imagePath}' alt='Imagen del Producto' class='rounded' style='width: 80px; height: 50px; object-fit: cover;' />
                        </td>
                        <td>\${$producto["precio"]}</td>
                        <td>
                            <form method='POST' class='d-inline'>
                                <input type='hidden' name='id_producto' value='{$id_producto}' />
                                <input type='number' name='cantidad' value='{$producto["cantidad"]}' min='0' class='form-control d-inline' style='width: 80px;' />
                                <button type='submit' name='update_quantity' class='btn btn-sm btn-outline-primary rounded-pill shadow-sm'>
                                <i class='fas fa-edit'></i> Actualizar
                                </button>
                            </form>
                        </td>
                        <td>\${$subtotal}</td>
                        <td>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='id_producto' value='{$id_producto}' />
                                <input type='hidden' name='cantidad' value='0' />
                                <button type='submit' name='update_quantity' class='btn btn-sm btn-outline-danger rounded-pill shadow-sm'>
                                <i class='fas fa-trash-alt'></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center'>No hay productos en el carrito.</td></tr>";
            }
            ?>
        </tbody>
        </table>
    </div>
</section>

    <div class="text-end container">
        <p>Total: $<?php echo $total; ?></p>
    </div>

    <div class="text-end mt-4 container">
        <form method="POST" action="shopping_cart/add_shopping_cart.php">
            <button type="submit" name="buy_now" class="btn btn-sm btn-success rounded-pill shadow-sm ">
            <i class="fa-solid fa-cart-arrow-down"></i> Comprar
            </button>
        </form>
    </div>

<script>
    window.onload = () => {
        const productCount = <?php echo array_sum(array_column($_SESSION["carrito"] ?? [], "cantidad")); ?>;
        const badge = document.querySelector(".nav-item .badge");
        if (badge) {
            badge.textContent = productCount > 0 ? productCount : "";
        }
    }

    function mostrarToast(titulo, mensaje, tipo) {
        let icon = '';
        let alertClass = '';

        switch (tipo) {
            case 'success':
                icon = '<span class="fas fa-check-circle text-white fs-6"></span>';
                alertClass = 'alert-success';
                break;
            case 'error':
                icon = '<span class="fas fa-times-circle text-white fs-6"></span>';
                alertClass = 'alert-danger';
                break;
            case 'warning':
                icon = '<span class="fas fa-exclamation-circle text-white fs-6"></span>';
                alertClass = 'alert-warning';
                break;
            case 'info':
                icon = '<span class="fas fa-info-circle text-white fs-6"></span>';
                alertClass = 'alert-info';
                break;
            default:
                icon = '<span class="fas fa-info-circle text-white fs-6"></span>';
                alertClass = 'alert-info';
                break;
        }

        const alert = `
        <div class="alert ${alertClass} d-flex align-items-center alert-dismissible fade show" role="alert">
            <div class="me-2">${icon}</div>
            <div>${titulo}: ${mensaje}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;

        $("#Alert").html(alert);

        setTimeout(() => {
            $(".alert").alert('close');
        }, 4000);
    }

    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($_SESSION['status_message']) && isset($_SESSION['status_type'])): ?>
            <?php if ($_SESSION["status_type"] === "warning"): ?>
                mostrarToast("Advertencia", '<?= $_SESSION["status_message"] ?>', '<?= $_SESSION["status_type"] ?>');
            <?php elseif ($_SESSION["status_type"] === "error"): ?>
                mostrarToast("Error", '<?= $_SESSION["status_message"] ?>', '<?= $_SESSION["status_type"] ?>');
            <?php elseif ($_SESSION["status_type"] === "info"): ?>
                mostrarToast("Info", '<?= $_SESSION["status_message"] ?>', '<?= $_SESSION["status_type"] ?>');
            <?php else: ?>
                mostrarToast("Éxito", '<?= $_SESSION["status_message"] ?>', '<?= $_SESSION["status_type"] ?>');
            <?php endif; ?>
            <?php unset($_SESSION['status_message'], $_SESSION['status_type']); ?>
        <?php endif; ?>
    });
</script>
