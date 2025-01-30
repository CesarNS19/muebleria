<?php
require '../../../mysql/connection.php';
$title = "Admin Categories";
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<title><?php echo $title; ?></title>

<div id="Alert"></div>

<section class="company-header">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoriesModal" style="float: right; margin: 10px;">
            Agregar Categoria
        </button><br/>
    </section><br/>

<!-- Modal para añadir categoria -->
<div class="modal fade" id="addCategoriesModal" tabindex="-1" role="dialog" aria-labelledby="addCategoriesModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoriesModalLabel">Agregar Nueva Categoría</h5>
            </div>
            <form action="add_categories.php" method="POST">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="">Categoría</label>
                        <input type="text" name="categoria" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Descriptción</label>
                        <textarea name="descripcion" class="form-control" required></textarea>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Agregar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>