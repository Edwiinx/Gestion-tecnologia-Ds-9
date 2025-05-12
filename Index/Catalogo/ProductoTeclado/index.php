<?php
session_start();


if (!isset($_SESSION['user'])) {
    header('Location: ../Inicio/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link rel="stylesheet" href="../../../Css/estiloProducto.css"> <!-- Enlace al CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.6/dist/sweetalert2.min.css">
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .btn-regresar {
    position: absolute;
    top: 20px;
    left: 20px;
    background-color: #f0f0f0;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    color: #333;
    z-index: 999;
}

.btn-regresar:hover {
    background-color: #b82eca;
}

.btn-carrito {
    position: fixed;
    bottom: 20px;
    right: 20px;
    font-size: 24px;
    background-color: #af10ee;
    color: white;
    padding: 15px 25px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: bold;
    box-shadow: 0 0 15px rgba(140, 79, 211, 0.5);
    z-index: 999;
    display: flex;
    align-items: center;
    gap: 10px;
}

.carrito-animado {
    position: relative;
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 5px 10px;
    font-weight: bold;
    animation: pulso 1.5s infinite;
}

@keyframes pulso {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(255, 0, 0, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 0, 0, 0);
    }
}

.btn-carrito:hover {
    background-color: #574fd6;
}


</style>
    
</head>
<body>
   
  
    <div class="producto-detalle">
        <h1 id="productoTitulo"></h1>
        <img id="productoImagen" alt="Imagen del producto" style="max-width: 100%; height: auto;">
        <div class="contenedorgrid">
        <p id="productoPrecio"></p>
        <p id="productoDescripcion"></p>
        <div class="contenedorEstado">
            <p id="productoEstado"></p>
            <div id="colorEstado"></div>
        </div>
        
        <div class="quantity-selector">
            <button onclick="decrease()">−</button>
            <input type="text" id="quantity" value="1" readonly>
            <button class="plus" onclick="increase()">+</button>
        </div>
        <button id="btnAgregarCarrito">Agregar al Carrito</button>
        </div>
        <a href="../../../Index/Catalogo/catalogo.php" class="btn-regresar">⟵ Catálogo</a>

    </div>

    <div id="modalCarrito" class="modal" style="display:none;">
        <div class="modal-content">
          <span class="close" onclick="cerrarModalCarrito()">&times;</span>
          <h2>Tu Carrito</h2>
          <div id="contenidoCarrito"></div>
        </div>
      </div>
      <a href="../../../Index/Carrito/Visa.php#carrito" class="btn-carrito">
    🛒 <span class="carrito-animado" id="contador-carrito">0</span>
</a>

<script>
let productoID = new URLSearchParams(window.location.search).get('id'); // ID en la URL
let estadoAnterior = null;

function verificarEstadoProducto() {
    if (!productoID) return;

    $.ajax({
        url: 'estadoProducto.php', // El archivo que verifica el estado del producto
        method: 'GET',
        data: { id: productoID },
        success: function (respuesta) {
            try {
                const data = JSON.parse(respuesta);
                const nuevoEstado = data.ESTADO;
                const cantidad = data.CANTIDAD;

                // Si el estado cambió
                if (estadoAnterior && nuevoEstado !== estadoAnterior) {
                    Swal.fire({
                        icon: 'info',
                        title: '¡Estado actualizado!',
                        text: `El producto ahora está: ${nuevoEstado}`,
                        timer: 3000,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false
                    });
                }

                estadoAnterior = nuevoEstado;

                // Actualizar el DOM
                $('#productoEstado').text(`Estado: ${nuevoEstado}`);
                let color = '#ccc';
                if (nuevoEstado === 'DISPONIBLE') color = 'green';
                else if (nuevoEstado === 'CASI AGOTADO') color = 'orange';
                else if (nuevoEstado === 'AGOTADO') color = 'red';
                $('#colorEstado').css('background', color).css('width', '20px').css('height', '20px').css('border-radius', '50%');

                // Desactivar botones según el estado
                if (nuevoEstado === 'DISPONIBLE') {
                    $(".quantity-selector button").prop("disabled", false);  // Habilitar botones de cantidad
                    $("#btnAgregarCarrito").prop("disabled", false);  // Habilitar agregar al carrito
                } else if (nuevoEstado === 'CASI AGOTADO') {
                    $(".quantity-selector button").prop("disabled", false);  // Habilitar aumentar cantidad
                    $("#btnAgregarCarrito").prop("disabled", false);  // Habilitar agregar al carrito
                    $(".quantity-selector button:first").prop("disabled", true);  // Deshabilitar disminuir cantidad
                } else if (nuevoEstado === 'AGOTADO') {
                    $(".quantity-selector button").prop("disabled", true);  // Deshabilitar ambos botones
                    $("#btnAgregarCarrito").prop("disabled", true);  // Deshabilitar agregar al carrito
                }

            } catch (error) {
                console.error('Error al interpretar el estado:', error);
            }
        }
    });
}

// Verificar estado cada 5 segundos
setInterval(verificarEstadoProducto, 5000);

// Ejecutar la verificación al cargar la página
$(document).ready(function () {
    verificarEstadoProducto();
});



    function actualizarContadorCarrito() {
     const idUsuario = localStorage.getItem('ID_USUARIO');
     if (!idUsuario) return;

     $.ajax({
         url: '../../../PhP/contarCarrito.php',
         method: 'POST',
         data: { id_usuario: idUsuario },
        success: function (respuesta) {
    console.log("Respuesta del servidor: ", respuesta); // Verifica lo que devuelve el servidor
    $('#contador-carrito').text(respuesta);
console.log("¿Existe el elemento?", $('#contador-carrito').length);

}

     });
 }

 $(document).ready(function () {
     // Forzar actualización del estado del producto después de agregar
    verificarEstadoProducto();
    actualizarContadorCarrito();
    
 });

</script>


<script>

    let idUsuario = localStorage.getItem('ID_USUARIO');

<?php

if (isset($_SESSION['ID_USUARIO'])) {
    echo "localStorage.setItem('ID_USUARIO', " . json_encode($_SESSION['ID_USUARIO']) . ");";
} else {
    echo "console.error('ID_USUARIO no está definido en la sesión.');";
}
?>

</script>

      <script src="../../../Js/Catalogo.js"></script>
</body>
</html>
