<?php
session_start();

if (!isset($_POST['id'])) {
    exit;
}

$id = intval($_POST['id']);

foreach ($_SESSION['carrito'] as $index => $item) {
    if ($item['ID_PRODUCTO'] == $id) {
        unset($_SESSION['carrito'][$index]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
        break;
    }
}
?>
