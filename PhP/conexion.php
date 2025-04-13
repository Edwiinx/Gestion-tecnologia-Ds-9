<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "pruebads9";


$est = mysqli_connect($host, $user, $password, $db);

if($est->connect_errno){
    echo'holaaaa';
}else{
}




?>