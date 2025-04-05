
<?php
include('../../PhP/conexion.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculos</title>
    <link rel="stylesheet" href="../../Css/estilo1.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

   <div class="container">
        <header>
            <h1>Tienda tecnologica</h1>
            <nav class="navegador">
                
                <ul>
                    <button class="boton1" id="boton">iniciar sesión</button>
                </ul>
                
            </nav>
        </header>
   </div>


   <div class="display">
       <div class="titulo">
            <h2>Articulos Tecnologicos</h2>
       </div>

       <div class="productos">
            
            <div class="contenedoriconos">
                <ul>
                    <li><a href="#"><img src="../../assets/iconoCelular.png" alt="iconoCelular" class="icono1"></a></li>
                    <li><a href="#"><img src="../../assets/ordenador-portatil.png" alt="iconoLaptop" class="icono2"></a></li>
                    <li><a href="#"><img src="../../assets/ver-la-television.png" alt="iconoTelevision" class="icono3"></a></li>
                </ul>
            </div>
            
       </div>

        <div class="filtrar">
            <div class="precio">
                <label> Precio </label>
                
            </div>
           

        </div>


        <div class="menu">

             <p>Numero de productos</p> <!--Contenedor de numero de productos con JS-->
            
            
        </div>

   </div>

   <script src="../../Js/carrito.js"></script>


</body>
</html>


