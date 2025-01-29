<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Mueblería ┃ Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f0f8ff;
            font-family: 'Nunito', sans-serif;
        }

        .login-container {
            background-color: #ffffff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            overflow: hidden;
        }

        .bg-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px 0 0 15px;
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

        a.link-secondary {
            color: #007bff;
        }

        a.link-secondary:hover {
            color: #0056b3;
        }

        .login-title {
            color: #0056b3;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="row w-100">
            <div class="col-md-8 mx-auto">
                <div class="row login-container">
                    <div class="col-md-6 d-none d-md-block bg-image">
                        <img src="../img/login.jpeg" alt="Mueblería">
                    </div>
                    <div class="col-md-6 p-5">
                        <h2 class="text-center login-title mb-4">Iniciar Sesión</h2>
                        
                        <form action="login_process.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Ingrese su correo" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Ingrese su contraseña" required>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Recordarme
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-3">Iniciar Sesión</button>

                            <div class="text-center">
                                <a href="forgot-password.html" class="link-secondary text-decoration-none">¿Olvidaste tu contraseña?</a>
                            </div>
                            <div class="text-center mt-2">
                                <a href="register.php" class="link-secondary text-decoration-none">¿No tienes una cuenta? Regístrate aquí</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JavaScript Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
