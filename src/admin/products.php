<?php
require '../../mysql/connection.php';
require 'slidebar.php';
$title = "Muebleria ┃ Admin Products";
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<title><?php echo $title; ?></title>

<div id="Alert"></div>

<section class="company-header">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal" style="float: right; margin: 10px;">
            Agregar Producto
        </button><br/>
    </section><br/>