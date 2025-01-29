<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mueblería ┃ Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Nunito', sans-serif;
        }

        .register-container {
            background-color: #ffffff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            padding: 40px;
            max-width: 600px;
            margin: auto;
        }

        .form-label {
            color: #0056b3;
        }

        .btn-primary {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .btn-primary:hover {
            background-color: #004494;
            border-color: #004494;
        }

        .register-title {
            color: #0056b3;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="register-container">
            <h2 class="text-center register-title mb-4">Registro de Cliente</h2>

            <form action="register_process.php" method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Ingrese su nombre" required>
                </div>

                <div class="mb-3">
                    <label for="apellido_paterno" class="form-label">Apellido Paterno</label>
                    <input type="text" name="apellido_paterno" class="form-control" id="apellido_paterno" placeholder="Ingrese su apellido paterno" required>
                </div>

                <div class="mb-3">
                    <label for="apellido_materno" class="form-label">Apellido Materno</label>
                    <input type="text" name="apellido_materno" class="form-control" id="apellido_materno" placeholder="Ingrese su apellido materno" required>
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" name="telefono" class="form-control" id="telefono" placeholder="Ingrese su teléfono" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Ingrese su correo electrónico" required>
                </div>

                <div class="mb-3">
                    <label for="contrasena" class="form-label">Contraseña</label>
                    <input type="password" name="contrasena" class="form-control" id="contrasena" placeholder="Ingrese su contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">Registrar</button>

                <div class="text-center mt-2">
                    <a href="login.php" class="link-secondary text-decoration-none">¿Ya tienes una cuenta? Inicia sesión aquí</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
