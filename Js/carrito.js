$(document).ready(function() {
    $.ajax({
        url: '../../PhP/consultacarrito.php', 
        method: 'GET',
        dataType: 'json',  
        success: function(data) {
            let productos = data;

            
            productos.forEach(function(producto) {
                
                let contenedor = $('<div class="producto"></div>');
                
                
                contenedor.append('<h3>' + producto.producto + '</h3>');
                contenedor.append('<p>Marca: ' + producto.marca + '</p>');
                contenedor.append('<p>Tipo: ' + producto.tipo + '</p>');
                
                
                $('.menu').append(contenedor);
            });
        },
       
    });
});
  