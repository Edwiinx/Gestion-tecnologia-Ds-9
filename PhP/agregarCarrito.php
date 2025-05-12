<?php 
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../Inicio/login.php');
    exit;
}

include("conexion.php");

// Recuperar ID del usuario de la sesión
$id_usuario = $_SESSION['ID_USUARIO'];

if (!$id_usuario) {
    echo "Error: Usuario no identificado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ID_PRODUCTO']) && isset($_POST['CANTIDAD'])) {
    $id_producto = $_POST['ID_PRODUCTO'];  
    $cantidad = (int)$_POST['CANTIDAD'];

    // Obtener el precio unitario del producto y la cantidad disponible
    $queryProducto = "SELECT PRECIO_UNITARIO, CANTIDAD FROM PRODUCTOS WHERE ID_PRODUCTO = ?";
    $stmt = $conn->prepare($queryProducto);
    if (!$stmt) {
        die("Error en la consulta: " . $conn->error);
    }
    $stmt->bind_param("s", $id_producto);  
    $stmt->execute();
    $resultado = $stmt->get_result();
    $row = $resultado->fetch_assoc();
    if (!$row) {
        die("Producto no encontrado.");
    }
    $precio_unitario = $row['PRECIO_UNITARIO'];
    $cantidad_disponible = $row['CANTIDAD'];

    // Verificar si hay suficiente stock
    if ($cantidad > $cantidad_disponible) {
        echo 'Cantidad excede el stock disponible.';
        exit;
    }

    // Calcular el total
    $total_precio = $precio_unitario * $cantidad;

    // Actualizar la cantidad del producto en la tabla de productos
    $nuevaCantidadDisponible = $cantidad_disponible - $cantidad;
    $updateProducto = "UPDATE PRODUCTOS SET CANTIDAD = ? WHERE ID_PRODUCTO = ?";
    $stmt = $conn->prepare($updateProducto);
    if (!$stmt) {
        die("Error en la consulta: " . $conn->error);
    }
    $stmt->bind_param("is", $nuevaCantidadDisponible, $id_producto);
    $stmt->execute();

    // Actualizar el estado del producto en la tabla de productos
    $nuevoEstado = 'DISPONIBLE';
    if ($nuevaCantidadDisponible == 0) {
        $nuevoEstado = 'AGOTADO';
    } elseif ($nuevaCantidadDisponible <= 5) {
        $nuevoEstado = 'CASI AGOTADO';
    }

    $updateEstado = "UPDATE PRODUCTOS SET ESTADO = ? WHERE ID_PRODUCTO = ?";
    $stmt = $conn->prepare($updateEstado);
    if (!$stmt) {
        die("Error en la consulta: " . $conn->error);
    }
    $stmt->bind_param("ss", $nuevoEstado, $id_producto);
    $stmt->execute();

    // Verificar si ya existe el producto en el carrito para este usuario
    $query = "SELECT * FROM CARRITO WHERE ID_USUARIO = ? AND ID_PRODUCTO = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Error en la consulta: " . $conn->error);
    }
    $stmt->bind_param("is", $id_usuario, $id_producto);  
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

    // Contar los productos en el carrito del usuario
    $countQuery = "SELECT SUM(CANTIDAD) as total FROM CARRITO WHERE ID_USUARIO = ?";
    $stmt = $conn->prepare($countQuery);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $total = $result->fetch_assoc()['total'] ?? 0;

    // Devolver el total de productos en el carrito
    echo $total;

} else {
    echo 'No se recibieron datos correctamente.';
}
?>
