<?php
require '../../../login/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoria = $_POST['categoria'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO categorias (categoria, descripcion)
            VALUES (?, ?)";
    
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $categoria, $descripcion);

    if ($stmt->execute()) {
        $_SESSION['status_message'] = "Categoria agregada exitosamente.";
        $_SESSION['status_type'] = "success";
    } else {
        $_SESSION['status_message'] = "Error al agregar la categoria: " . $stmt->error;
        $_SESSION['status_type'] = "danger";
    }

    $stmt->close();
    header("Location: categories.php");
    exit();
}

$con->close();
?>