<?php
  
  
  // Conexion a la base de datos
$host = "localhost";
$username = "mario";
$password = "12345678";
$dbname = "noid_bd";
  
$conn = mysqli_connect($host, $username, $password, $dbname);
   
$host = "localhost";
$user = "root";
$password = "";
$db = "NOID_BD";


$est = mysqli_connect($host, $user, $password, $db);

if($est->connect_errno){
}else{
}


?>



