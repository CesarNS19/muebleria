<?php
session_start();
include 'slidebar.php';
require '../../mysql/connection.php';
$title = "Muebleria ┃ Mis Compras";
?>

<title><?php echo $title; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<section class="container my-3">
    <h2 class="fw-bold text-primary text-center mt-2">Mis compras</h2>

    <?php
    $sql = "SELECT v.fecha, v.hora, v.id_venta, 
                   SUM(d.cantidad) AS total_articulos, 
                   SUM(d.subtotal) AS total_compra
            FROM ventas v
            JOIN detalle_venta d ON v.id_venta = d.id_venta
            WHERE v.id_cliente = '" . $_SESSION['id_cliente'] . "'
            GROUP BY v.id_venta
            ORDER BY v.fecha DESC, v.hora DESC;";
    $result = $conn->query($sql);
    $meses = [
        'January' => 'enero', 'February' => 'febrero', 'March' => 'marzo', 'April' => 'abril',
        'May' => 'mayo', 'June' => 'junio', 'July' => 'julio', 'August' => 'agosto',
        'September' => 'septiembre', 'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'
    ];

    if ($result->num_rows > 0) {
        while ($venta = $result->fetch_assoc()) {
            $fecha = new DateTime($venta['fecha']);
            $hora = new DateTime($venta['hora']);
            $fecha_formateada = $fecha->format('d') . " de " . $meses[$fecha->format('F')] . " de " . $fecha->format('Y');
            $hora_formateada = $hora->format('h:i A');

            $total_color = ($venta['total_compra'] >= 100) ? 'text-primary' : 'text-danger';
            
            echo "<div class='card mb-3 shadow-sm mt-3'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>Compra realizada el " . htmlspecialchars($fecha_formateada) . " a las " . htmlspecialchars($hora_formateada) . "</h5>";
            echo "<p class='card-text'>Cantidad de artículos: " . htmlspecialchars($venta['total_articulos']) . "</p>";
            echo "<p class='card-text " . $total_color . "'>Total de la compra: $" . number_format($venta['total_compra'], 2) . "</p>";
            echo "<div class='d-flex justify-content-end'>";
            echo "<button class='btn btn-primary' onclick='verTicket(" . $venta['id_venta'] . ")' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver los detalles completos de esta compra'>
            <i class='fas fa-ticket-alt'></i> Ver Ticket</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p class='text-center text-muted'>No se encontraron compras.</p>";
    }
    ?>
</section>

<script>
    function verTicket(idVenta) {
        window.location.href = 'ticket/view_ticket.php?id_venta=' + idVenta;
    }
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
