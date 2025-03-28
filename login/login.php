<?php
session_start();
if (isset($_SESSION['status_message'])) {
    echo "<script>console.log('Status: " . $_SESSION['status_message'] . "');</script>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mueblería ┃ Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Nunito', sans-serif;
        }

        .login-container {
            background-color: #ffffff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            overflow: hidden;
            height: 600px;
        }

        .animation-container {
            color: white;
            display: flex;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .furniture-icon {
            font-size: 4rem;
            opacity: 0;
            animation: fadeIn 1.5s forwards;
        }

        .furniture-icon:nth-child(1) { animation-delay: 0.3s; }
        .furniture-icon:nth-child(2) { animation-delay: 0.6s; }
        .furniture-icon:nth-child(3) { animation-delay: 0.9s; }
        .furniture-icon:nth-child(4) { animation-delay: 1.2s; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-label {
            color: #6c757d;
        }

        .login-title {
            font-weight: 700;
        }

        .texto {
            font-weight: bold;
            font-size: 36px;
        }
    </style>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="row w-100">
            <div class="col-md-8 mx-auto">
                <div class="row login-container">

                    <div class="col-md-6 d-none d-md-flex animation-container bg-primary">
                        <h3 class="mb-3 texto">MUEBLERÍA PARÍS</h3>
                        <div class="d-flex justify-content-around w-100 mt-5">
                            <i class="fas fa-couch furniture-icon"></i>
                            <i class="fas fa-bed furniture-icon"></i>
                            <i class="fas fa-chair furniture-icon"></i>
                            <i class="fas fa-toilet furniture-icon"></i>
                        </div>
                    </div>

                    <div class="col-md-6 p-5" id="form-container">
                        <h2 class="text-center login-title mb-4 text-primary mt-4">Iniciar Sesión</h2>
                        <form action="send_token.php" method="POST">
                            <div id="Alert"></div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Ingrese su correo">
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">Enviar Correo</button>

                            <div class="text-center mt-2">
                                <a href="?form=forgot_password" class="link-secondary text-decoration-none">¿Olvidaste tu contraseña?</a>
                            </div>

                            <div class="text-center mt-2">
                                <a href="?form=register" class="link-secondary text-decoration-none">¿No tienes una cuenta? Regístrate aquí</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    function mostrarFormulario(formType) {
        if (formType === "register") {
            document.getElementById('form-container').innerHTML = `
                <h2 class="text-center login-title mb-4 text-primary">Registro</h2>
                <form action="register_process.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" id="nombre">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                            <input type="text" name="apellido_paterno" class="form-control" id="apellido_paterno">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="apellido_materno" class="form-label">Apellido Materno</label>
                            <input type="text" name="apellido_materno" class="form-control" id="apellido_materno">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" id="telefono">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="contrasena" class="form-label">Contraseña</label>
                            <input type="password" name="contrasena" class="form-control" id="contrasena" placeholder="Contraseña">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="confirmar_contrasena" class="form-label">Confirmar</label>
                            <input type="password" name="confirmar_contrasena" class="form-control" id="confirmar_contrasena" placeholder="Confirmar contraseña">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Registrar</button>
                    <div class="text-center mt-2">
                        <a href="login.php" class="link-secondary text-decoration-none"">¿Ya tienes una cuenta? Inicia sesión aquí</a>
                    </div>
                </form>
            `;
        } else if (formType === "forgot_password") {
            document.getElementById('form-container').innerHTML = `
                <h2 class="text-center login-title mb-4 text-primary mt-5">Recuperar Contraseña</h2>
                <form action="forgot_password_process.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo Electrónico</label>
                        <input type="email" name="email" class="form-control" placeholder="Ingrese su correo">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Enviar Correo</button>
                    <div class="text-center mt-2">
                        <a href="login.php" class="link-secondary text-decoration-none"">¿Ya tienes una cuenta? Inicia sesión aquí</a>
                    </div>
                </form>
            `;
        } else if (formType === "code"){
            document.getElementById('form-container').innerHTML = `
            <h2 class="text-center login-title mb-4 text-primary mt-4">Iniciar Sesión</h2>
            <form action="login_process.php" method="POST">
                            <div id="Alert"></div>
                            <div class="mb-3">
                                <label for="code" class="form-label">Codigo de acceso</label>
                                <input type="text" name="code" class="form-control" id="code" placeholder="Ingrese su código de acceso">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Ingrese su contraseña">
                            </div>


                            <button type="submit" class="btn btn-primary w-100 mb-3">Iniciar Sesión</button>

                            <div class="text-center mt-2">
                                <a href="?form=forgot_password" class="link-secondary text-decoration-none">¿Olvidaste tu contraseña?</a>
                            </div>

                            <div class="text-center mt-2">
                                <a href="?form=register" class="link-secondary text-decoration-none">¿No tienes una cuenta? Regístrate aquí</a>
                            </div>
                        </form>
                        `;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const formType = urlParams.get('form');
        if (formType) {
            mostrarFormulario(formType);
        }
    });

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

        document.addEventListener("DOMContentLoaded", function() {
            if (localStorage.getItem("rememberEmail")) {
                document.getElementById("email").value = localStorage.getItem("rememberEmail");
                document.getElementById("remember").checked = true;
            }
            
            document.querySelector("form").addEventListener("submit", function() {
                if (document.getElementById("remember").checked) {
                    localStorage.setItem("rememberEmail", document.getElementById("email").value);
                } else {
                    localStorage.removeItem("rememberEmail");
                }
            });
        });

        
</script>

</body>
</html>
