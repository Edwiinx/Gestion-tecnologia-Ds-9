<?php

include("conexion.php");



$sql= "SELECT ID_PRODUCTO, ID_CATEGORIA, NOMBRE_PRODUCTO, MODELO,PRECIO_UNITARIO,FECHA_DE_CAPTURA, CANTIDAD,ESTADO,DESCRIPCION,IMAGEN ,NUMERO_DE_SERIE FROM PRODUCTOS";
$result = $conn->query($sql);


$productos_info = [];


while ($row = $result->fetch_assoc()) {
    
    $productos_info[] = [
        "ID_PRODUCTO" => $row['ID_PRODUCTO'],
        "ID_CATEGORIA" => $row['ID_CATEGORIA'],
        "NOMBRE_PRODUCTO" => $row['NOMBRE_PRODUCTO'],
        "MODELO" => $row['MODELO'],
        "PRECIO_UNITARIO" => $row['PRECIO_UNITARIO'],
        "FECHA_DE_CAPTURA" => $row['FECHA_DE_CAPTURA'],
        "CANTIDAD" => $row['CANTIDAD'],
        "ESTADO" => $row['ESTADO'],
        "DESCRIPCION" => $row['DESCRIPCION'],
        "IMAGEN" => $row['IMAGEN'],
        "NUMERO_DE_SERIE" => $row['NUMERO_DE_SERIE']

    ];
}

$conn->close();




echo json_encode($productos_info);
?>

