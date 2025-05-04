<?php 
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../Inicio/login.php');
    exit;
}

include("conexion.php");

// Recuperar ID del usuario de la sesión
// Después de validar el usuario:
$id_usuario = $_SESSION['ID_USUARIO'];

if (!$id_usuario) {
    echo "Error: Usuario no identificado.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ID_PRODUCTO']) && isset($_POST['CANTIDAD'])) {
    $id_producto = $_POST['ID_PRODUCTO'];  // VARCHAR en BD, no cambiar tipo
    $cantidad = (int)$_POST['CANTIDAD'];

    // Obtener el precio unitario del producto
    $queryPrecio = "SELECT PRECIO_UNITARIO FROM PRODUCTOS WHERE ID_PRODUCTO = ?";
    $stmt = $conn->prepare($queryPrecio);
    if (!$stmt) {
        die("Error en la consulta: " . $conn->error);
    }
    $stmt->bind_param("s", $id_producto);  // 's' porque ID_PRODUCTO es VARCHAR
    $stmt->execute();
    $resultado = $stmt->get_result();
    $row = $resultado->fetch_assoc();
    if (!$row) {
        die("Producto no encontrado.");
    }
    $precio_unitario = $row['PRECIO_UNITARIO'];

    // Calcular el total
    $total_precio = $precio_unitario * $cantidad;

    // Verificar si ya existe el producto en el carrito para este usuario
    $query = "SELECT * FROM CARRITO WHERE ID_USUARIO = ? AND ID_PRODUCTO = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error en la consulta: " . $conn->error);
    }
    $stmt->bind_param("is", $id_usuario, $id_producto);  // i para ID_USUARIO (int), s para ID_PRODUCTO (varchar)
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Ya existe: actualizar cantidad y total
        $row = $resultado->fetch_assoc();
        $nuevaCantidad = $row['CANTIDAD'] + $cantidad;
        $nuevoTotal = $row['TOTAL_PRECIO'] + $total_precio;

        $update = "UPDATE CARRITO SET CANTIDAD = ?, TOTAL_PRECIO = ? WHERE ID_USUARIO = ? AND ID_PRODUCTO = ?";
        $stmt = $conn->prepare($update);
        if (!$stmt) {
            die("Error en la consulta: " . $conn->error);
        }
        $stmt->bind_param("idis", $nuevaCantidad, $nuevoTotal, $id_usuario, $id_producto);
        $stmt->execute();
    } else {
        // Insertar nuevo registro en el carrito
        $insert = "INSERT INTO CARRITO (ID_USUARIO, ID_PRODUCTO, CANTIDAD, TOTAL_PRECIO) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert);
        if (!$stmt) {
            die("Error en la consulta: " . $conn->error);
        }
        $stmt->bind_param("isis", $id_usuario, $id_producto, $cantidad, $total_precio);
        $stmt->execute();
    }

    echo 'ok';
} else {
    echo 'No se recibieron datos correctamente.';
}
?>
