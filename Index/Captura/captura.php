<?php 

include('../../PhP/conexion.php');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../Css/Designcaptura.css?v=<?php echo time()?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

</head>
<body>
    
        <div class="main-container">
            <div class="design-container">
                <h3>Bienvenido</h3>
                <img class="computerimage" src="../../Assets/manpc.png">
            </div>
            <div class="form-container">
                <h3>Registrar productos</h3>
                <label class="fillblanks">Llenar Datos</label>
                <form method="POST" action='insertar.php' enctype="multipart/form-data">
                    <div class="subform-container">
                        <div class="sub1">
                            <div class="subform">
                                <label class="subform-label">Nombre</label>
                                <input class="subform-input nombre" type="text" name="nombre" placeholder="Ingrese el nombre">
                            </div>
                            
                            <div class="subform">
                                <label class="subform-label">Precio</label>
                                <input class="subform-input precio" type="text" name="precio" placeholder="Ingrese el Precio">
                            </div>

                            <div class="subform">
                                <label class="subform-label">Imagen</label>
                                <input type="file" class="subform-input imagen" name="imagen" accept="image/png, image/jpg, image/jpeg, image/svg+xml">
                            </div>

                            <div class="subform">
                                <label class="subform-label">Descripcion</label>
                                <textarea class="subform-input descripcion" name="descripcion" placeholder="Ingrese el Precio"></textarea>
                            </div>
                            
                        </div>
                        <div class="sub2">

                            <div class="subform"> 
                                <label class="subform-label">Numero de Serie</label>
                                <input class="subform-input numbserie" name="numbserie" type="text" placeholder="Numero de Serie">
                            </div>
                        
                            <div class="subform">
                                <label class="subform-label">Modelo</label>
                                <input class="subform-input modelo" name="modelo" type="text" placeholder="Ingrese el Modelo">
                            </div>

                            <div class="subform">
                                <label name="categoria" class="subform-label">Categoria</label>
                                <select class='subform-input categoria' >
                                <?php $sql = "SELECT * FROM CATEGORIA";
                                    $sql_query = mysqli_query($est, $sql);
                                    while($row=mysqli_fetch_assoc($sql_query)){
                                        $id_categoria=$row["ID_CATEGORIA"];
                                        $nombre_categoria=$row["NOMBRE_CATEGORIA"];
                                        echo "<option value='" . $id_categoria . "-01' selected>$nombre_categoria</option>";
                                    }
                                ?>
                                </select>
                            </div>


                        
                        </div>
                    </div>
                </form>

                <button id='savebtn' class="savebtn">Guardar</button>

            </div>
        </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  

    let nombre = document.querySelector('.nombre').value;
    let precio = document.querySelector('.precio').value;
    let imagen = document.querySelector('.imagen').value;
    let numbserie = document.querySelector('.numbserie').value;
    let descripcion = document.querySelector('.descripcion').value;
    let categoria = document.querySelector('.categoria').value;
    let modelo = document.querySelector('.modelo').value;
    let btnsave = document.getElementById('savebtn');
                            
    let parametros ={
        'nombre':nombre, 
        'precio':precio,
        'imagen':imagen,
        'numbserie':numbserie,
        'descripcion':descripcion,
        'categoria':categoria,
        'modelo':modelo
    }
    btnsave.addEventListener('click', ()=>{
        $.ajax({
        data:parametros,
        url:('insertar.php'),
        type:'post',

        success: function(mensaje_mostrar){
            Swal.fire({
            title: "Bien Hecho",
            text: "Todo se envio correctamente",
            icon: "success"
            });
			}
        });
    });
   
                                


</script>
</html>