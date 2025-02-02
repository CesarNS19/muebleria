<?php
require '../../mysql/connection.php';
require 'slidebar.php';
$title = "Muebleria ┃ Sillas";
$sql = "SELECT p.id_producto, c.nombre AS categoria, m.nombre AS marca, p.nombre, p.descripcion, p.color, p.tamaño, p.capacidad, p.precio
        FROM productos p
        JOIN categorias c ON p.id_categoria = c.id_categoria
        JOIN marcas m ON p.id_marca = m.id_marca
        WHERE c.nombre = 'sillas'";
$result = $conn->query($sql);
?>
<title><?php echo $title; ?></title>

<div class="container mt-5">
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <p class="card-text">Marca: ' . $row["marca"] . '</p>
                                    <p class="card-text">Descripción: ' . $row["descripcion"] . '</p>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">Color: ' . $row["color"] . '</li>
                                        <li class="list-group-item">Tamaño: ' . $row["tamaño"] . '</li>
                                        <li class="list-group-item">Capacidad: ' . $row["capacidad"] . '</li>
                                    </ul>
                                    <p class="mt-3 text-success text-center"><strong>Precio: $' . $row["precio"] . '</strong></p>
                                    <button class="btn btn-primary w-100">Añadir al carrito</button>
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