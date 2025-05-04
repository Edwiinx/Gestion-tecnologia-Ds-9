<?php
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// Recibir datos
$action = $_POST['action'];
$id_producto = $_POST['id_producto'];

if ($action == 'cambiarCantidad') {
    $nuevaCantidad = intval($_POST['cantidad']);

    foreach ($_SESSION['carrito'] as &$item) {
        if ($item['ID_PRODUCTO'] == $id_producto) {
            $item['CANTIDAD'] = $nuevaCantidad;
            $item['TOTAL_PRECIO'] = $item['PRECIO_UNITARIO'] * $nuevaCantidad;
            break;
        }
    }
}

if ($action == 'eliminar') {
    foreach ($_SESSION['carrito'] as $index => $item) {
        if ($item['ID_PRODUCTO'] == $id_producto) {
            unset($_SESSION['carrito'][$index]);
            $_SESSION['carrito'] = array_values($_SESSION['carrito']); // Reindexar
            break;
        }
    }
}

echo json_encode(['success' => true]);
?>
