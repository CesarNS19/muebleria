<?php
session_start();
include "../../mysql/connection.php";
include "slidebar.php";
$title = "Muebleria ┃ Perfil Empleado";
$id_empleado = $_SESSION['id_empleado'];

$sql = "SELECT nombre, apellido_paterno, apellido_materno, telefono, email, estatus, rol 
        FROM empleados WHERE id_empleado = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_empleado);
$stmt->execute();
$result = $stmt->get_result();
$userData = $result->fetch_assoc();
?>

<style>
    .container{
        background-color: white;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<title><?php echo $title; ?></title>

<!-- Card para mostrar información del empleado-->
<div class="container mt-5">
    <div class="card mx-auto shadow-lg" style="max-width: 500px; border-radius: 15px;">
        <div class="card-body text-center">
        <div id="Alert" class="container"></div>
            <div class="mb-4">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Perfil Admin" class="rounded-circle" width="120">
            </div>
            <h3 class="card-title text-primary fw-bold">Mi Perfil</h3>
            <ul class="list-group list-group-flush text-start">
                <li class="list-group-item"><strong>Nombre:</strong> <?php echo $userData['nombre']; ?></li>
                <li class="list-group-item"><strong>Apellido Paterno:</strong> <?php echo $userData['apellido_paterno']; ?></li>
                <li class="list-group-item"><strong>Apellido Materno:</strong> <?php echo $userData['apellido_materno']; ?></li>
                <li class="list-group-item"><strong>Teléfono:</strong> <?php echo $userData['telefono']; ?></li>
                <li class="list-group-item"><strong>Email:</strong> <?php echo $userData['email']; ?></li>
            </ul>
            <button class="btn btn-outline-primary mt-4 px-4" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <i class="fas fa-user-edit me-1"></i> Editar Perfil
            </button>
        </div>
    </div>
</div>

<!-- Modal para editar el perfil-->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="profile/edit_profile.php" method="POST">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editProfileModalLabel">Editar Perfil</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $userData['nombre']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                        <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" value="<?php echo $userData['apellido_paterno']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido_materno" class="form-label">Apellido Materno</label>
                        <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" value="<?php echo $userData['apellido_materno']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" value="<?php echo $userData['telefono']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $userData['email']; ?>" required>
                    </div>

                    <h6 class="text-primary">Cambiar Contraseña</h6>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    window.onload = () => {
        const productCount = <?php echo array_sum(array_column($_SESSION["carrito"] ?? [], "cantidad")); ?>;
        const badge = document.querySelector(".nav-item .badge");
        if (badge) {
            badge.textContent = productCount > 0 ? productCount : "";
        }
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
