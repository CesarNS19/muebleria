<?php
require '../../../mysql/connection.php';
$title = "Admin Categories";
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<title><?php echo $title; ?></title>

<style>
    .services-table {
    display: block !important;
}

</style>
<div id="Alert"></div>

<section class="company-header">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoriesModal" style="float: right; margin: 10px;">
            Agregar Categoria
        </button><br/>
    </section><br/>

<!-- Modal para añadir categoria -->
<div class="modal fade" id="addCategoriesModal" tabindex="-1" role="dialog" aria-labelledby="addCategoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoriesModalLabel">Agregar Nueva Categoría</h5>
            </div>
            <form action="categories/add_categories.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="">Categoría</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Descripción</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Agregar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

 <!-- Tabla de Categorias -->
 <section class="services-table container my-4">
    <h2 class="text-center mb-4">Administrar Categorías</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Nombre de la categoría</th>
                    <th>Descripción de la categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM categorias";
                    $result = $conn->query($sql);
                    
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                            echo "<td>";
                            echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Editar categoría'>
                                    <i class='fas fa-edit'></i>
                                </button>
                                <a href='delete_service.php?id=" . $row['id_categoria'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta categiría?\")' title='Eliminar categoría'>
                                    <i class='fas fa-trash'></i>
                                </a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No se encontrarón categorías</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>
</section>

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