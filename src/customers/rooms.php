<?php
session_start();
require '../../mysql/connection.php';
require 'slidebar.php';

$title = "Muebleria ┃ Salas";

$sql = "SELECT p.id_producto, c.nombre AS categoria, m.nombre AS marca, p.nombre, p.descripcion, p.color, p.tamaño, p.capacidad, p.precio, p.imagen
        FROM productos p
        JOIN categorias c ON p.id_categoria = c.id_categoria
        JOIN marcas m ON p.id_marca = m.id_marca
        WHERE c.nombre = 'salas'";
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
                            <img src="' . $imagePath . '" class="card-img-top" alt="Imagen del Producto" style="height: 270px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title text-center">' . $row["nombre"] . '</h5>
                                <p class="card-text">Marca: ' . $row["marca"] . '</p>
                                <p class="card-text">Descripción: ' . $row["descripcion"] . '</p>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item">Color: ' . $row["color"] . '</li>
                                </ul>
                                <p class="mt-2 text-success text-center"><strong>Precio: $' . $row["precio"] . '</strong></p>
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
</body>
</html>