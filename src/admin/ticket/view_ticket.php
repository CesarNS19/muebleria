<?php
session_start();
require '../../../mysql/connection.php';
require '../../../vendor/fpdf/fpdf.php';

$conn->set_charset("utf8");

if (!isset($_GET['id_venta']) || empty($_GET['id_venta'])) {
    die("ID de venta no válido.");
}

$id_venta = intval($_GET['id_venta']);

function utf8_to_iso8859($text) {
    return iconv("UTF-8", "ISO-8859-1//TRANSLIT", $text);
}

$sql_venta = "SELECT v.fecha, v.hora, v.id_venta, SUM(d.subtotal) AS total
              FROM ventas v
              JOIN detalle_venta d ON v.id_venta = d.id_venta
              WHERE v.id_venta = ? 
              GROUP BY v.id_venta";
$stmt = $conn->prepare($sql_venta);
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$result_venta = $stmt->get_result();

if ($result_venta->num_rows === 0) {
    die("Venta no encontrada.");
}

$venta = $result_venta->fetch_assoc();

$sql_productos = "SELECT p.nombre, d.cantidad, p.precio, d.subtotal
                  FROM productos p
                  JOIN detalle_venta d ON p.id_producto = d.id_producto
                  WHERE d.id_venta = ?";
$stmt = $conn->prepare($sql_productos);
$stmt->bind_param("i", $id_venta);
$stmt->execute();
$result_productos = $stmt->get_result();

$pdf = new FPDF('P', 'mm', array(90, 150));
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, utf8_to_iso8859('MUEBLERÍA PARÍS'), 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 5, '', 0, 1, 'C');
$pdf->Cell(0, 5, utf8_to_iso8859('Sucursal: Centro'), 0, 1, 'C');
$pdf->Ln(2);
$pdf->Cell(0, 5, utf8_to_iso8859('Fecha: ') . date("d/m/Y", strtotime($venta['fecha'])), 0, 1, 'R');
$pdf->Cell(0, 5, utf8_to_iso8859('Hora: ') . date("h:i A", strtotime($venta['hora'])), 0, 1, 'R');
$pdf->Ln(2);
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(35, 5, utf8_to_iso8859('Producto'), 0, 0, 'L');
$pdf->Cell(15, 5, 'Cant', 0, 0, 'C');
$pdf->Cell(15, 5, 'Precio', 0, 0, 'L');
$pdf->Cell(15, 5, 'Subtotal', 0, 1, 'L');
$pdf->SetFont('Arial', '', 8);

foreach ($result_productos as $producto) {
    $pdf->Cell(35, 5, utf8_to_iso8859(substr($producto['nombre'], 0, 20)), 0, 0, 'L');
    $pdf->Cell(15, 5, $producto['cantidad'], 0, 0, 'C');
    $pdf->Cell(15, 5, '$' . number_format($producto['precio'], 2), 0, 0, 'L');
    $pdf->Cell(15, 5, '$' . number_format($producto['subtotal'], 2), 0, 1, 'L');
}

$pdf->Ln(2);
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 5, utf8_to_iso8859('TOTAL: $') . number_format($venta['total'], 2), 0, 1, 'R');
$pdf->Ln(5);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 5, utf8_to_iso8859('Gracias por su preferencia'), 0, 1, 'C');
$pdf->Cell(0, 5, utf8_to_iso8859('Vuelva pronto'), 0, 1, 'C');

$pdf->Output('I', 'Ticket_compra_' . $id_venta . '.pdf');
?>