<?php
session_start();
header('Content-Type: application/json');


include("conexion.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar conexión
if (!$conn) {
    echo json_encode(['error' => 'Fallo en la conexión a la base de datos']);
    exit;
}

// Verificar parámetros
if (!isset($_SESSION['ID_USUARIO']) || !isset($_POST['id_carrito']) || !isset($_POST['delta'])) {
    echo json_encode(['error' => 'Datos incompletos']);
    exit;
}

$id_usuario = $_SESSION['ID_USUARIO'];
$id_carrito = intval($_POST['id_carrito']);
$delta = intval($_POST['delta']);

// Obtener cantidad actual
$stmt = $conn->prepare("SELECT CANTIDAD, ID_PRODUCTO FROM CARRITO WHERE ID_CARRITO = ? AND ID_USUARIO = ?");
$stmt->bind_param("ii", $id_carrito, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $cantidad_actual = $row['CANTIDAD'];
    $id_producto = $row['ID_PRODUCTO'];
    $nueva_cantidad = max(1, $cantidad_actual + $delta);

    // Obtener el precio unitario del producto
    $stmt_precio = $conn->prepare("SELECT PRECIO_UNITARIO FROM PRODUCTOS WHERE ID_PRODUCTO = ?");
    $stmt_precio->bind_param("i", $id_producto);
    $stmt_precio->execute();
    $precio_result = $stmt_precio->get_result();
    $precio_unitario = $precio_result->fetch_assoc()['PRECIO_UNITARIO'];

    // Calcular nuevo total
    $nuevo_total_precio = $nueva_cantidad * $precio_unitario;

    // Actualizar la base de datos con la nueva cantidad y el nuevo total
    $stmt_update = $conn->prepare("
        UPDATE CARRITO 
        SET CANTIDAD = ?, 
            TOTAL_PRECIO = ? 
        WHERE ID_CARRITO = ? AND ID_USUARIO = ?
    ");
    $stmt_update->bind_param("iiii", $nueva_cantidad, $nuevo_total_precio, $id_carrito, $id_usuario);
    $stmt_update->execute();

    echo json_encode(['success' => 'Cantidad actualizada correctamente']);
} else {
    echo json_encode(['error' => 'Producto no encontrado']);
}

$conn->close();
?>
