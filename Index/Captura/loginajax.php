<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../../PhP/conexion.php');

$username = $_POST['user'];
$password = $_POST['password'];

// Debug temporal
// echo "<script>alert('Username: $username\\nPassword: $password');</script>";

$type = '';
$valido = false;

if ($est) {
    $sql_select = "SELECT * FROM USUARIO WHERE NOMBRE_USUARIO = '$username'";
    $sql_select_query = mysqli_query($est, $sql_select);

    if (mysqli_num_rows($sql_select_query) > 0) {
        $row = mysqli_fetch_assoc($sql_select_query);
        if ($password === $row['CONTRASENA']) {
            $type = $row['TIPO'];
            $valido = true;
            $_SESSION['user'] = $username;
            $_SESSION['password'] = $password;
            header('Content-Type: application/json');
            echo json_encode(["mensaje_mostrar" => "exito", "type" => $type, "username"=>$username, "password"=>$password]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(["status" => 'error', "mensaje" => "Usuario o contraseña incorrecta"]);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(["status" => 'error', "mensaje" => "El usuario no existe"]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(["status" => 'error', "mensaje" => "No hay conexión"]);
}
?>
