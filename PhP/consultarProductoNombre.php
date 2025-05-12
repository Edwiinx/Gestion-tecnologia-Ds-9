<?php
require_once 'conexion.php'; // o tu ruta a la conexión
$nombre = $_GET['nombre'] ?? '';

if ($nombre) {
    $stmt = $conn->prepare("SELECT * FROM producto WHERE NOMBRE_PRODUCTO = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        echo json_encode($resultado->fetch_assoc());
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode(['error' => 'Nombre no proporcionado']);
}
?>
