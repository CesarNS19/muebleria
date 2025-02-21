<?php
require '../../../mysql/connection.php';

$searchQuery = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $searchQuery = " AND p.nombre LIKE '%" . $conn->real_escape_string($searchTerm) . "%' ";
}

$sql = "SELECT p.id_producto, c.nombre AS categoria, m.nombre AS marca, p.nombre, p.descripcion, p.color, p.precio, p.imagen
        FROM productos p
        JOIN categorias c ON p.id_categoria = c.id_categoria
        JOIN marcas m ON p.id_marca = m.id_marca
        WHERE (c.nombre = 'alacenas' OR c.nombre = 'gabinetes')" . $searchQuery;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imagePath = !empty($row['imagen']) ? "img/" . $row['imagen'] : "";
        echo '
        <div class="col">
            <div class="card h-100 shadow-lg rounded-3 text-center">
            <h5 class="card-title fw-bold mt-2">' . $row["nombre"] . '</h5>
                <div class="position-relative overflow-hidden d-flex justify-content-center align-items-center" style="height: 100px;">
                    <img src="' . $imagePath . '" alt="Imagen del Producto" style="height: 80%; width: 30%;"></div>
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
    echo "<p>No se encontraron productos para tu búsqueda.</p>";
}

$conn->close();
?>
