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
        WHERE c.nombre = 'muebles'" . $searchQuery;
$result = $conn->query($sql);

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

<script>
    $(document).ready(function() {

        $(".add-to-cart").click(function () {
            let button = $(this);
            let id_producto = button.data("id");
            let nombre = button.data("nombre");
            let descripcion = button.data("descripcion");
            let precio = button.data("precio");
            let imagen = button.data("imagen");

            $.ajax({
                url: "shopping_cart/add_to_cart.php",
                type: "POST",
                data: {
                    id_producto: id_producto,
                    nombre: nombre,
                    descripcion: descripcion,
                    precio: precio,
                    imagen: imagen
                },
                dataType: "json",
                success: function (response) {
                    if (response.status === "success") {
                        mostrarToast("Éxito", response.message, "success");
                        $(".nav-item .badge").text(response.total > 0 ? response.total : "");
                    } else {
                        mostrarToast("Advertencia", response.message, "warning");
                    }
                },
                error: function () {
                    mostrarToast("Error", "Hubo un problema al agregar el producto.", "error");
                }
            });
        });
    });
</script>