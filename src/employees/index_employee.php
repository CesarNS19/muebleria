<?php
session_start();
require '../../mysql/connection.php';

require 'slidebar.php';

$title = "Muebleria ┃ Dashboard Employee";
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<title><?php echo $title; ?></title>