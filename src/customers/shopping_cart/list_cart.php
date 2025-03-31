<?php
session_start();
require '../../../mysql/connection.php';

if (!isset($_SESSION['email'])) {
    echo "Usuario no autenticado.";
    exit;
}

$email = $_SESSION['email'];
$sql = "SELECT c.id, p.nombre, c.descripcion, c.cantidad, c.imagen, c.precio, c.subtotal, p.stock
        FROM cart c
        JOIN productos p ON c.id_producto = p.id_producto
        WHERE c.email = '$email' ORDER BY c.id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Stock</th>
                    <th>Imagen</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>';
    
    while ($row = $result->fetch_assoc()) {
        $cantidad = (int)$row['cantidad'];
        $precio = (float)$row['precio'];
        $stock = (int)$row['stock'];
        $subtotal = (float)$row['subtotal'];
        $imagePath = !empty($row['imagen']) ? "img/" . $row['imagen'] : "";

        echo '<tr>
                <td>' . $row['nombre'] . '</td>
                <td>' . $row['descripcion'] . '</td>
                <td>' . $stock . '</td>
                <td><img src="' . $imagePath . '" class="rounded" width="80px" height="50px" alt="imagen producto"></td>
                <td>' . $cantidad . '</td>
                <td>$' . number_format($precio, 2) . '</td>
                <td>$' . number_format($subtotal, 2) . '</td>
                <td>
                    <button class="btn btn-outline-primary btn-sm" onclick="sumCant(' . $row['id'] . ')"><i class="fas fa-plus"></i></button>
                    <button class="btn btn-outline-warning btn-sm" onclick="resCant(' . $row['id'] . ')"><i class="fas fa-minus"></i></button>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteCart(' . $row['id'] . ')"><i class="fas fa-trash-alt"></i></button>
                </td>
            </tr>';
    }
    echo '</tbody></table>';

    echo '<div class="text-end mt-4 container row justify-content-end">
            <div class="col-auto">
                <form method="POST" action="shopping_cart/add_shopping_cart.php">
                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 py-1">
                        <i class="fa-solid fa-cart-arrow-down"></i> Comprar
                    </button>
                </form>
            </div>
            <div class="col-auto">
                <form method="POST" action="shopping_cart/empty_cart.php">
                    <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3 py-1">
                        <i class="fas fa-trash"></i> Vaciar Carrito
                    </button>
                </form>
            </div>
        </div>';
} else {
    echo '<div>No se encontraron productos en el carrito.</div>';
}
?>
