<?php 
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ../Inicio/login.php');
    exit;
}
include("../../PhP/conexion.php");

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
</head>
<body>

<div class="container">
  <!-- Pantalla del carrito -->
  <div id="carrito">
    <h3>Carrito de Compras</h3>

    <?php if (count($carrito_items) == 0): ?>
      <p>Tu carrito está vacío.</p>
    <?php else: ?>
      <?php foreach ($carrito_items as $item): ?>
        <div class="item" data-id="<?= htmlspecialchars($item['ID_PRODUCTO']) ?>">
          <img src="../../Assets/<?= htmlspecialchars($item['IMAGEN']) ?>" alt="<?= htmlspecialchars($item['NOMBRE_PRODUCTO']) ?>">
          <div class="item-details">
            <h4><?= htmlspecialchars($item['NOMBRE_PRODUCTO']) ?></h4>
            <p>Precio: $<?= number_format($item['TOTAL_PRECIO'], 2) ?></p>
            <div class="qty-controls">
              <button onclick="cambiarCantidad(this, -1)">-</button>
              <span class="cantidad"><?= htmlspecialchars($item['CANTIDAD']) ?></span>
              <button onclick="cambiarCantidad(this, 1)">+</button>
              <button onclick="eliminarProducto(this)" style="margin-left: auto; background-color: #dc3545;">Eliminar</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <button class="btn" onclick="mostrarPago()">Proceder a Pagar</button>
    <button class="btn btn-secondary" onclick="window.location.href='/Gestion-tecnologia-Ds-9/Index/Catalogo/Catalogo.php'">
      Continuar comprando
    </button>
  </div>

  <!-- Pantalla de pago -->
  <div id="pago" class="hidden">
    <h3>Método de Pago (Solo Visa)</h3>
    <form onsubmit="procesarPago(event)" method="POST">
      <label>Número de Tarjeta</label>
      <input type="text" placeholder="1234 5678 9012 3456" required pattern="4[0-9]{12}(?:[0-9]{3})?">

      <div class="input-group">
        <div>
          <label>Vencimiento</label>
          <input type="text" placeholder="MM/AA" maxlength="5" required>
        </div>
        <div>
          <label>CVV</label>
          <input type="text" placeholder="123" maxlength="3" required>
        </div>
      </div>

      <button class="btn" type="submit">Pagar Ahora</button>
      <button type="button" class="btn btn-secondary" onclick="volverAlCarrito()">Volver al carrito</button>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
function mostrarPago() {
    document.getElementById("carrito").classList.add("hidden");
    document.getElementById("pago").classList.remove("hidden");
}

function volverAlCarrito() {
    document.getElementById("pago").classList.add("hidden");
    document.getElementById("carrito").classList.remove("hidden");
}

function cambiarCantidad(btn, delta) {
    const item = btn.closest(".item");
    const id = item.dataset.id;

    fetch('../../PhP/actualizarCantidad.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}&delta=${delta}`
    }).then(() => location.reload());
}

function eliminarProducto(btn) {
    const item = btn.closest(".item");
    const id = item.dataset.id;

    fetch('../../PhP/eliminarProducto.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}`
    }).then(() => location.reload());
}

function procesarPago(e) {
    e.preventDefault();
    alert("Pago procesado exitosamente. ¡Gracias por tu compra!");

    // Limpiar carrito
    fetch('../../PhP/vaciarCarrito.php', {
        method: 'POST'
    }).then(() => {
        window.location.href = '../Catalogo/Catalogo.php';
    });
}
</script>

</body>
</html>
