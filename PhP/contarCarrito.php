<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;

    if ($id_usuario > 0) {
        $stmt = $conn->prepare("SELECT SUM(CANTIDAD) AS total FROM carrito WHERE ID_USUARIO = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        echo $total !== null ? $total : 0; // Solo devuelve el total
    } else {
        echo 0; // Devuelve 0 si no hay ID de usuario válido
    }
}
?>
