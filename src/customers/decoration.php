<?php
session_start();
require '../../mysql/connection.php';
include 'slidebar.php';
$title = "Muebleria ┃ Dashboard";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_producto"])) {
    if (isset($_SESSION["id_cliente"])) {
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

        $_SESSION['status_message'] = "Producto agregado al carrito.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Debe iniciar sesión para agregar productos al carrito.";
        $_SESSION['status_type'] = "warning";
    }
}

$sql = "SELECT p.id_producto, c.nombre AS categoria, m.nombre AS marca, p.nombre, p.descripcion, p.color, p.precio, p.imagen
        FROM productos p
        JOIN categorias c ON p.id_categoria = c.id_categoria
        JOIN marcas m ON p.id_marca = m.id_marca
        WHERE c.nombre = 'decoracion'";
$result = $conn->query($sql);
?>

<title><?php echo $title; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div id="Alert" class="container mt-3"></div>

<div class="container mt-2">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagePath = !empty($row['imagen']) ? "img/" . $row['imagen'] : "";
                echo '
                <div class="col">
                    <div class="card h-100 shadow-lg rounded-3 text-center">
                    <h5 class="card-title fw-bold mt-2">' . $row["nombre"] . '</h5>
                        <div class="position-relative overflow-hidden d-flex justify-content-center align-items-center" style="height: 200px;">
                            <img src="' . $imagePath . '" alt="Imagen del Producto" style="height: 80%; width: 60%;">
                        </div>
                        <div class="card-body text-center">
                            <p class="card-text text-muted">Descripción: <strong>' . $row["descripcion"] . '</strong></p>
                            <span>Marca: <strong>' . $row["marca"] . '</strong></span>
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item">Color: <strong>' . $row["color"] . '</strong></li>
                            </ul>
                            <p class="text-success fs-5 fw-bold">Precio: $' . $row["precio"] . '</p>
                            <form method="POST" action="">
                                <input type="hidden" name="id_producto" value="' . $row["id_producto"] . '">
                                <input type="hidden" name="nombre" value="' . $row["nombre"] . '">
                                <input type="hidden" name="descripcion" value="' . $row["descripcion"] . '">
                                <input type="hidden" name="imagen" value="' . $row["imagen"] . '">
                                <input type="hidden" name="precio" value="' . $row["precio"] . '">
                                <button class="btn btn-primary w-100 rounded-pill">Añadir al carrito <i class="fas fa-cart-plus"></i></button>
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
                <?php elseif ($_SESSION["status_type"] === "danger"): ?>
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