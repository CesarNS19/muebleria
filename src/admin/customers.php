<?php
session_start();
require '../../mysql/connection.php';
require 'slidebar.php';
$title = "Muebleria ┃ Admin Customers";
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<title><?php echo $title; ?></title>

<div id="Alert"></div>

<section class="company-header">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCustomerModal" style="float: right; margin: 10px;">
            Agregar Cliente
        </button>
    </section>

<!-- Modal para añadir cliente -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" role="dialog" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomerLabel">Add New Customer</h5>
            </div>
            <form action="customers/add_customer.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Nombre del Cliente</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Apellido Materno</label>
                        <input type="text" name="apellido_materno" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Telefono</label>
                        <input type="text" name="telefono" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="contrasena" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Agregar Empleado</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar cliente -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Editar Cliente</h5>
            </div>
            <form action="customers/edit_customer.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_cliente" id="edit_id_cliente">
                    
                    <div class="form-group mb-3">
                        <label for="edit_nombre">Nombre del Cliente</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="edit_apellido_paterno">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" id="edit_apellido_paterno" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_apellido_materno">Apellido Materno</label>
                        <input type="text" name="apellido_materno" id="edit_apellido_materno" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_telefono">Phone</label>
                        <input type="text" name="telefono" id="edit_telefono" class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="edit_email">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
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

<!-- Tabla de clientes -->
<section class="services-table container my-1">
<div class="table-responsive">
    <table class="table table-bordered table-hover text-center">
        <thead class="thead-dark">
        <h2 class="text-center">Administrar Clientes</h2><br/>
            <tr>
                <th>Nombre del Cliente</th>
                <th>Telefono</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM clientes WHERE rol = 'usuario'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $nombre_completo = htmlspecialchars($row['nombre'] . ' ' . $row['apellido_paterno'] . ' ' . $row['apellido_materno']);
                    echo "<tr>";
                    echo "<td>" . $nombre_completo . "</td>";
                    echo "<td>" . htmlspecialchars($row['telefono']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['rol']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['estatus']) . "</td>";
                    echo "<td>";
                    
                    if ($row['estatus'] === 'activo') {
                        echo "<a href='customers/status_customer.php?id=" . $row['id_cliente'] . "&estatus=inactivo' class='btn btn-warning btn-sm me-2' title='Desactivar Cliente'>
                                <i class='fas fa-ban'></i>
                            </a>";
                    } else {
                        echo "<a href='customers/status_customer.php?id=" . $row['id_cliente'] . "&estatus=activo' class='btn btn-success btn-sm me-2' title='Activar Cliente'>
                                <i class='fas fa-check-circle'></i>
                            </a>";
                    }
                    echo "<button class='btn btn-info btn-sm me-1' onclick='openEditModal(" . json_encode($row) . ")' title='Editar Cliente'>
                            <i class='fas fa-edit'></i>
                        </button>
                        <a href='customers/delete_customer.php?id=" . $row['id_cliente'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de eliminar este cliente?\")' title='Eliminar Cliente'>
                            <i class='fas fa-trash'></i>
                        </a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>No hay clientes</td></tr>";
                }
            ?>
        </tbody>
    </table>
</div>
</section>

<script>

    function openEditModal(customerData) {
        $('#edit_id_cliente').val(customerData.id_cliente);
        $('#edit_nombre').val(customerData.nombre);
        $('#edit_apellido_paterno').val(customerData.apellido_paterno);
        $('#edit_apellido_materno').val(customerData.apellido_materno);
        $('#edit_telefono').val(customerData.telefono);
        $('#edit_email').val(customerData.email);
        $('#editCustomerModal').modal('show');
    }

    function openAddAddressModal(cliente) {
        document.getElementById('id_cliente_modal').value = cliente.id_cliente;
        
        document.getElementById('nombre_cliente_modal').value = cliente.nombre_cliente + ' ' + cliente.apellido_paterno + ' ' + cliente.apellido_materno;
    
        $('#addCustomerAddressModal').modal('show');
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