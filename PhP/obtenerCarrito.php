<?php
// PhP/obtenerCarrito.php
include("conexion.php");
header('Content-Type: application/json');

$idUsuario = isset($_GET['ID_USUARIO']) ? (int)$_GET['ID_USUARIO'] : 0;
if ($idUsuario <= 0) {
    http_response_code(400);
    exit(json_encode([]));
}

$sql = "SELECT 
            c.CANTIDAD,
            c.TOTAL_PRECIO,
            p.NOMBRE_PRODUCTO,
            p.IMAGEN
        FROM carrito c
        JOIN productos p ON c.ID_PRODUCTO = p.ID_PRODUCTO
        WHERE c.ID_USUARIO = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
while ($row = $res->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode($items);
$stmt->close();
$conn->close();
