<?php 

include('../../PhP/conexion.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../Css/Designcaptura.css">
</head>
<body>
    <form method="post"  >
        <div class="main-container">
            <div class="design-container">
                <h3>Bienvenido</h3>
                <img class="computerimage" src="../../Assets/manpc.png">
            </div>
            <div class="form-container">
                <h3>Registrar productos</h3>
                <label class="fillblanks">Llenar Datos</label>
                <div class="subform-container">
                    <div class="sub1">
                        <div class="subform">
                            <label class="subform-label">Nombre</label>
                            <input class="subform-input" type="text" placeholder="Ingrese el nombre">
                        </div>
                        
                        <div class="subform">
                            <label class="subform-label">Precio</label>
                            <input class="subform-input" type="text" placeholder="Ingrese el Precio">
                        </div>

                        <div class="subform">
                            <label class="subform-label">Imagen</label>
                            <input type="file"  class="subform-input" accept="image/png" accept="image/jpg" accept="image/jpeg" accept="image/svg" placeholder="Agregar Imagen">
                        </div>
                        
                    </div>
                    <div class="sub2">

                        <div class="subform"> 
                            <label class="subform-label">Numero de Serie</label>
                            <input class="subform-input" type="text" placeholder="Numero de Serie">
                        </div>
                    
                        <div class="subform">
                            <label class="subform-label">Modelo</label>
                            <input class="subform-input" type="text" placeholder="Ingrese el Modelo">
                        </div>

                    
                    </div>
                </div>
                <button class="savebtn">Guardar</button>
            </div>
        </div>
    </form>

</body>
</html>