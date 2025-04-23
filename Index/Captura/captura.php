<?php 

session_start();


if(!isset($_SESSION['user'])){
    header('Location: ../Inicio/login.php');
    exit;
}else{

}
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
                                        echo "<option value='$id_categoria' selected>$nombre_categoria</option>";
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
  
  let btnsave = document.getElementById('savebtn');

    btnsave.addEventListener('click', ()=>{

    let nombre = document.querySelector('.nombre').value;
    let precio = document.querySelector('.precio').value;
    let imagen = document.querySelector('.imagen');
    let imagendata = imagen.files[0];
    let imaged2 = imagendata.name;
    let numbserie = document.querySelector('.numbserie').value;
    let descripcion = document.querySelector('.descripcion').value;
    let categoria = document.querySelector('.categoria').value;
    let modelo = document.querySelector('.modelo').value;
    let nombrevoid  = document.querySelector('.nombre');
    let preciovoid = document.querySelector('.precio');
    let numbserievoid = document.querySelector('.numbserie');
    let descripcionvoid = document.querySelector('.descripcion');
    let categoriavoid = document.querySelector('.categoria');
    let modelovoid = document.querySelector('.modelo');
    console.log(categoria);             
    let parametros ={
        'nombre':nombre, 
        'precio':precio,
        'imagen':imaged2,
        'numbserie':numbserie,
        'descripcion':descripcion,
        'categoria':categoria,
        'modelo':modelo
    }
    $.ajax({
    data: parametros,
    url: 'insertar.php',
    type: 'post',
    dataType: 'json', 
    success: function(respuesta) {
        if (respuesta.status == 'exito') {
            Swal.fire({
                title: "Bien Hecho",
                text: "Todo se envío correctamente",
                icon: "success"
            });

            nombrevoid.value='';
            preciovoid.value='';
            numbserievoid.value='';
            descripcionvoid.value='';
            categoriavoid.value='';
            modelovoid.value='';
          

        } else if (respuesta.status == 'error') {
            Swal.fire({
                title: "Error",
                text: respuesta.mensaje_mostrar,
                icon: "error"
            });
        }
    },
    error: function(xhr, status, error) {
            Swal.fire({
                title: "Error grave",
                text: `Error: ${xhr.responseText || error}`,
                icon: "error"
            });
            console.error("Detalles del error:", {
                status: status,
                error: error,
                response: xhr.responseText
            });
        }
    });
    });
   
                                


</script>
</html>