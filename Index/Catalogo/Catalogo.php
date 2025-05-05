<?php
include('../../PhP/conexion.php');
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../Inicio/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_producto'])) {
    $id_producto = $_POST['id_producto'];
    $total_precio = $_POST['total_precio'];
    $cantidad = 1;
    $id_usuario = $_SESSION['user']; // <- El usuario actual logueado

    // Primero, verificar si ya existe el producto en el carrito del usuario
    $query = "SELECT * FROM CARRITO WHERE ID_USUARIO = ? AND ID_PRODUCTO = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id_usuario, $id_producto);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Si ya existe, solo actualizar la cantidad y el precio total
        $row = $resultado->fetch_assoc();
        $nuevaCantidad = $row['CANTIDAD'] + 1;
        $nuevoTotal = $row['TOTAL_PRECIO'] + $total_precio;

        $update = "UPDATE CARRITO SET CANTIDAD = ?, TOTAL_PRECIO = ? WHERE ID_USUARIO = ? AND ID_PRODUCTO = ?";
        $stmt = $conn->prepare($update);
        $stmt->bind_param("idii", $nuevaCantidad, $nuevoTotal, $id_usuario, $id_producto);
        $stmt->execute();
    } else {
        // Si no existe, insertar nuevo registro
        $insert = "INSERT INTO CARRITO (ID_USUARIO, ID_PRODUCTO, CANTIDAD, TOTAL_PRECIO) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert);
        $stmt->bind_param("iiid", $id_usuario, $id_producto, $cantidad, $total_precio);
        $stmt->execute();
    }

    echo 'ok';
    exit;
}


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

<style>
.boton1 {
  display: inline-block;
  padding: 10px 20px;
  background-color:rgb(142, 59, 219);
  color: white !important; /* fuerza el color blanco */
  text-decoration: none;
  border-radius: 20px;
  border: none;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s ease;
  text-align: center;
}

.boton1:hover {
  background-color:rgb(199, 179, 214);
}
</style>


<body>

   <div class="container">
        <header>
            <h1>NOID</h1>
            <nav class="navegador">
                
            <ul>
                    <li>
                        <?php if (isset($_SESSION['user'])): ?>
                            <a href="../inicio/logout.php" class="boton1" >Cerrar sesión</a>
                        <?php else: ?>
                            <a href="../inicio/login.php" class="boton1" >Iniciar sesión</a>
                        <?php endif; ?>
                    </li>
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
                        <li><a href="#"><img src="../../assets/pc-caseicon.png" alt="iconoCaja" class="icono8"></a></li>
                        <span class="textoIcono8">Caja</span>
                    </div>
                </ul>
            </div>
       </div>

       <div class="menu">
             <p>Número de productos</p>
        </div>

        <div class="footer">
        <a href="/Gestion-tecnologia-Ds-9/Index/Carrito/Visa.php#carrito">
        <img src="../../assets/iconoCarrito.png" alt="iconoCarrito" id="carrito-icono" class="iconoCarrito">
        </a>
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
$(document).ready(function() {
    // Abrir modal y cargar carrito
    $('#carrito-icono').click(function () {
        $("#modalCarrito").fadeIn();
        actualizarCarrito();
    });

    // Cerrar modal
    $('.cerrar, .modal').click(function (e) {
        if (e.target === this || $(e.target).hasClass('cerrar')) {
            $("#modalCarrito").fadeOut();
        }
    });

    // Función para actualizar carrito en modal
    function actualizarCarrito() {
        $.get('../../PhP/obtenerCarrito.php', function (data) {
            $('#contenidoCarrito').html(data);
            activarBotones();
        });
    }

    // Funciones de aumentar, disminuir, eliminar en el modal
    function activarBotones() {
        $('.aumentar').click(function () {
            let id = $(this).data('id');
            modificarCantidad(id, 1);
        });

        $('.disminuir').click(function () {
            let id = $(this).data('id');
            modificarCantidad(id, -1);
        });

        $('.eliminar').click(function () {
            let id = $(this).data('id');
            eliminarProducto(id);
        });
    }

    function modificarCantidad(id, delta) {
        $.post('../../PhP/actualizarCantidad.php', { id: id, delta: delta }, function() {
            actualizarCarrito();
        });
    }

    function eliminarProducto(id) {
        $.post('../../PhP/eliminarProducto.php', { id: id }, function() {
            actualizarCarrito();
        });
    }

    // Agregar productos al carrito
    $('.agregar-carrito').click(function() {
        let id = $(this).data('id');
        $.post('../../PhP/agregarCarrito.php', { id: id }, function() {
            actualizarCarrito();
            alert('Producto agregado al carrito');
        });
    });
});
</script>

<script>localStorage.setItem('ID_USUARIO', <?php echo json_encode($_SESSION['user']); ?>);</script>
<script src="../../Js/Catalogo.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
