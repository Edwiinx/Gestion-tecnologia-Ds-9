<?php
session_start();
header('Content-Type: application/json');
include("conexion.php");

if (!isset($_SESSION['ID_USUARIO'])) {
    echo json_encode(['success' => false, 'message' => 'No autenticado']);
    exit;
}

$id_usuario = $_SESSION['ID_USUARIO'];
$id_compra = $_GET['id'] ?? null;

if (!$id_compra || !is_numeric($id_compra)) {
    echo json_encode(['success' => false, 'message' => 'ID de compra inválido.']);
    exit;
}

$sql = "SELECT * FROM RECIBO WHERE ID_USUARIO = ? AND ID_COMPRA = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id_usuario, $id_compra);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'id_compra' => $row['ID_COMPRA'],
        'total' => number_format($row['TOTAL_PRECIO'], 2),
        'fecha' => $row['FECHA_RECIBO'],
        'tarjeta' => '**** **** **** ' . substr($row['NUMERO_VISA'], -4)
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Recibo no encontrado.']);
}
?>
