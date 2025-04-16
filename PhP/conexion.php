<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "NOID_BD";


$est = mysqli_connect($host, $user, $password, $db);

if($est->connect_errno){
    echo'holaaaa';
}else{
}




?>