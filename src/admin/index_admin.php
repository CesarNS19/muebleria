<?php
session_start();

require '../../mysql/connection.php';

if (isset($_COOKIE['timezone'])) {
    date_default_timezone_set($_COOKIE['timezone']);
}

$hour = date('H');
if ($hour >= 5 && $hour < 12) {
    $greeting = 'Buenos Días';
} elseif ($hour >= 12 && $hour < 19) {
    $greeting = 'Buenas Tardes';
} else {
    $greeting = 'Buenas Noches';
}

$title = "Muebleria ┃ Dashboard Admin";

$sql = "SELECT p.id_producto, c.nombre AS categoria, m.nombre AS marca, p.nombre, p.descripcion, p.color, p.tamaño, p.capacidad, p.precio
        FROM productos p
        JOIN categorias c ON p.id_categoria = c.id_categoria
        JOIN marcas m ON p.id_marca = m.id_marca";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $title; ?></title>

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index_admin.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-couch"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Mueblería París</div>
            </a>

            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="index_admin.php">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span></a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">
                Productos
            </div>
            <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-layer-group"></i>
                <span>Categorías</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="#" onclick="loadContent('categories/categories.php')">
                        <i class="fas fa-layer-group"></i>Categorias
                    </a>
                </div>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-globe"></i>
                <span>Marcas</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="#" onclick="loadContent('brands/brands.php')">
                        <i class="fas fa-globe"></i> Marcas
                    </a>
                </div>
            </div>
        </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-box"></i>
                    <span>Productos</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="#" onclick="loadContent('products/products.php')">
                        <i class="fas fa-box"></i> Productos
                    </a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Ventas
            </div>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse"
                    aria-expanded="true" aria-controls="collapse">
                    <i class="fas fa-dollar-sign"></i>
                    <span>Ventas</span>
                </a>
                <div id="collapse" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="login/login.php">Login</a>
                        <a class="collapse-item" href="register.html">Register</a>
                        <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                        <a class="collapse-item" href="404.html">404 Page</a>
                        <a class="collapse-item" href="blank.html">Blank Page</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li>

            <hr class="sidebar-divider d-none d-md-block">
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <ul class="navbar-nav ml-auto">

                    <?php
                        if (isset($_SESSION['nombre'], $_SESSION['apellido_paterno'], $_SESSION['apellido_materno'])) {
                            $fullName = $_SESSION['nombre'] . ' ' . $_SESSION['apellido_paterno'] . ' ' . $_SESSION['apellido_materno'];

                            echo "
                            <div class='nav-item' style='display: flex; align-items: center; margin-left: auto;'>
                                <a class='nav-link' style='color: black; font-size: 15px; text-decoration: none; font-weight: normal;'>
                                    $greeting $fullName
                                </a>
                            </div>";
                        } else {
                            echo "<a class='nav-link' href='../login/login.php' style='color: black; font-size: 18px; text-decoration: none; font-weight: 600;'>Log In</a>";
                        }
                    ?>

                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">   
                            </span>
                            <img class="img-profile rounded-circle" src="../../img/undraw_profile.svg">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../../login/login.php">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

    <div id="main-content" class="container-fluid">
    </div>

    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../vendor/chart.js/Chart.min.js"></script>
    <script src="../../js/demo/chart-area-demo.js"></script>
    <script src="../../js/demo/chart-pie-demo.js"></script>

    <script>
        function loadContent(url) {
            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    $('#main-content').html(data);

                    let pageTitle = $('#main-content title').text();
                    $('title').text("Mueblería ┃ " + pageTitle);
                },
                error: function () {
                    alert('Error al cargar el contenido');
                }
            });
        }

        document.cookie = "timezone=" + Intl.DateTimeFormat().resolvedOptions().timeZone;
    </script>
</body>
</html>