
<?php
include('../../PhP/conexion.php');


$username = $_POST['user'];
$password = $_POST['password'];
$tipo = $_POST['type'];

    $sql_query = "SELECT * FROM USUARIO";
    $sql_queryselect = mysqli_query($est, $sql_query);
    $existe = false;

    while($row = mysqli_fetch_assoc($sql_queryselect)){
        if($username === $row['NOMBRE_USUARIO']){
            $existe = true;
            break;
        }
    }

    if (!$existe) {
        $sql_queryinsert = "INSERT INTO USUARIO (NOMBRE_USUARIO, CONTRASENA, TIPO) VALUES ('$username','$password', '$tipo')";
        if(mysqli_query($est, $sql_queryinsert)) {
            header('Content-Type: application/json');
            echo json_encode(["mensaje_mostrar" => "exito"]);
        } else {
            echo "Error al insertar usuario: " . mysqli_error($est);
        }
    } else {
       header('Content-Type: application/json');
       echo json_encode(["status"=>"error"]);
    }
    










?>