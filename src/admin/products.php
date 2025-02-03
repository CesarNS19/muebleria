<?php
session_start();
require '../../mysql/connection.php';
require 'slidebar.php';
$title = "Muebleria ┃ Admin Products";
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<title><?php echo $title; ?></title>

<div id="Alert"></div>

<section class="company-header">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductsModal" style="float: right; margin: 10px;">
            Agregar Producto
        </button><br/>
    </section><br/>

<!-- Modal para añadir productos -->
<div class="modal fade" id="addProductsModal" tabindex="-1" role="dialog" aria-labelledby="addProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductsModalLabel">Agregar Nuevo Producto</h5>
            </div>
            <form action="products/add_product.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                <div class="form-group mb-3">
                    <label>Categoría</label>
                    <select name="id_categoria" class="form-control" required>
                        <option value="">Seleccione una categoría</option>
                        <?php
                        $categoria_sql = "SELECT * FROM categorias";
                        $categoria_result = $conn->query($categoria_sql);

                        while ($categoria = $categoria_result->fetch_assoc()) {
                            echo "<option value='" . $categoria['id_categoria'] . "'>" . htmlspecialchars($categoria['nombre']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label>Marca</label>
                    <select name="id_marca" class="form-control" required>
                        <option value="">Seleccione una marca</option>
                        <?php
                        $marca_sql = "SELECT * FROM marcas";
                        $marca_result = $conn->query($marca_sql);

                        while ($marca = $marca_result->fetch_assoc()) {
                            echo "<option value='" . $marca['id_marca'] . "'>" . htmlspecialchars($marca['nombre']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                    <div class="form-group mb-3">
                        <label for="">Nombre del producto</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Descripción</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Precio</label>
                        <input type="number" name="precio" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Stock</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Color</label>
                        <input type="text" name="color" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="file" name="imagen_producto" class="form-control" placeholder="Imágen del producto">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Agregar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar productos -->
<div class="modal fade" id="editProductsModal" tabindex="-1" role="dialog" aria-labelledby="editProductsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductsLabel">Editar Producto</h5>
            </div>
            <form action="products/edit_product.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_producto" id="edit_id_producto">
                    
                    <div class="form-group mb-3">
                        <label for="edit_categoria">Categoría</label>
                        <select name="id_categoria" id="edit_categoria" class="form-control" required>
                            <?php
                            $categoria_sql = "SELECT * FROM categorias";
                            $categoria_result = $conn->query($categoria_sql);

                            while ($categoria = $categoria_result->fetch_assoc()) {
                                echo "<option value='" . $categoria['id_categoria'] . "'>" . htmlspecialchars($categoria['nombre']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_marca">Categoría</label>
                        <select name="id_marca" id="edit_marca" class="form-control" required>
                            <?php
                            $categoria_sql = "SELECT * FROM marcas";
                            $categoria_result = $conn->query($categoria_sql);

                            while ($categoria = $categoria_result->fetch_assoc()) {
                                echo "<option value='" . $categoria['id_marca'] . "'>" . htmlspecialchars($categoria['nombre']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_nombre">Nombre del producto</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_descripcion">Descripción del producto</label>
                        <textarea name="descripcion" class="form-control" id="edit_descripcion" required></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_precio">Precio del Producto</label>
                        <input type="number" name="precio" id="edit_precio" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_stock">Stock</label>
                        <input type="number" name="stock" id="edit_stock" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_color">Color</label>
                        <input type="text" name="color" id="edit_color" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Imágen Actual</label>
                        <div>
                            <img id="current_image" src="" width="100" alt="Imágen del producto">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_imagen">Actualizar Imágen del Producto</label>
                        <input type="file" name="imagen_producto" id="edit_imagen" class="form-control">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tabla de Productos -->
<section class="products-table container my-4">
    <h2 class="text-center mb-4">Administrar Productos</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Producto</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Color</th>
                    <th>Imágen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM productos";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                            echo "<td>". htmlspecialchars($row['precio']). "</td>";
                            echo "<td>". htmlspecialchars($row['stock']). "</td>";
                            echo "<td>". htmlspecialchars($row['color']). "</td>";
                            echo "<td><img src='img/" . htmlspecialchars($row['imagen']) . "' width='100px' height='60px' alt='Imágen Producto'></td>";
                            echo "<td>";
                            echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Editar Producto'>
                                    <i class='fas fa-edit'></i>
                                </button>
                                <a href='products/delete_product.php?id=" . $row['id_producto'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este producto?\")' title='Eliminar Producto'>
                                    <i class='fas fa-trash'></i>
                                </a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No se encontrarón productos</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</section>

<script>

    function openEditModal(productsData) {
        $('#edit_id_producto').val(productsData.id_producto);
        $('#edit_categoria').val(productsData.id_categoria);
        $('#edit_id_marca').val(productsData.id_marca);
        $('#edit_nombre').val(productsData.nombre);
        $('#edit_descripcion').val(productsData.descripcion);  
        $('#edit_precio').val(productsData.precio);
        $('#edit_stock').val(productsData.stock);
        $('#edit_color').val(productsData.color);
        $('#current_image').attr('src', 'img/' + productsData.imagen);
        $('#editProductsModal').modal('show');
    }

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