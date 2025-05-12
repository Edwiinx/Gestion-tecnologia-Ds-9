<?php
include("conexion.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');
session_start();

// DEPURACIÓN
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Archivo de log
$logFile = __DIR__ . '/debug_log.txt';
function log_debug($mensaje) {
    global $logFile;
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] " . $mensaje . "\n", FILE_APPEND);
}

if (!isset($_SESSION['ID_USUARIO'])) {
    log_debug("Sesión no iniciada");
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit;
}

$id_usuario = $_SESSION['ID_USUARIO'];
$numero_visa = $_POST['numero_visa'] ?? '';
$fecha = $_POST['fecha'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$total = $_POST['total'] ?? 0;  // Recibimos el total directamente desde el frontend

log_debug("POST recibido: numero_visa=$numero_visa, fecha=$fecha, cvv=$cvv, total=$total");

// Validar fecha
$partes_fecha = explode('/', $fecha);
if (count($partes_fecha) != 2) {
    log_debug("Fecha inválida: $fecha");
    echo json_encode(['success' => false, 'message' => 'Formato de fecha inválido.']);
    exit;
}
$fecha_vencimiento = "20" . $partes_fecha[1] . "-" . $partes_fecha[0] . "-01";

// Validar si el total es mayor que 0
if ($total <= 0) {
    log_debug("Total <= 0. Carrito vacío o error de suma.");
    echo json_encode(['success' => false, 'message' => 'No hay productos para pagar.']);
    exit;
}

// Validar tarjeta
$query_visa = $conn->prepare("SELECT MONTO FROM VISA WHERE NUMERO_VISA = ? AND FECHA_VENCIMIENTO = ? AND VCC = ?");
$query_visa->bind_param("sss", $numero_visa, $fecha_vencimiento, $cvv);
$query_visa->execute();
$result = $query_visa->get_result();

if ($result->num_rows === 0) {
    log_debug("Tarjeta no válida: $numero_visa");
    echo json_encode(['success' => false, 'message' => 'Tarjeta no válida']);
    exit;
}

$datos_visa = $result->fetch_assoc();
$saldo_disponible = floatval($datos_visa['MONTO']);

log_debug("Saldo disponible: $saldo_disponible");

if ($saldo_disponible < $total) {
    log_debug("Fondos insuficientes. Disponible: $saldo_disponible, Requerido: $total");
    echo json_encode(['success' => false, 'message' => 'Fondos insuficientes']);
    exit;
}

$conn->begin_transaction();

try {
    // 1. Actualizar saldo
    $nuevo_monto = $saldo_disponible - $total;
    $update_visa = $conn->prepare("UPDATE VISA SET MONTO = ? WHERE NUMERO_VISA = ?");
    $update_visa->bind_param("ds", $nuevo_monto, $numero_visa);
    $update_visa->execute();

    // 2. Insertar compra
    $fecha_actual = date('Y-m-d H:i:s');
    $insert_compra = $conn->prepare("INSERT INTO COMPRA (ID_USUARIO, FECHA_COMPRA, TOTAL_COMPRA) VALUES (?, ?, ?)");
    $insert_compra->bind_param("isd", $id_usuario, $fecha_actual, $total);
    $insert_compra->execute();
    $id_compra = $conn->insert_id;

    // 3. Insertar recibo
    $insert_recibo = $conn->prepare("INSERT INTO RECIBO (ID_USUARIO, ID_COMPRA, TOTAL_PRECIO, FECHA_RECIBO, NUMERO_VISA) VALUES (?, ?, ?, ?, ?)");
    $insert_recibo->bind_param("iisss", $id_usuario, $id_compra, $total, $fecha_actual, $numero_visa);
    $insert_recibo->execute();

    // 4. Vaciar carrito
    $vaciar = $conn->prepare("DELETE FROM CARRITO WHERE ID_USUARIO = ?");
    $vaciar->bind_param("i", $id_usuario);
    $vaciar->execute();

    $conn->commit();

    log_debug("Compra completada con éxito. ID compra: $id_compra");

    echo json_encode(['success' => true, 'mensaje' => 'Pago realizado con éxito', 'id_compra' => $id_compra]);
} catch (Exception $e) {
    $conn->rollback();
    $mensajeError = $e->getMessage();
    log_debug("Excepción: " . $mensajeError);
    echo json_encode(['success' => false, 'message' => 'Error al procesar el pago: ' . $mensajeError]);
}
?>
