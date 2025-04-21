<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include('../../PhP/conexion.php');


$nombre = $_POST["nombre"];
$precio = $_POST['precio'];
$imagen = $_POST['imagen'];
$numbserie = $_POST['numbserie'];
$descripcion = $_POST['descripcion'];
$categoria = $_POST['categoria'];
$modelo = $_POST['modelo'];
$id_product ='';

    $sql_select = "SELECT * FROM PRODUCTOS";

    $sql_queryselect = mysqli_query($est, $sql_select);
    while($row=mysqli_fetch_assoc($sql_queryselect)){
        $id_product = $row['ID_PRODUCTO'];
    }
    

    $sql_insert = "INSERT INTO PRODUCTOS (
    ID_CATEGORIA, NOMBRE_PRODUCTO, MODELO, PRECIO_UNITARIO, FECHA_DE_CAPTURA,
    CANTIDAD, ESTADO, DESCRIPCION, IMAGEN, NUMERO_DE_SERIE
) VALUES
('$categoria', '$nombre', '$modelo', '$precio', NOW(), 100, 'DISPONIBLE', '$descripcion', '$imagen', '$numbserie')";


   

    if (mysqli_query($est, $sql_insert)) {
        
        echo json_encode([
        'status' => 'exito',
        'mensaje_mostrar' => 'bueno',
                ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'mensaje_mostrar' => 'no se envio capullo',
                    ]);
    }


















?>