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

    $sql = "
        SELECT id_cliente, email 
        FROM clientes 
        WHERE email = ? 
        UNION 
        SELECT id_empleado, email 
        FROM empleados 
        WHERE email = ?;
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $verification_code = bin2hex(random_bytes(4));
        $timestamp = date("Y-m-d H:i:s");

        $user = $result->fetch_assoc();
        $user_id = $user['id_cliente'] ?? $user['id_empleado'];
        
        if (isset($user['id_cliente'])) {
            $sql = "UPDATE clientes SET codigo_inicio = ?, timestamp = ? WHERE id_cliente = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $verification_code, $timestamp, $user_id);
            $stmt->execute();
        } else {
            $sql = "UPDATE empleados SET codigo_inicio = ?, timestamp = ? WHERE id_empleado = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $verification_code, $timestamp, $user_id);
            $stmt->execute();
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
            $mail->Body = "Tu código para iniciar sesión es: $verification_code";

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
    } else {
        $_SESSION['status_message'] = "No se encontró ningún usuario con ese correo electrónico.";
        $_SESSION['status_type'] = "error";
        header("Location: login.php");
        exit();
    }

    $conn->close();
}
?>