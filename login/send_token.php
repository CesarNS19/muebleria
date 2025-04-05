<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '../../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $conn = new mysqli("localhost", "root", "", "muebleria");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset('utf8');

    $verification_code = bin2hex(random_bytes(4));
    $timestamp = date("Y-m-d H:i:s");

    $sql_cliente = "SELECT id_cliente FROM clientes WHERE email = ?";
    $stmt_cliente = $conn->prepare($sql_cliente);
    $stmt_cliente->bind_param("s", $email);
    $stmt_cliente->execute();
    $result_cliente = $stmt_cliente->get_result();

    if ($result_cliente->num_rows > 0) {
        $user = $result_cliente->fetch_assoc();
        $user_id = $user['id_cliente'];

        $sql_update = "UPDATE clientes SET codigo_inicio = ?, timestamp = ? WHERE id_cliente = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssi", $verification_code, $timestamp, $user_id);
        $stmt_update->execute();

    } else {
        $sql_empleado = "SELECT id_empleado FROM empleados WHERE email = ?";
        $stmt_empleado = $conn->prepare($sql_empleado);
        $stmt_empleado->bind_param("s", $email);
        $stmt_empleado->execute();
        $result_empleado = $stmt_empleado->get_result();

        if ($result_empleado->num_rows > 0) {
            $user = $result_empleado->fetch_assoc();
            $user_id = $user['id_empleado'];

            $sql_update = "UPDATE empleados SET codigo_inicio = ?, timestamp = ? WHERE id_empleado = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssi", $verification_code, $timestamp, $user_id);
            $stmt_update->execute();
        } else {
            $_SESSION['status_message'] = "No se encontró ningún usuario con ese correo electrónico.";
            $_SESSION['status_type'] = "error";
            header("Location: login.php");
            exit();
        }
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'cesarneri803@gmail.com';
        $mail->Password = 'kyoi thod ximj mipk';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('cesarneri803@gmail.com', 'Mueblería París');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Código de Acceso';
        $mail->Body = "Tu código para iniciar sesión es: <strong>$verification_code</strong>";

        $mail->send();

        $_SESSION['status_message'] = "Te hemos enviado un correo con el código para iniciar sesión";
        $_SESSION['status_type'] = "success";
        header("Location: login.php?form=code");
        exit();
    } catch (Exception $e) {
        $_SESSION['status_message'] = "Error al enviar el correo: {$mail->ErrorInfo}";
        $_SESSION['status_type'] = "error";
        header("Location: login.php");
        exit();
    }
    $conn->close();
}
?>
