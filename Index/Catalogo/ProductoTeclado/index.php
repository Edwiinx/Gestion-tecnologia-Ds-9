

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto</title>
    <link rel="stylesheet" href="../../../Css/estiloProducto.css"> <!-- Enlace al CSS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        
    </div>
    <script>
    localStorage.setItem('ID_USUARIO', <?php echo json_encode($_SESSION['usuario']['ID_USUARIO']); ?>);
</script>

    <script src="../../../Js/Catalogo.js"></script>
</body>
</html>
