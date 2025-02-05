<?php
session_start();
include '../mysql/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("
        SELECT id, email, contrasena, rol, nombre, apellido_paterno, apellido_materno, estatus
        FROM (
            SELECT id_cliente AS id, email AS email, contrasena, rol, nombre AS nombre, apellido_paterno, apellido_materno, estatus
            FROM clientes
            UNION
            SELECT id_empleado AS id, email AS email, contrasena, rol, nombre, apellido_paterno, apellido_materno, estatus
            FROM empleados
        ) AS usuarios
        WHERE email = ?
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['contrasena'])) {
            if ($row['estatus'] === 'inactivo') {
                echo "Tu cuenta está inactiva. Por favor, contacta con el administrador.";
            } else {
                $_SESSION['user'] = $row['email'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['apellido_paterno'] = $row['apellido_paterno'];
                $_SESSION['apellido_materno'] = $row['apellido_materno'];

                $rol = strtolower(trim($row['rol']));

                switch ($rol) {
                    case 'admin':
                        $_SESSION['id_cliente'] = $row['id'];
                        header("Location: ../src/admin/index_admin.php");
                        break;
                    case 'empleado':
                        $_SESSION['id_empleado'] = $row['id'];
                        header("Location: ../src/employees/index_employee.php");
                        break;
                    default:
                        $_SESSION['id_cliente'] = $row['id'];
                        header("Location: ../src/customers/index_customers.php");
                        break;
                }
                exit();
            }
        } else {
            echo "Credenciales incorrectas.";
        }
    } else {
        echo "Credenciales incorrectas.";
    }

    $stmt->close();
}
$conn->close();
?>
