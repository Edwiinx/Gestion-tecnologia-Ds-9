<?php
session_start();

if (!isset($_SESSION['carrito']) || count($_SESSION['carrito']) == 0) {
    echo "<p>Tu carrito está vacío.</p>";
    exit;
}

foreach ($_SESSION['carrito'] as $item):
?>
    <div class="producto-carrito" data-precio="<?= htmlspecialchars($item['TOTAL_PRECIO']) ?>">
        <img src="<?= htmlspecialchars($item['IMAGEN_URL']) ?>" width="50">
        <h4><?= htmlspecialchars($item['NOMBRE_PRODUCTO']) ?></h4>
        <div>
            <button class="disminuir" data-id="<?= $item['ID_PRODUCTO'] ?>">-</button>
            <span class="cantidad"><?= htmlspecialchars($item['CANTIDAD']) ?></span>
            <button class="aumentar" data-id="<?= $item['ID_PRODUCTO'] ?>">+</button>
        </div>
        <button class="eliminar" data-id="<?= $item['ID_PRODUCTO'] ?>">Eliminar</button>
    </div>
<?php
endforeach;
?>
