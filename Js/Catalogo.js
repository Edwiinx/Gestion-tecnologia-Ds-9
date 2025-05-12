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

    function actualizarProductosPeriodicamente() {
        setInterval(function () {
            $.ajax({
                url: '../../PhP/consultacarrito.php',
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    todosLosProductos = data;
                    mostrarProductos(todosLosProductos);
                }
            });
        }, 3000); // Actualiza cada 3 segundos
    }    

    

    function mostrarProductos(productosFiltrados) {
        $('.menu').empty();

        if (productosFiltrados.length === 0) {
            $('.menu').append('<p>No hay productos de este tipo.</p>');
            return;
        }

        productosFiltrados.forEach(function (producto) {
            let contenedor = $('<div class="producto"></div>');

            contenedor.append('<img class="imagen" src="../../Assets/' + producto.IMAGEN + '" alt="Imagen del producto">');
            contenedor.append('<h3 class="tituloP">' + producto.NOMBRE_PRODUCTO + '/' + producto.MODELO + '</h3>');
            contenedor.append('<p class="precioP">$' + producto.PRECIO_UNITARIO + '</p>');

            contenedor.click(function () {
                const categoria = producto.ID_CATEGORIA.toUpperCase();
                const nombreFormateado = encodeURIComponent(producto.NOMBRE_PRODUCTO);
                let ruta = '';

                switch (categoria) {
                    case 'PC': ruta = `../../Index/Catalogo/ProductoPc/index.php?nombre=${nombreFormateado}`; break;
                    case 'LAP': ruta = `../../Index/Catalogo/ProductoLap/index.php?nombre=${nombreFormateado}`; break;
                    case 'MOU': ruta = `../../Index/Catalogo/ProductoMouse/index.php?nombre=${nombreFormateado}`; break;
                    case 'MON': ruta = `../../Index/Catalogo/ProductoMonitor/index.php?nombre=${nombreFormateado}`; break;
                    case 'KEY': ruta = `../../Index/Catalogo/ProductoTeclado/index.php?nombre=${nombreFormateado}`; break;
                    case 'SPE': ruta = `../../Index/Catalogo/ProductoParlante/index.php?nombre=${nombreFormateado}`; break;
                    case 'COM': ruta = `../../Index/Catalogo/ProductoComponente/index.php?nombre=${nombreFormateado}`; break;
                    case 'CAS': ruta = `../../Index/Catalogo/ProductoCase/index.php?nombre=${nombreFormateado}`; break;
                }

                localStorage.setItem('productoDetalle', JSON.stringify(producto));
                window.location.href = ruta;
            });

            $('.menu').append(contenedor);
        });
    }

    actualizarProductosPeriodicamente();


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
    // Asignar valores a los elementos del detalle
    $('#productoTitulo').text(producto.NOMBRE_PRODUCTO + " / " + producto.MODELO);
    $('#productoImagen').attr('src', '../../../assets/' + producto.IMAGEN);
    $('#productoPrecio').text('Precio: $' + producto.PRECIO_UNITARIO);
    $('#productoEstado').text(producto.ESTADO);
    $('#productoDescripcion').text('Descripción: ' + (producto.DESCRIPCION || 'Sin descripción disponible'));

    // Colores del estado
    let estado = document.getElementById("productoEstado");
    let color = document.getElementById("colorEstado");
    let botonCarrito = document.getElementById("btnAgregarCarrito");
    let inputCantidad = document.getElementById("quantity");

    if (estado.textContent === "DISPONIBLE") {
        color.style.backgroundColor = "green";
        botonCarrito.disabled = false; // Aseguramos que esté habilitado
    } else if (estado.textContent === "AGOTADO") {
        color.style.backgroundColor = "red";
        botonCarrito.disabled = true; // Deshabilitar el botón
        inputCantidad.disabled = true; // Deshabilitar el campo de cantidad
    } else if (estado.textContent === "CASI AGOTADO") {
        color.style.backgroundColor = "orange";
        botonCarrito.disabled = false; // Habilitar el botón si casi agotado
        inputCantidad.disabled = false; // Permitir modificar la cantidad
    }


    let productoID = new URLSearchParams(window.location.search).get('id'); // ID en la URL
let estadoAnterior = null;

function verificarEstadoProducto() {
    if (!productoID) return;

    $.ajax({
        url: 'estadoProducto.php',
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

            } catch (error) {
                console.error('Error al interpretar el estado:', error);
            }
        }
    });
}


$('#btnAgregarCarrito').off('click').on('click', function () {
    const cantidad = parseInt($('#quantity').val()) || 1;

    if (cantidad <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Cantidad inválida',
            text: 'La cantidad debe ser mayor a 0.',
        });
        return;
    }

    const existencias = producto.CANTIDAD;
    if (cantidad > existencias) {
        Swal.fire({
            icon: 'error',
            title: 'Cantidad excedida',
            text: 'No hay suficientes existencias para esta cantidad.',
        });
        return;
    }

    const precio = parseFloat(producto.PRECIO_UNITARIO);
    const total = (cantidad * precio).toFixed(2);

  $.ajax({
    url: '/Gestion-tecnologia-Ds-9/PhP/agregarCarrito.php',
    method: 'POST',
    data: {
        ID_USUARIO: idUsuario,
        ID_PRODUCTO: producto.ID_PRODUCTO,
        CANTIDAD: cantidad,
        TOTAL_PRECIO: total
    },
    success: function (response) {
        console.log("Respuesta de agregarCarrito:", response);
        const data = JSON.parse(response);

        // Si la respuesta es de error, mostrar el mensaje
        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.error,
            });
        } else {
            // Si todo es correcto, actualizar estado y contador
            verificarEstadoProducto();
            actualizarContadorCarrito();

            Swal.fire({
                icon: 'success',
                title: 'Producto agregado',
                text: 'El producto se ha agregado correctamente al carrito.',
            });
        }
    },
    error: function (xhr, status, error) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al agregar al carrito.',
        });
        console.error(error);
    }
});

});
}

  // Función para disminuir la cantidad
window.decrease = function () {
    let value = parseInt($('#quantity').val());
    if (value > 1) {
        $('#quantity').val(value - 1);
    }
}

// Función para aumentar la cantidad
window.increase = function () {
    let value = parseInt($('#quantity').val());
    const maxQuantity = producto.CANTIDAD;  // Máxima cantidad disponible
    if (value < maxQuantity) {
        $('#quantity').val(value + 1);
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Sin Disponibilidad',
            text: `No puedes agregar más unidades.`,
        });
    }};
});