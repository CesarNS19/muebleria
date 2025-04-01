<?php
session_start();
require '../../../mysql/connection.php';

if (!isset($_SESSION['email'])) {
    echo "<div class='alert alert-danger'>Usuario no autenticado.</div>";
    exit;
}

$email = $_SESSION['email'];
$sql = "SELECT c.id, p.nombre, c.descripcion, c.cantidad, c.imagen, c.precio, c.subtotal, p.stock
        FROM cart c
        JOIN productos p ON c.id_producto = p.id_producto
        WHERE c.email = '$email' ORDER BY c.id ASC";
$result = $conn->query($sql);

$total = 0;

if ($result->num_rows > 0) {
    echo '<div class="container">';
    echo '    <div class="row g-4">';
    
    while ($row = $result->fetch_assoc()) {
        $cantidad = (int)$row['cantidad'];
        $precio = (float)$row['precio'];
        $stock = (int)$row['stock'];
        $subtotal = (float)$row['subtotal'];
        $imagePath = !empty($row['imagen']) ? "img/" . $row['imagen'] : "img/default.jpg";
        $total += $subtotal;

        echo '        <div class="col-md-6">';
        echo '            <div class="card mb-3 shadow-sm h-100 d-flex flex-column">';
        echo '                <div class="row g-0 align-items-center">';
        echo '                    <div class="col-4">';
        echo '                        <img src="' . $imagePath . '" class="img-fluid rounded-start" style="height: 150px; object-fit: cover; width: 100%;" alt="Imagen del producto">';
        echo '                    </div>';
        echo '                    <div class="col-8">';
        echo '                        <div class="card-body d-flex flex-column flex-grow-1">';
        echo '                            <h5 class="card-title">' . htmlspecialchars($row['nombre']) . '</h5>';
        echo '                            <p class="card-text flex-grow-1 text-truncate" style="max-height: 60px; overflow: hidden;">' . htmlspecialchars($row['descripcion']) . '</p>';
        echo '                            <div class="d-flex justify-content-between">';
        echo '                                <p class="fw-bold ' . ($stock == 0 ? 'text-danger' : '') . '"><i class="fas fa-box"></i> Existencia: ' . $stock . '</p>';
        echo '                                <p class="fw-bold"><i class="fas fa-sort-numeric-up"></i> Cantidad: ' . $cantidad . '</p>';
        echo '                            </div>';
        echo '                            <div class="d-flex justify-content-between">';
        echo '                                <p class="fw-bold text-success"><i class="fas fa-dollar-sign"></i> Precio: $' . number_format($precio, 2) . '</p>';
        echo '                                <p class="fw-bold text-primary"><i class="fas fa-money-bill"></i> Subtotal: $' . number_format($subtotal, 2) . '</p>';
        echo '                            </div>';
        echo '                            <div class="d-flex justify-content-between mt-auto">';
        echo '                                <button class="btn btn-outline-primary btn-sm" onclick="sumCant(' . $row['id'] . ')"><i class="fas fa-plus"></i> Agregar</button>';
        echo '                                <button class="btn btn-outline-success btn-sm" onclick="resCant(' . $row['id'] . ')"><i class="fas fa-minus"></i> Reducir</button>';
        echo '                                <button class="btn btn-outline-danger btn-sm" onclick="deleteCart(' . $row['id'] . ')"><i class="fas fa-trash-alt"></i> Eliminar</button>';
        echo '                            </div>';
        echo '                        </div>';
        echo '                    </div>';
        echo '                </div>';
        echo '            </div>';
        echo '        </div>';
    }
    
    echo '    </div>';
    echo '</div>';

    echo '<div class="container mt-4 d-flex justify-content-end">';
    echo '    <div>';
    echo '        <p class="fw-bold text-success text-end">Total: $' . number_format($total, 2) . '</p>';
    echo '    </div>';
    echo '</div>';
    
    echo '<div class="text-end mt-4 container">';
    echo '    <form method="POST" action="shopping_cart/add_shopping_cart.php" class="d-inline">';
    echo '        <button type="submit" class="btn btn-success rounded-pill px-3 py-1">';
    echo '            <i class="fa-solid fa-cart-arrow-down"></i> Comprar';
    echo '        </button>';
    echo '    </form>';
    echo '    <form method="POST" action="shopping_cart/empty_cart.php" class="d-inline">';
    echo '        <button type="submit" class="btn btn-danger rounded-pill px-3 py-1">';
    echo '            <i class="fas fa-trash"></i> Vaciar Carrito';
    echo '        </button>';
    echo '    </form>';
    echo '</div>';
} else {
    echo '<div class="alert alert-warning">No se encontraron productos en el carrito.</div>';
}
?>