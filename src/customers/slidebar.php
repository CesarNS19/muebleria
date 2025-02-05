<?php
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

$totalProductos = 0;
if (!empty($_SESSION["carrito"])) {
    foreach ($_SESSION["carrito"] as $producto) {
        $totalProductos += $producto["cantidad"];
    }
}
?>

<style>
    #accordionSidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    z-index: 1030;
    overflow-y: auto;
}

#content-wrapper {
    margin-left: 220px;
}

</style>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index_customers.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-couch"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Mueblería París</div>
            </a>

            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="index_customers.php">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span></a>
            </li>

            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-layer-group"></i>
                    <span>Categorías</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="rooms.php">
                            <i class="fas fa-couch"></i> Salas
                        </a>
                        <a class="collapse-item" href="canteens.php">
                            <i class="fas fa-utensils"></i> Comedores
                        </a>
                        <a class="collapse-item" href="bedrooms.php">
                            <i class="fas fa-bed"></i> Recámaras
                        </a>
                        <a class="collapse-item" href="office.php">
                            <i class="fas fa-briefcase"></i> Oficina
                        </a>
                        <a class="collapse-item" href="appliances.php">
                            <i class="fas fa-tv"></i> Electrodomésticos
                        </a>
                        <a class="collapse-item" href="decoration.php">
                            <i class="fas fa-paint-brush"></i> Decoración
                        </a>
                        <a class="collapse-item" href="beds.php">
                            <i class="fas fa-layer-group"></i> Camas y colchones
                        </a>
                        <a class="collapse-item" href="accesories.php">
                            <i class="fas fa-tags"></i> Accesorios
                        </a>
                        <a class="collapse-item" href="furniture.php">
                            <i class="fas fa-tree"></i> Muebles de exterior
                        </a>
                        <a class="collapse-item" href="cupboards.php">
                            <i class="fas fa-warehouse"></i> Alacenas y gabinetes
                        </a>
                        <a class="collapse-item" href="chairs.php">
                            <i class="fas fa-chair"></i> Sillas
                        </a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-shopping-cart"></i>
                    <span>Mi Carrito</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="shopping_cart.php">
                            <i class="fas fa-fw fa-shopping-cart"></i> Mi carrito
                        </a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">

            <div class="sidebar-heading">
                Addons
            </div>

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="../../login/login.php">Login</a>
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
            <nav class="navbar navbar-expand navbar-light bg-white topbar shadow">
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
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

                <li class="nav-item">
                    <a class="nav-link" href="shopping_cart.php">
                        <i class="fas fa-fw fa-shopping-cart"></i>
                        <?php if ($totalProductos > 0): ?>
                            <span class="badge badge-danger ml-2"><?php echo $totalProductos; ?></span>
                        <?php endif; ?>
                    </a>
                </li>

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
                        echo "<a class='nav-link' href='../../login/login.php' style='color: black; font-size: 18px; text-decoration: none; font-weight: 600;'>Log In</a>";
                    }
                    ?>
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small"></span>
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

    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../js/sb-admin-2.min.js"></script>
    <script src="../../vendor/chart.js/Chart.min.js"></script>
    <script src="../../js/demo/chart-area-demo.js"></script>
    <script src="../../js/demo/chart-pie-demo.js"></script>

 <script>
     document.cookie = "timezone=" + Intl.DateTimeFormat().resolvedOptions().timeZone;
</script>
</body>
</html>