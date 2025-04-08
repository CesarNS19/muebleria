<?php
session_start();
require '../../mysql/connection.php';
require 'slidebar.php';

$title = "Muebleria ┃ Carrito";
?>
<title><?php echo $title; ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


<div id="Alert" class="container mt-3"></div>
<div id="main-content" class="container-fluid">
<h1 class="mb-3 text-center mt-3">Mi Carrito de Compras</h1>
<div id="cartTableContainer" class="container mt-4"></div>
</div>
<script>
    $(document).ready(function () {
        cargarCart();
    });

    function cargarCart() {
        $.ajax({
            url: "shopping_cart/list_cart.php",
            type: "POST",
            success: function (res) {
                console.log(res);
                if (res) {
                    $("#cartTableContainer").html(res);
                } 
            },
            error: function () {
                alert("Ocurrió un error interno, por favor inténtalo más tarde");
            }
        });
    }

    function sumCant(id) {
        const sum = { id: id }; 
        $.ajax({
            url: "shopping_cart/sum_cant.php",
            type: "POST",
            data: sum,
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    mostrarToast("Éxito", res.message, "success");
                    cargarCart();
                    $('#cart-badge').text(res.total_cart);
                    if (res.total_cart > 0) {
                        $('#cart-badge').show();
                    } else {
                        $('#cart-badge').hide();
                    }
                } else {
                    mostrarToast("Alerta", res.message, "warning");
                }
            },
            error: function () {
                alert("Ocurrió un error interno, inténtalo más tarde");
            }
        });
    }

    function resCant(id) {
        const res = { id: id };
        $.ajax({
            url: "shopping_cart/res_cant.php",
            type: "POST",
            data: res,
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    mostrarToast("Éxito", res.message, "success");
                    cargarCart();
                    $('#cart-badge').text(res.total_cart);
                    if (res.total_cart > 0) {
                        $('#cart-badge').show();
                    } else {
                        $('#cart-badge').hide();
                    }
                } else {
                    mostrarToast("Error", res.message, "error");
                }
            },
            error: function () {
                alert("Ocurrió un error interno, inténtalo más tarde");
            }
        });
    }

    function deleteCart(id) {
        const del = { id: id };
        $.ajax({
            url: "shopping_cart/delete_cant.php",
            type: "POST",
            data: del,
            dataType: "json",
            success: function (res) {
                if (res.status === "success") {
                    mostrarToast("Éxito", res.message, "success");
                    cargarCart();
                    $('#cart-badge').text(res.total_cart);
                    if (res.total_cart > 0) {
                        $('#cart-badge').show();
                    } else {
                        $('#cart-badge').hide();
                    }
                } else {
                    mostrarToast("Error", res.message, "error");
                }
            },
            error: function () {
                alert("Ocurrió un error interno, inténtalo más tarde");
            }
        });
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
