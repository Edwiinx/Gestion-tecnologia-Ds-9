$(document).ready(function () {
    let todosLosProductos = [];
    let productoSeleccionado = null;


    $.ajax({
        url: '../../PhP/consultacarrito.php',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            todosLosProductos = data;
            mostrarProductos(todosLosProductos);
        }
    });

    function mostrarProductos(productosFiltrados) {
        $('.menu').empty();

        if (productosFiltrados.length === 0) {
            $('.menu').append('<p>No hay productos de este tipo.</p>');
            return;
        }

        productosFiltrados.forEach(function (producto) {
            let contenedor = $('<div class="producto"></div>');

            contenedor.append('<img class="imagen" src="../../assets/' + producto.IMAGEN + '" alt="Imagen del producto">');
            contenedor.append('<h3 class="tituloP">' + producto.NOMBRE_PRODUCTO + '/' + producto.MODELO + '</h3>');
            contenedor.append('<p class="precioP">$' + producto.PRECIO_UNITARIO + '</p>');

            contenedor.click(function () {
                const categoria = producto.ID_CATEGORIA.toUpperCase();
                const nombreFormateado = encodeURIComponent(producto.NOMBRE_PRODUCTO);
                let ruta = '';

                switch (categoria) {
                    case 'PC': ruta = `../../Index/Carrito/ProductoPc/index.html?nombre=${nombreFormateado}`; break;
                    case 'LAP': ruta = `../../Index/Carrito/ProductoLap/index.html?nombre=${nombreFormateado}`; break;
                    case 'MOU': ruta = `../../Index/Carrito/ProductoMouse/index.html?nombre=${nombreFormateado}`; break;
                    case 'MON': ruta = `../../Index/Carrito/ProductoMonitor/index.html?nombre=${nombreFormateado}`; break;
                    case 'KEY': ruta = `../../Index/Carrito/ProductoTeclado/index.html?nombre=${nombreFormateado}`; break;
                    case 'SPE': ruta = `../../Index/Carrito/ProductoParlante/index.html?nombre=${nombreFormateado}`; break;
                    case 'COM': ruta = `../../Index/Carrito/ProductoComponente/index.html?nombre=${nombreFormateado}`; break;
                    case 'CAS': ruta = `../../Index/Carrito/ProductoCase/index.html?nombre=${nombreFormateado}`; break;
                }

                localStorage.setItem('productoDetalle', JSON.stringify(producto));
                window.location.href = ruta;
            });

            $('.menu').append(contenedor);
        });
    }

  
    $(document).on('click', '.iconoCarrito', function () {
        $.ajax({
            url: '../../PhP/obtenerCarrito.php',
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                let html = '';
                if (data.length === 0) {
                    html = '<p>Tu carrito está vacío.</p>';
                } else {
                    data.forEach(item => {
                        html += `
                            <div class="item-carrito">
                                <h4>${item.NOMBRE_PRODUCTO}</h4>
                                <p>Cantidad: ${item.CANTIDAD}</p>
                                <p>Total: $${parseFloat(item.TOTAL_PRECIO).toFixed(2)}</p>
                            </div>
                        `;
                    });
                }

                $('#contenidoCarrito').html(html);
                $('#modalCarrito').fadeIn();  
            }
        });
    });

    // Cerrar modal
    $('#cerrarModalCarrito').click(function () {
        $('#modalCarrito').fadeOut();  
    });

    
    $(window).click(function (event) {
        if ($(event.target).is('#modalCarrito')) {
            $('#modalCarrito').fadeOut();
        }
    });

    // Filtros por íconos
    $('.icono1').click(() => mostrarProductos(todosLosProductos.filter(p => p.ID_CATEGORIA.toUpperCase() === 'PC')));
    $('.icono2').click(() => mostrarProductos(todosLosProductos.filter(p => p.ID_CATEGORIA.toUpperCase() === 'LAP')));
    $('.icono3').click(() => mostrarProductos(todosLosProductos.filter(p => p.ID_CATEGORIA.toUpperCase() === 'MOU')));
    $('.icono4').click(() => mostrarProductos(todosLosProductos.filter(p => p.ID_CATEGORIA.toUpperCase() === 'MON')));
    $('.icono5').click(() => mostrarProductos(todosLosProductos.filter(p => p.ID_CATEGORIA.toUpperCase() === 'KEY')));
    $('.icono6').click(() => mostrarProductos(todosLosProductos.filter(p => p.ID_CATEGORIA.toUpperCase() === 'SPE')));
    $('.icono7').click(() => mostrarProductos(todosLosProductos.filter(p => p.ID_CATEGORIA.toUpperCase() === 'COM')));
    $('.icono8').click(() => mostrarProductos(todosLosProductos.filter(p => p.ID_CATEGORIA.toUpperCase() === 'CAS')));

    // Página del detalle del producto
    const producto = JSON.parse(localStorage.getItem('productoDetalle'));
    if (producto) {
        $('#productoTitulo').text(producto.NOMBRE_PRODUCTO + " / " + producto.MODELO);
        $('#productoImagen').attr('src', '../../../assets/' + producto.IMAGEN);
        $('#productoPrecio').text('Precio: $' + producto.PRECIO_UNITARIO);
        $('#productoEstado').text(producto.ESTADO);
        $('#productoDescripcion').text('Descripción: ' + (producto.DESCRIPCION || 'Sin descripción disponible'));

        let estado = document.getElementById("productoEstado");
        let color = document.getElementById("colorEstado");
        let botonCarrito = document.getElementById("btnAgregarCarrito");

        if (estado.textContent === "DISPONIBLE") {
            color.style.backgroundColor = "green";
        } else if (estado.textContent === "AGOTADO") {
            color.style.backgroundColor = "red";
            botonCarrito.disabled = true;
        } else if (estado.textContent === "CASI AGOTADO") {
            color.style.backgroundColor = "orange";
        }

        // Agregar al carrito
        $('#btnAgregarCarrito').click(function () {
            const idUsuario = localStorage.getItem('ID_USUARIO');
            console.log("ID_USUARIO enviado:", idUsuario);  // Verificar el valor en consola

            if (!idUsuario) {
                alert("Por favor, inicie sesión primero.");
                return;
            }

            const cantidad = parseInt($('#quantity').val()) || 1;
            const precio = parseFloat(producto.PRECIO_UNITARIO);
            const total = (cantidad * precio).toFixed(2);

            $.ajax({
                url: '../../../PhP/agregarAlCarrito.php',
                method: 'POST',
                data: {
                    ID_USUARIO: idUsuario,
                    ID_PRODUCTO: producto.ID_PRODUCTO,
                    CANTIDAD: cantidad,
                    TOTAL_PRECIO: total
                },
                success: function (response) {
                    alert('Producto agregado al carrito.');
                    console.log(response);
                },
                error: function (xhr, status, error) {
                    alert('Error al agregar al carrito.');
                    console.error(error);
                }
            });
        });
    }

    // Funciones para botones + y -
    window.decrease = function () {
        let value = parseInt($('#quantity').val());
        if (value > 1) $('#quantity').val(value - 1);
    }

    window.increase = function () {
        let value = parseInt($('#quantity').val());
        $('#quantity').val(value + 1);
    }


    window.volverP = function () {
        window.location.href = "../carrito.php";
    }
});
