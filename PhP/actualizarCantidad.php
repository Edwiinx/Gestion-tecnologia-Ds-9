<?php
session_start();

if (!isset($_POST['id']) || !isset($_POST['delta'])) {
    exit;
}

$id = intval($_POST['id']);
$delta = intval($_POST['delta']);

foreach ($_SESSION['carrito'] as &$item) {
    if ($item['ID_PRODUCTO'] == $id) {
        $item['CANTIDAD'] += $delta;
        if ($item['CANTIDAD'] < 1) {
            $item['CANTIDAD'] = 1;
        }
        break;
    }
}
?>
