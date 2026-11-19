
<?php

require_once __DIR__ . '/../config/bd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = (int) $_POST['id'];
    $estado = $_POST['estado'];

    $estadosPermitidos = ['Pendiente', 'Preparando', 'Entregado'];

    if (in_array($estado, $estadosPermitidos, true)) {

        $consulta = $conexion->prepare(
            "UPDATE pedidos SET estado = ? WHERE id = ?"
        );

        $consulta->execute([$estado, $id]);
    }

    header('Location: pedidos.php');
    exit();
}

require_once __DIR__ . '/../template/cabecera.php';

$consulta = $conexion->query(
    "SELECT * FROM pedidos ORDER BY fecha DESC"
);

$pedidos = $consulta->fetchAll();

foreach ($pedidos as &$pedido) {

    $consultaDetalle = $conexion->prepare(
        "SELECT nombre_producto, cantidad, subtotal
        FROM detalle_pedido
        WHERE pedido_id = ?"
    );

    $consultaDetalle->execute([$pedido['id']]);

    $pedido['productos'] = $consultaDetalle->fetchAll();
}

unset($pedido);
?>

<div class="container py-4">

    <div class="mb-4">
        <h2 class="fw-bold">Gestión de pedidos</h2>
        <p class="text-muted">
            Administrá los pedidos de PANSUSU.
        </p>
    </div>

    <?php if (empty($pedidos)): ?>

        <div class="alert alert-info rounded-3 shadow-sm">
            Todavía no hay pedidos registrados.
        </div>

    <?php else: ?>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

            <div class="card-header bg-dark text-white p-3">
                <h5 class="mb-1">Pedidos de PANSUSU</h5>
                <small>Listado de compras de los clientes</small>
            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-dark">
                        <tr>
                            <th class="p-3">Correo del cliente</th>
                            <th class="p-3">Fecha</th>
                            <th class="p-3">Total</th>
                            <th class="p-3">Estado</th>
                            <th class="p-3">Productos</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($pedidos as $pedido): ?>

                            <tr>

                                <td class="p-3">
                                    <?php echo htmlspecialchars($pedido['email']); ?>
                                </td>

                                <td class="p-3">
                                    <?php echo htmlspecialchars($pedido['fecha']); ?>
                                </td>

                                <td class="p-3 fw-bold text-nowrap">
                                    $<?php echo number_format(
                                        (float) $pedido['total'],
                                        2,
                                        ',',
                                        '.'
                                    ); ?>
                                </td>

                                <td class="p-3">

                                    <form method="POST" action="pedidos.php">

                                        <input
                                            type="hidden"
                                            name="id"
                                            value="<?php echo (int) $pedido['id']; ?>"
                                        >

                                        <select
                                            name="estado"
                                            class="form-select form-select-sm mb-2"
                                        >

                                            <option value="Pendiente"
                                                <?php if ($pedido['estado'] === 'Pendiente') echo 'selected'; ?>>
                                                Pendiente
                                            </option>

                                            <option value="Preparando"
                                                <?php if ($pedido['estado'] === 'Preparando') echo 'selected'; ?>>
                                                Preparando
                                            </option>

                                            <option value="Entregado"
                                                <?php if ($pedido['estado'] === 'Entregado') echo 'selected'; ?>>
                                                Entregado
                                            </option>

                                        </select>

                                        <button
                                            type="submit"
                                            class="btn btn-dark btn-sm w-100"
                                        >
                                            Guardar
                                        </button>

                                    </form>

                                </td>

                                <td class="p-3">

                                    <?php foreach ($pedido['productos'] as $producto): ?>

                                        <div class="mb-2">
                                            <span class="badge text-bg-light border text-wrap">
                                                <?php echo htmlspecialchars($producto['nombre_producto']); ?>
                                                (x<?php echo (int) $producto['cantidad']; ?>)
                                            </span>
                                        </div>

                                    <?php endforeach; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>
        </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../template/pie.php'; ?>
