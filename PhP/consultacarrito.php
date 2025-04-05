<?php

include("conexion.php");



$sql= "SELECT producto, marca, tipo FROM carrito";
$result = $conn->query($sql);


$productos = [];


while ($row = $result->fetch_assoc()) {
    
    $productos[] = [
        "producto" => $row['producto'],
        "marca" => $row['marca'],
        "tipo" => $row['tipo']
    ];
}

$conn->close();




echo json_encode($productos);
?>

