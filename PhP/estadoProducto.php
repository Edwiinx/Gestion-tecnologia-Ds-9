<?php
// estadoProducto.php
include("conexion.php");

if (isset($_GET['id'])) {
    // Validar el ID de producto para evitar inyecciones SQL (opcional pero recomendado)
    $id = $_GET['id'];

    // Consulta para obtener el estado y cantidad del producto
    $query = "SELECT ESTADO, CANTIDAD FROM PRODUCTOS WHERE ID_PRODUCTO = ?";
    $stmt = $conn->prepare($query);

    // Verificar que la preparación fue exitosa
    if ($stmt === false) {
        echo json_encode(["error" => "Error al preparar la consulta."]);
        exit;
    }

    // Enlazar el parámetro (ID del producto)
    $stmt->bind_param("s", $id);
    $stmt->execute();

    // Obtener los resultados
    $resultado = $stmt->get_result();

    // Verificar si se encontró el producto
    if ($resultado->num_rows > 0) {
        // Si se encuentra, devolver el estado y cantidad
        $row = $resultado->fetch_assoc();
        echo json_encode($row);
    } else {
        // Si no se encuentra el producto, devolver error
        echo json_encode(["error" => "Producto no encontrado."]);
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["error" => "No se ha proporcionado un ID de producto."]);
}
?>
