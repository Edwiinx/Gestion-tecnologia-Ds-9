<?php

include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit("Método no permitido");
}


$idUsuario   = isset($_POST['ID_USUARIO'])   ? (int)$_POST['ID_USUARIO']     : 0;
$idProducto  = isset($_POST['ID_PRODUCTO'])  ? $_POST['ID_PRODUCTO']         : '';
$cantidad    = isset($_POST['CANTIDAD'])     ? (int)$_POST['CANTIDAD']       : 1;


$stmtU = $conn->prepare("SELECT 1 FROM usuario WHERE ID_USUARIO = ?");
$stmtU->bind_param("i", $idUsuario);
$stmtU->execute();
if ($stmtU->get_result()->num_rows === 0) {
    http_response_code(400);
    exit("Error: usuario inválido");
}
$stmtU->close();


$stmtP = $conn->prepare("SELECT PRECIO_UNITARIO FROM productos WHERE ID_PRODUCTO = ?");
$stmtP->bind_param("s", $idProducto);
$stmtP->execute();
$resP = $stmtP->get_result();
if ($resP->num_rows === 0) {
    http_response_code(400);
    exit("Error: producto inválido");
}
$precioUnitario = $resP->fetch_assoc()['PRECIO_UNITARIO'];
$stmtP->close();

$stmtC = $conn->prepare("
    SELECT CANTIDAD 
      FROM carrito 
     WHERE ID_USUARIO = ? 
       AND ID_PRODUCTO = ?
");
$stmtC->bind_param("is", $idUsuario, $idProducto);
$stmtC->execute();
$resC = $stmtC->get_result();

if ($resC->num_rows > 0) {

    $fila      = $resC->fetch_assoc();
    $nuevaCant = $fila['CANTIDAD'] + $cantidad;
    $nuevoTot  = $nuevaCant * $precioUnitario;

    $stmtU = $conn->prepare("
        UPDATE carrito 
           SET CANTIDAD = ?, TOTAL_PRECIO = ? 
         WHERE ID_USUARIO = ? 
           AND ID_PRODUCTO = ?
    ");
    $stmtU->bind_param("idis", $nuevaCant, $nuevoTot, $idUsuario, $idProducto);
    if (! $stmtU->execute()) {
        http_response_code(500);
        exit("Error al actualizar carrito: " . $stmtU->error);
    }
    $stmtU->close();
    echo "Carrito actualizado: nueva cantidad $nuevaCant";
} else {
  
    $totalPrecio = $cantidad * $precioUnitario;
    $stmtI = $conn->prepare("
        INSERT INTO carrito (ID_USUARIO, ID_PRODUCTO, CANTIDAD, TOTAL_PRECIO)
        VALUES (?, ?, ?, ?)
    ");
    $stmtI->bind_param("isid", $idUsuario, $idProducto, $cantidad, $totalPrecio);
    if (! $stmtI->execute()) {
        http_response_code(500);
        exit("Error al agregar al carrito: " . $stmtI->error);
    }
    $stmtI->close();
    echo "Producto agregado al carrito.";
}

$stmtC->close();
$conn->close();
?>
