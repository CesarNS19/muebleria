<?php
session_start();
include "../../mysql/connection.php";
include "slidebar.php";
$title = "Muebleria ┃ Admin sales";
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<title><?php echo $title; ?></title>

<div id="Alert" class="container"></div>

<div id ="main-content" class="container-fluid">
<!-- Tabla de Ventas -->
<section class="services-table container my-4">
    <h2 class="text-center mb-4">Ventas</h2>
    <div class="table-responsive">
        <table class="table table-hover table-bordered text-center align-middle shadow-sm rounded-3">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Nombre del Cliente</th>
                    <th>Nombre del producto</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT d.cantidad, d.subtotal, p.nombre AS nombre_producto, v.fecha, v.hora, 
                               p.imagen, p.descripcion, 
                               CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS nombre_cliente
                        FROM ventas v
                        JOIN detalle_venta d ON v.id_venta = d.id_venta
                        JOIN productos p ON d.id_producto = p.id_producto
                        JOIN clientes c ON v.id_cliente = c.id_cliente";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['nombre_cliente']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nombre_producto']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                        echo "<td><img src='img/" . htmlspecialchars($row['imagen']) . "' class='rounded' width='100px' height='60px' alt='Imagen Producto'></td>";
                        echo "<td>" . htmlspecialchars($row['cantidad']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['hora']) . "</td>";
                        echo "<td>" . htmlspecialchars(number_format($row['subtotal'], 2)) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No se han realizado ventas</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>
</div>

<script>
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