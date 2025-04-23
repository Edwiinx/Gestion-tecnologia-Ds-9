<?php
include('../../PhP/conexion.php');
?>

<!DOCTYPE html>
<html lang="es">
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
            <h1>Tienda tecnológica</h1>
            <nav class="navegador">
                <ul>
                    <button class="boton1" id="boton">Iniciar sesión</button>
                </ul>
            </nav>
        </header>
   </div>

   <div class="display">
       <div class="titulo">
            <h2>Artículos Tecnológicos</h2>
       </div>

       <div class="productos">
            <div class="contenedoriconos">
                <ul>
                    <div class="contenedorIcono1">
                        <li><a href="#"><img src="../../assets/iconoPC.png" alt="iconoPc" class="icono1"></a></li>
                        <span class="textoIcono1">PC</span>
                    </div>
                    <div class="contenedorIcono2">
                        <li><a href="#"><img src="../../assets/iconoLaptop.png" alt="iconoLaptop" class="icono2"></a></li>
                        <span class="textoIcono2">Laptop</span>
                    </div>
                    <div class="contenedorIcono3">
                        <li><a href="#"><img src="../../assets/iconoMouse.png" alt="iconoMouse" class="icono3"></a></li>
                        <span class="textoIcono3">Mouse</span>
                    </div>
                    <div class="contenedorIcono4">
                        <li><a href="#"><img src="../../assets/iconoMonitor.png" alt="iconoMonitor" class="icono4"></a></li>
                        <span class="textoIcono4">Monitor</span>
                    </div>
                    <div class="contenedorIcono5">
                        <li><a href="#"><img src="../../assets/iconoTeclado.png" alt="iconoTeclado" class="icono5"></a></li>
                        <span class="textoIcono5">Teclado</span>
                    </div>
                    <div class="contenedorIcono6">
                        <li><a href="#"><img src="../../assets/iconoAltavoz.png" alt="iconoAltavoz" class="icono6"></a></li>
                        <span class="textoIcono6">Altavoz</span>
                    </div>
                    <div class="contenedorIcono7">
                        <li><a href="#"><img src="../../assets/iconoComponentes.png" alt="iconoComponentes" class="icono7"></a></li>
                        <span class="textoIcono7">Componente</span>
                    </div>
                    <div class="contenedorIcono8">
                        <li><a href="#"><img src="../../assets/caja_android.png" alt="iconoCaja" class="icono8"></a></li>
                        <span class="textoIcono8">Caja</span>
                    </div>
                </ul>
            </div>
       </div>

       <div class="menu">
             <p>Número de productos</p>
        </div>

        <div class="footer">
            <img src="../../assets/iconoCarrito.png" alt="iconoCarrito" id="carrito-icono" class="iconoCarrito">
        </div>
    </div>

    <!-- Modal para el carrito -->
    <div id="modalCarrito" class="modal">
        <div class="modal-contenido">
            <span class="cerrar" id="cerrarModalCarrito">&times;</span>
            <h2>Carrito de Compras</h2>
            <div id="contenidoCarrito">
                
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
    // Abrir modal y cargar carrito
    $('#carrito-icono').click(function () {
        $("#modalCarrito").fadeIn();

        // Cargar contenido dinámicamente
        $.get('../../PhP/obtenerCarrito.php', function (data) {
            $('#contenidoCarrito').html(data);
            activarBotones();
        });
    });

    // Cerrar modal
    $('.cerrar, .modal').click(function (e) {
        if (e.target === this || $(e.target).hasClass('cerrar')) {
            $("#modalCarrito").fadeOut();
        }
    });

    // Función para aumentar y disminuir cantidad
    function activarBotones() {
        $('.aumentar').click(function () {
            let contenedor = $(this).closest('.producto-carrito');
            let cantidadElem = contenedor.find('.cantidad');
            let totalElem = contenedor.find('.total');
            let precioUnitario = parseFloat(contenedor.data('precio'));

            let cantidad = parseInt(cantidadElem.text());
            cantidad++;
            cantidadElem.text(cantidad);
            totalElem.text('$' + (cantidad * precioUnitario).toFixed(2));
        });

        $('.disminuir').click(function () {
            let contenedor = $(this).closest('.producto-carrito');
            let cantidadElem = contenedor.find('.cantidad');
            let totalElem = contenedor.find('.total');
            let precioUnitario = parseFloat(contenedor.data('precio'));

            let cantidad = parseInt(cantidadElem.text());
            if (cantidad > 1) {
                cantidad--;
                cantidadElem.text(cantidad);
                totalElem.text('$' + (cantidad * precioUnitario).toFixed(2));
            }
        });
    }
});

    </script>

    <script src="../../Js/carrito.js"></script>
</body>
</html>
