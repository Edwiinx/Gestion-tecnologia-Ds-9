<?php
session_start();
include("conexion.php");

header('Content-Type: application/json');

// Leer el cuerpo JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['ID_USUARIO']) || !isset($data['id'])) {
    echo json_encode(['success' => false, 'error' => 'Parámetros faltantes']);
    exit;
}

$id_usuario = $_SESSION['ID_USUARIO'];
$id_carrito = intval($data['id']);

$stmt = $conn->prepare("DELETE FROM CARRITO WHERE ID_CARRITO = ? AND ID_USUARIO = ?");
$stmt->bind_param("ii", $id_carrito, $id_usuario);
$success = $stmt->execute();

$stmt->close();
$conn->close();

echo json_encode(['success' => $success]);
?>
