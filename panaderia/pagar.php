<?php
session_start();

include_once("admin/config/bd.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: mostrar_carrito.php");
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

if (!$email) {
    exit("El correo electrónico no es válido.");
}

if (empty($_SESSION['CARRITO'])) {
    exit("El carrito está vacío.");
}

$total = 0;
$detalles = [];
$idPedido = null;
$error = "";

try {

    // Iniciamos la transacción.
    $conexion->beginTransaction();

    $consulta = $conexion->prepare(
        "SELECT id, nombre, precio FROM productos WHERE id = ?"
    );

    foreach ($_SESSION['CARRITO'] as $producto) {

        $id = (int) $producto['ID'];
        $cantidad = filter_var(
            $producto['CANTIDAD'],
            FILTER_VALIDATE_INT
        );

        if ($id <= 0 || $cantidad === false || $cantidad < 1) {
            throw new Exception("Hay un producto con datos incorrectos.");
        }

        $consulta->execute([$id]);
        $datosProducto = $consulta->fetch();

        if (!$datosProducto) {
            throw new Exception(
                "Uno de los productos no existe en la base de datos."
            );
        }

        $precio = (float) $datosProducto['precio'];
        $subtotal = round($precio * $cantidad, 2);
        $total += $subtotal;

        $detalles[] = [
            'producto_id' => $datosProducto['id'],
            'nombre'      => $datosProducto['nombre'],
            'cantidad'    => $cantidad,
            'precio'      => $precio,
            'subtotal'    => $subtotal
        ];
    }

    $sqlPedido = $conexion->prepare(
        "INSERT INTO pedidos (email, total, estado)
         VALUES (?, ?, 'Pendiente')"
    );

    $sqlPedido->execute([$email, $total]);

    $idPedido = $conexion->lastInsertId();

    $sqlDetalle = $conexion->prepare(
        "INSERT INTO detalle_pedido
        (pedido_id, producto_id, nombre_producto, cantidad, precio, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    foreach ($detalles as $detalle) {
        $sqlDetalle->execute([
            $idPedido,
            $detalle['producto_id'],
            $detalle['nombre'],
            $detalle['cantidad'],
            $detalle['precio'],
            $detalle['subtotal']
        ]);
    }

    // Confirmamos los cambios y vaciamos el carrito.
    $conexion->commit();
    unset($_SESSION['CARRITO']);

} catch (Exception $e) {

    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }

    $error = "No se pudo registrar el pedido. Revisá los datos e intentá nuevamente.";
}
?>

<?php include_once("template_carrito/cabecera.php"); ?>

<div class="container py-4">

    <?php if ($error === "") { ?>

        <div class="alert alert-success" role="alert">
            <h3>¡Pedido registrado correctamente!</h3>

            <p>Gracias por comprar en PANSUSU.</p>

            <p>
                Número de pedido:
                <strong><?php echo htmlspecialchars((string) $idPedido); ?></strong>
            </p>

            <p>
                Total:
                <strong>
                    $<?php echo number_format($total, 2, ',', '.'); ?>
                </strong>
            </p>

            <p>Estado: Pendiente</p>
        </div>

        <a href="productos.php" class="btn btn-primary">
            Volver a los productos
        </a>

    <?php } else { ?>

        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error); ?>
        </div>

        <a href="mostrar_carrito.php" class="btn btn-primary">
            Volver al carrito
        </a>

    <?php } ?>

</div>

<?php include_once("template_carrito/pie.php"); ?>