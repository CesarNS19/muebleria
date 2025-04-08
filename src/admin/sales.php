<?php
session_start();
include "../../mysql/connection.php";
include "slidebar.php";
$title = "Muebleria ┃ Admin Ventas";
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="light">
<head>
<meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <title><?php echo $title; ?></title>
</head>
<body>

<div class="container-fluid d-flex">
    <main class="flex-fill p-4 overflow-auto" id="main-content">
        <h2 class="text-center text-primary fw-bold mt-2">Ventas</h2>
        <?php
        $sql = "SELECT v.id_venta, v.fecha, v.hora, 
                       SUM(d.cantidad) AS total_articulos, 
                       SUM(d.subtotal) AS total_compra,
                       CONCAT(c.nombre, ' ', c.apellido_paterno, ' ', c.apellido_materno) AS nombre_completo
                FROM ventas v
                JOIN detalle_venta d ON v.id_venta = d.id_venta
                JOIN clientes c ON v.id_cliente = c.id_cliente
                GROUP BY v.id_venta
                ORDER BY v.fecha DESC, v.hora DESC";

        $result = $conn->query($sql);
        $meses = [
            'January' => 'enero', 'February' => 'febrero', 'March' => 'marzo', 'April' => 'abril',
            'May' => 'mayo', 'June' => 'junio', 'July' => 'julio', 'August' => 'agosto',
            'September' => 'septiembre', 'October' => 'octubre', 'November' => 'noviembre', 'December' => 'diciembre'
        ];

        if ($result->num_rows > 0) {
            echo "<div class='row row-cols-1 row-cols-md-2 g-4'>";
            while ($venta = $result->fetch_assoc()) {
                $fecha = new DateTime($venta['fecha']);
                $hora = new DateTime($venta['hora']);
                $fecha_formateada = $fecha->format('d') . " de " . $meses[$fecha->format('F')] . " de " . $fecha->format('Y');
                $hora_formateada = $hora->format('h:i A');
                $total_color = ($venta['total_compra'] >= 100) ? 'text-primary' : 'text-danger';

                echo "<div class='col'>";
                echo "<div class='card border border-ligth shadow-sm rounded-4 h-100'>";

                echo "<div class='card-header text-center fw-semibold rounded-top-4' style='border-top-left-radius: 1rem; border-top-right-radius: 1rem;'>";
                echo "<i class='fas fa-receipt me-2'></i> Venta Realizada";
                echo "</div>";

                echo "<div class='card-body px-4 py-3 d-flex flex-column justify-content-between h-100'>";

                    echo "<div class='d-flex justify-content-between mb-3 text-muted small'>";
                    echo "<div><i class='fas fa-calendar-alt me-1'></i> " . htmlspecialchars($fecha_formateada) . "</div>";
                    echo "<div><i class='fas fa-clock me-1'></i> " . htmlspecialchars($hora_formateada) . "</div>";
                    echo "</div>";

                    echo "<div class='mb-3'>";
                    echo "<div class='fw-semibold text-body mb-1'><i class='fas fa-user me-2'></i>Cliente</div>";
                    echo "<div class='text-muted'>" . htmlspecialchars($venta['nombre_completo']) . "</div>";
                    echo "</div>";

                    echo "<div class='row mb-3'>";
                    echo "<div class='col-6'>";
                    echo "<div class='fw-semibold text-body'><i class='fas fa-box me-2'></i>Artículos: " . htmlspecialchars($venta['total_articulos']) . "</div>";
                    echo "</div>";

                    echo "<div class='col-6 text-end'>";
                    echo "<div class='fw-bold'>Total:\$ " . number_format($venta['total_compra'], 2) . "</div>";
                    echo "</div>";
                    echo "</div>";

                    echo "<div class='mt-auto text-end'>";
                    echo "<button class='btn btn-primary btn-sm rounded-pill px-4' onclick='verTicket(" . $venta['id_venta'] . ")' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver ticket de venta'>";
                    echo "<i class='fas fa-eye me-1'></i>";
                    echo "</button>";
                    echo "</div>";

                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p class='text-center text-muted'>No se han realizado ventas.</p>";
        }
        ?>
    </main>
</div>
</body>
</html>
