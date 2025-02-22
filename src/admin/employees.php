<?php
session_start();
require '../../mysql/connection.php';
require 'slidebar.php';
$title = "Muebleria ┃ Admin Employees";
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<title><?php echo $title; ?></title>

<div id="Alert" class="container"></div>

<section class="company-header">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeesModal" style="float: right; margin: 10px;">
            Agregar Empleado
        </button>
    </section>
    <div class="modal fade" id="addEmployeesModal" tabindex="-1" role="dialog" aria-labelledby="addEmployeesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addEmployeesModalLabel">Add Employee</h5>
            </div>
            <form action="employees/add_employee.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Nombre del Empleado</label>
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

<!-- Modal para editar empleado -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editEmployeeLabel">Editar Empleado</h5>
            </div>
            <form action="employees/edit_employee.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_empleado" id="edit_id_empleado">
                    
                    <div class="form-group mb-3">
                        <label for="edit_nombre">Nombre del Empleado</label>
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

<!-- Modal para eliminar empleados -->
<div class="modal fade" id="deleteEmployeeModal" tabindex="-1" aria-labelledby="deleteEmployeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteEmployeeModalLabel">Confirmar Eliminación</h5>
      </div>
      <form action="employees/delete_employee.php" method="POST">
      <div class="modal-body">
      <input type="hidden" name="id_empleado" id="delete_id_empleado">
        <p>¿Estás seguro de que deseas eliminar al empleado?, Esta acción no se puede deshacer.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<div id ="main-content" class="container-fluid">
<!-- Tabla de Empleados -->
<section class="services-table container my-4">
    <h2 class="fw-bold text-primary text-center">Administrar Empleados</h2>
    <div class="table-responsive">
        <table class="table table-hover table-bordered text-center align-middle shadow-sm rounded-3">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Empleado</th>
                    <th>Telefono</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM empleados";
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
                            echo "<a href='employees/status_employee.php?id=" . $row['id_empleado'] . "&estatus=inactivo' class='btn btn-warning btn-sm me-2 rounded-pill shadow-sm'>
                                    <i class='fas fa-ban'></i> Desactivar
                                  </a>";
                        } else {
                            echo "<a href='employees/status_employee.php?id=" . $row['id_empleado'] . "&estatus=activo' class='btn btn-success btn-sm me-2 rounded-pill shadow-sm'>
                                    <i class='fas fa-check-circle'></i> Activar
                                  </a>";
                        }

                        echo "<button class='btn btn-sm btn-outline-primary me-2 rounded-pill shadow-sm' onclick='openEditModal(" . json_encode($row) . ")'>
                                <i class='fas fa-edit'></i> Editar
                              </button>
                              <button class='btn btn-sm btn-outline-danger me-2 rounded-pill shadow-sm' onclick='openDeleteModal(" . json_encode($row) . ")'>
                                <i class='fas fa-trash-alt'></i> Eliminar
                              </button>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center text-muted'>No hay empleados disponibles</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</section>
</div>

<script>

    function openEditModal(employeesData) {
        $('#edit_id_empleado').val(employeesData.id_empleado);
        $('#edit_nombre').val(employeesData.nombre);
        $('#edit_apellido_paterno').val(employeesData.apellido_paterno);
        $('#edit_apellido_materno').val(employeesData.apellido_materno);
        $('#edit_telefono').val(employeesData.telefono);
        $('#edit_email').val(employeesData.email);
        $('#editEmployeeModal').modal('show');
    }

    function openDeleteModal(Data) {
        $('#delete_id_empleado').val(Data.id_empleado);
        $('#deleteEmployeeModal').modal('show');
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