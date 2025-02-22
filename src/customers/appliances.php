<?php
session_start();
include 'slidebar.php';
require '../../mysql/connection.php';
$title = "Muebleria ┃ Dashboard";

$searchQuery = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchQuery = " WHERE p.nombre LIKE '%" . $conn->real_escape_string($searchTerm) . "%' ";
}

$sql = "SELECT p.id_producto, c.nombre AS categoria, m.nombre AS marca, p.nombre, p.descripcion, p.color, p.precio, p.imagen
        FROM productos p
        JOIN categorias c ON p.id_categoria = c.id_categoria
        JOIN marcas m ON p.id_marca = m.id_marca
        WHERE c.nombre = 'electrodomesticos'" . $searchQuery;
$result = $conn->query($sql);
?>

<title><?php echo $title; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div id="Alert" class="container mt-3"></div>

<div id ="main-content" class="container-fluid">
    <div id="products-container" class="row row-cols-1 row-cols-md-3 g-4">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagePath = !empty($row['imagen']) ? "img/" . $row['imagen'] : "";
                echo '
                <div class="col">
                    <div class="card h-100 shadow-lg rounded-3 text-center">
                    <h5 class="card-title fw-bold mt-2">' . $row["nombre"] . '</h5>
                        <div class="position-relative overflow-hidden d-flex justify-content-center align-items-center" style="height: 100px;">
                            <img src="' . $imagePath . '" alt="Imagen del Producto" style="height: 80%; width: 30%;">
                        </div>
                        <div class="card-body text-center">
                            <p class="card-text text-muted">Descripción: <strong>' . $row["descripcion"] . '</strong></p>
                            <span>Marca: <strong>' . $row["marca"] . '</strong></span>
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item">Color: <strong>' . $row["color"] . '</strong></li>
                            </ul>
                            <p class="text-success fs-5 fw-bold">Precio: $' . $row["precio"] . '</p>
                                <button class="btn btn-primary w-100 rounded-pill add-to-cart"
                                    data-id="'.$row["id_producto"].'"
                                    data-nombre="'.$row["nombre"].'"
                                    data-descripcion="'.$row["descripcion"].'"
                                    data-imagen="'.$row["imagen"].'"
                                    data-precio="'.$row["precio"].'">
                                    Añadir al carrito <i class="fas fa-cart-plus"></i>
                                </button>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo "<p>No se encontraron productos para tu búsqueda.</p>";
        }
        $conn->close();
        ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#search').on('input', function() {
            let searchTerm = $(this).val();
            
            $.ajax({
                url: "products/search_appliances.php",
                type: "GET",
                data: { search: searchTerm },
                success: function(response) {
                    $('#products-container').html(response);
                }
            });
        });
    });

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