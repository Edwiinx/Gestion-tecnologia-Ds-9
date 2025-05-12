<?php
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_carrito = $_POST['id_producto'] ?? null;
    $nuevaCantidad = $_POST['nuevaCantidad'] ?? null;
    $nuevoPrecioTotal = $_POST['nuevoPrecioTotal'] ?? null;

    if (!$id_carrito || !$nuevaCantidad || !$nuevoPrecioTotal) {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
        exit;
    }

    $sql = "UPDATE CARRITO SET CANTIDAD = ?, TOTAL_PRECIO = ? WHERE ID_CARRITO = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("idi", $nuevaCantidad, $nuevoPrecioTotal, $id_carrito);
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'new_quantity' => $nuevaCantidad,
                'new_total' => $nuevoPrecioTotal
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
