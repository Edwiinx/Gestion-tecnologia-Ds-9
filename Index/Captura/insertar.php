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
    if($categoria==$id_product){
        list($prefix, $number) = explode("-", $categoria);
        $number = str_pad((int)$number + 1, 2, "0", STR_PAD_LEFT);
        $categoria = $prefix . "-" . $number;
    }

    $sql_insert = "INSERT INTO PRODUCTOS (ID_PRODUCTO, NOMBRE_PRODUCTO, MODELO, PRECIO_UNITARIO, DESCRIPCION, IMAGEN, NUMERO_DE_SERIE) VALUES ('$categoria', '$nombre','$modelo','$precio','$descripcion', '$imagen','$numbserie')";

    if (mysqli_query($est, $sql_insert)) {
        echo "Producto insertado correctamente.";
    } else {
        echo "Error al insertar producto: " . mysqli_error($est);
    }


















?>