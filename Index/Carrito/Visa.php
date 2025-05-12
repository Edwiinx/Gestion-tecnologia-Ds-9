<?php 
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../Inicio/login.php');
    exit;
}
include("../../PhP/conexion.php");


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Recuperar ID del usuario de la sesión
$id_usuario = $_SESSION['ID_USUARIO'] ?? null;
error_log("DEBUG: ID_USUARIO en sesión: " . var_export($id_usuario, true));

if (!$id_usuario) {
    error_log("ERROR: No se encontró ID_USUARIO en sesión.");
    echo "Error: Usuario no identificado.";
    exit;
}

$sql = "SELECT c.ID_CARRITO, c.ID_PRODUCTO, c.CANTIDAD, c.TOTAL_PRECIO, p.NOMBRE_PRODUCTO, p.IMAGEN 
        FROM CARRITO c 
        INNER JOIN PRODUCTOS p ON c.ID_PRODUCTO = p.ID_PRODUCTO 
        WHERE c.ID_USUARIO = ?";

error_log("DEBUG: Consulta SQL preparada: " . $sql);

$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("ERROR: Falló la preparación de la consulta: " . $conn->error);
    echo "Error en la consulta a la base de datos.";
    exit;
}

$stmt->bind_param("i", $id_usuario);

if (!$stmt->execute()) {
    error_log("ERROR: Falló la ejecución de la consulta: " . $stmt->error);
    echo "Error al ejecutar la consulta.";
    exit;
}

$result = $stmt->get_result();

error_log("DEBUG: Número de productos encontrados: " . $result->num_rows);

$carrito_items = [];
while ($row = $result->fetch_assoc()) {
    $carrito_items[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Carrito y Pago</title>
  <link rel="stylesheet" href="../../Css/DesignVisa.css">
      <link rel="stylesheet" href="../../Css/estiloProducto.css"> <!-- Enlace al CSS -->


</head>
<body>

<div class="container">
  
        <a href="../../Index/Catalogo/Catalogo.php" class="btn-regresar">⟵ Catálogo</a>

   
  <!-- Pantalla del carrito -->
  <div id="carrito">
    <h3>Carrito de Compras</h3>

    <?php if (count($carrito_items) == 0): ?>
      <p>Tu carrito está vacío.</p>
    <?php else: ?>
      <?php foreach ($carrito_items as $item): ?>
        <div class="item" data-id="<?= htmlspecialchars($item['ID_CARRITO']) ?>">
          <img src="../../Assets/<?= htmlspecialchars($item['IMAGEN']) ?>" alt="<?= htmlspecialchars($item['NOMBRE_PRODUCTO']) ?>">
          <div class="item-details">
            <h4><?= htmlspecialchars($item['NOMBRE_PRODUCTO']) ?></h4>
<p data-precio-unitario="<?= htmlspecialchars($item['TOTAL_PRECIO'] / $item['CANTIDAD']) ?>">
  Precio unitario: $<?= number_format($item['TOTAL_PRECIO'] / $item['CANTIDAD'], 2) ?>
  <span class="precio-total" style="display: none;"><?= number_format($item['TOTAL_PRECIO'], 2) ?></span>
</p>

              <div class="qty-controls">
              <button onclick="cambiarCantidad(this, -1)">-</button>
              <span class="cantidad"><?= htmlspecialchars($item['CANTIDAD']) ?></span>
              <button onclick="cambiarCantidad(this, 1)">+</button>
              <button onclick="eliminarProducto(<?= $item['ID_CARRITO'] ?>)" style="margin-left: auto; background-color: #dc3545;">Eliminar</button>
           </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- Resumen de Totales -->
<div id="resumen" style="text-align: right; margin-top: 20px; font-weight: bold;">
  <p>Subtotal: $<span id="subtotal">0.00</span></p>
  <p>ITBMS (7%): $<span id="itbms">0.00</span></p>
  <p>Total: $<span id="total">0.00</span></p>
</div>


    <button class="btn" onclick="mostrarPago()">Proceder a Pagar</button>
    <button class="btn btn-secondary" onclick="window.location.href='/Gestion-tecnologia-Ds-9/Index/Catalogo/Catalogo.php'">
      Continuar comprando
    </button>
  </div>

 <!-- Pantalla de pago -->
<div id="pago" class="hidden">
  <h3>Método de Pago (Solo Visa)</h3>
  <form onsubmit="procesarPago(event)" method="POST" id="formPago">
    
    <label>Número de Tarjeta</label>
    <input
      type="text"
      name="numero_visa"
      id="numero_visa"
      placeholder="1234 5678 9012 3456"
      required
      maxlength="19"
      pattern="^4\d{3}\s\d{4}\s\d{4}\s\d{4}$"
      title="Una tarjeta Visa válida de 16 dígitos, separada por espacios"
      inputmode="numeric"
    >

    <div class="input-group">
      <div>
        <label>Vencimiento</label>
        <input
          type="text"
          name="fecha"
          id="fecha"
          placeholder="MM/AA"
          maxlength="5"
          required
          pattern="^(0[1-9]|1[0-2])\/\d{2}$"
          title="Formato válido: MM/AA"
          inputmode="numeric"
        >
      </div>
      <div>
        <label>CVV</label>
        <input
          type="text"
          name="cvv"
          id="cvv"
          placeholder="123"
          maxlength="3"
          required
          pattern="^\d{3}$"
          title="Ingresa un CVV de 3 dígitos"
          inputmode="numeric"
        >
      </div>
    </div>

    <button class="btn" type="submit">Pagar Ahora</button>
    <button type="button" class="btn btn-secondary" onclick="volverAlCarrito()">Volver al carrito</button>
  </form>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>


function actualizarTotales() {
    let subtotal = 0;

    document.querySelectorAll(".item").forEach(item => {
        const cantidad = parseInt(item.querySelector(".cantidad").textContent);
        const precioUnitario = parseFloat(item.querySelector("[data-precio-unitario]").dataset.precioUnitario);
        const precioTotal = cantidad * precioUnitario;

        // Actualizar visualmente el precio total de ese producto
        item.querySelector(".precio-total").textContent = precioTotal.toFixed(2);

        subtotal += precioTotal;
    });

    const itbms = subtotal * 0.07;
    const total = subtotal + itbms;

    document.getElementById("subtotal").textContent = subtotal.toFixed(2);
    document.getElementById("itbms").textContent = itbms.toFixed(2);
    document.getElementById("total").textContent = total.toFixed(2);
}

function mostrarPago() {
    document.getElementById("carrito").classList.add("hidden");
    document.getElementById("pago").classList.remove("hidden");
}

function volverAlCarrito() {
    document.getElementById("pago").classList.add("hidden");
    document.getElementById("carrito").classList.remove("hidden");
}

function cambiarCantidad(button, delta) {
    // Buscar el contenedor del producto
    const item = button.closest('.item');
    
    // Buscar el `span` donde se muestra la cantidad
    const cantidadSpan = item.querySelector('.cantidad');
    
    // Obtener la cantidad actual y agregar el delta (aumento o disminución)
    let cantidadActual = parseInt(cantidadSpan.textContent);

    // Solo actualizar si la cantidad es mayor que 0 (no se puede tener cantidad negativa)
    if (cantidadActual + delta >= 1) {
        cantidadActual += delta;
    }

    // Actualizar la cantidad en el `span` visualmente
    cantidadSpan.textContent = cantidadActual;

    // Obtener el ID del producto para poder actualizarlo en la base de datos
    const idProducto = item.getAttribute('data-id');

    // Ahora actualizamos el precio total correspondiente a este producto
    const precioUnitario = parseFloat(item.querySelector("[data-precio-unitario]").dataset.precioUnitario);
    const nuevoPrecioTotal = cantidadActual * precioUnitario;

    // Actualizar visualmente el precio total de ese producto
    item.querySelector(".precio-total").textContent = nuevoPrecioTotal.toFixed(2);

    // Enviar la nueva cantidad y el nuevo precio total al servidor con una solicitud AJAX
    fetch('../../PhP/actualizarCantidad.php', {
        method: 'POST',
        body: new URLSearchParams({
            'nuevaCantidad': cantidadActual,
            'id_producto': idProducto,
            'nuevoPrecioTotal': nuevoPrecioTotal.toFixed(2) // Enviar el precio total actualizado
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Cantidad y precio total actualizados:', data.new_quantity, data.new_total);
            // Después de actualizar la cantidad y el precio, recalcular los totales
            actualizarTotales();
        } else {
            console.error('Error al actualizar cantidad y precio total:', data.error);
        }
    })
    .catch(error => {
        console.error('Error en la solicitud:', error);
    });
}


function eliminarProducto(idProducto) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas eliminar este producto del carrito?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'No, cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('../../PhP/eliminarProducto.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: idProducto })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Eliminado',
                        text: 'El producto fue eliminado del carrito.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        willClose: () => {
                            location.reload(); // se ejecuta justo al cerrar el alert
                        }
                    });
                } else {
                    Swal.fire(
                        'Error',
                        'No se pudo eliminar el producto.',
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Error',
                    'Hubo un problema al eliminar el producto.',
                    'error'
                );
            });
        }
    });
}




document.getElementById('numero_visa').addEventListener('input', function (e) {
  let valor = e.target.value.replace(/\D/g, '').substring(0, 16); // solo dígitos
  valor = valor.replace(/(.{4})/g, '$1 ').trim(); // añade espacio cada 4 dígitos
  e.target.value = valor;
});

document.getElementById('fecha').addEventListener('input', function (e) {
  let valor = e.target.value.replace(/\D/g, '').substring(0, 4);
  if (valor.length >= 3) {
    valor = valor.replace(/(\d{2})(\d{1,2})/, '$1/$2');
  }
  e.target.value = valor;
});

function procesarPago(event) { 
  event.preventDefault();
  const form = document.getElementById("formPago");

  if (!form.checkValidity()) {
    form.reportValidity();
    return;
  }

  const numero_visa = form.numero_visa.value.replace(/\s/g, '');
  const fecha = form.fecha.value;
  const cvv = form.cvv.value;
  const total = parseFloat(document.getElementById("total").textContent);

  // Validación de vencimiento (que no esté expirado)
  const [mes, anio] = fecha.split('/');
  const ahora = new Date();
  const fechaIngresada = new Date(`20${anio}`, mes - 1);
  if (fechaIngresada < ahora) {
    Swal.fire('Tarjeta vencida', 'La tarjeta ingresada ya expiró.', 'error');
    return;
  }

  fetch("../../PhP/procesar_pago.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `numero_visa=${numero_visa}&fecha=${fecha}&cvv=${cvv}&total=${total}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      Swal.fire({
        icon: 'success',
        title: 'Compra exitosa',
        text: 'Tu compra ha sido procesada correctamente.',
        showCancelButton: true,
        confirmButtonText: 'Ver Recibo',
        cancelButtonText: 'Volver al Inicio',
        allowOutsideClick: false,
        allowEscapeKey: false
      }).then((result) => {
        if (result.isConfirmed) {
          // Mostrar recibo con los mismos botones de estilo swal2
          fetch(`../../PhP/ver_recibo.php?id=${data.id_compra}`)
            .then(res => res.json())
            .then(recibo => {
              if (recibo.success) {
                Swal.fire({
                  icon: 'info',
                  title: 'Recibo de Compra',
                  html: `
                    <p><strong>ID Compra:</strong> ${recibo.id_compra}</p>
                    <p><strong>Total:</strong> $${recibo.total}</p>
                    <p><strong>Fecha:</strong> ${recibo.fecha}</p>
                    <p><strong>Tarjeta:</strong> ${recibo.tarjeta}</p>
                  `,
                  confirmButtonText: 'Volver al Inicio',
                  allowOutsideClick: false
                }).then(res => {
                  if (res.isConfirmed) {
                    window.location.href = "../Catalogo/Catalogo.php";
                  }
                });
              } else {
                Swal.fire('Error', recibo.message, 'error');
              }
            });
        } else {
          window.location.href = "../Catalogo/Catalogo.php";
        }
      });
    } else {
      Swal.fire('Error', data.message, 'error');
    }
  })
  .catch(async (error) => {
    const respuesta = await error.text?.() || error;
    console.error("Respuesta no JSON:", respuesta);
    Swal.fire('Error', 'No se pudo procesar el pago.', 'error');
  });
}



</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>window.onload = () => {actualizarTotales();  };</script>


</body>
</html>
