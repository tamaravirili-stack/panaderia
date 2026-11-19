
<?php

session_start();

if (!isset($_SESSION['admin_logueado']) || $_SESSION['admin_logueado'] !== true) {
    header('Location: login.php');
    exit();
}

require __DIR__ . '/config/bd.php';

$productos = $conexion->query(
    'SELECT * FROM productos ORDER BY id DESC'
)->fetchAll();

$numeroProductos = count($productos);

$consultaPedidos = $conexion->prepare(
    "SELECT COUNT(*) FROM pedidos WHERE estado = ?"
);

$consultaPedidos->execute(['Pendiente']);

$pedidosPendientes = $consultaPedidos->fetchColumn();

require __DIR__ . '/template/cabecera.php';

?>

<div class="admin-dashboard">

    <?php if ($pedidosPendientes > 0): ?>

        <div class="alert alert-warning shadow-sm">
            <strong>¡Atención!</strong>

            Tenés <?php echo $pedidosPendientes; ?>
            pedido(s) pendiente(s) por revisar.

            <a href="seccion/pedidos.php" class="alert-link">
                Ver pedidos
            </a>
        </div>

    <?php else: ?>

        <div class="alert alert-success shadow-sm">
            No hay pedidos pendientes.
        </div>

    <?php endif; ?>

    <div class="row align-items-center mb-5">

        <div class="col-lg-7">

            <span class="badge text-bg-warning text-dark px-3 py-2 rounded-pill mb-3">
                Panel de administración
            </span>

            <h1 class="display-5 fw-bold mb-3">
                Bienvenido al control de tu panadería
            </h1>

            <p class="lead text-muted mb-4">
                Desde aquí podés gestionar tu catálogo y controlar
                los pedidos de tu negocio.
            </p>

            <div class="d-flex gap-3 flex-wrap">

                <a href="seccion/productos.php"
                   class="btn btn-primary btn-lg rounded-pill px-4">
                    Gestionar productos
                </a>

                <a href="seccion/pedidos.php"
                   class="btn btn-success btn-lg rounded-pill px-4">
                    Gestionar pedidos
                </a>

                <a href="../index.php"
                   class="btn btn-outline-dark btn-lg rounded-pill px-4">
                    Ver sitio web
                </a>

            </div>

        </div>

        <div class="col-lg-5 text-center">

            <img
                src="../imagen/cafeteria.png"
                alt="Panadería"
                class="img-fluid rounded-4 shadow-lg admin-dashboard-image">

        </div>

    </div>

    <div class="admin-panel">

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

            <div>
                <h3 class="mb-0">Últimos productos</h3>

                <p class="mb-0 mt-2">
                    Actualmente hay
                    <strong><?php echo $numeroProductos; ?></strong>
                    productos registrados.
                </p>
            </div>

            <a href="seccion/productos.php"
               class="btn btn-dark rounded-pill">
                Administrar catálogo
            </a>

        </div>

        <?php if (empty($productos)): ?>

            <div class="alert alert-info mb-0">
                Todavía no hay productos cargados.
            </div>

        <?php else: ?>

            <div class="table-responsive">

                <table class="table align-middle table-hover mb-0">

                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Imagen</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach (array_slice($productos, 0, 10) as $producto): ?>

                            <tr>

                                <td>
                                    <?php echo htmlspecialchars($producto['nombre']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($producto['categoria'] ?? 'Panadería'); ?>
                                </td>

                                <td>
                                    $<?php echo number_format((float) $producto['precio'], 0, ',', '.'); ?>
                                </td>

                                <td>

                                    <img
                                        src="../imagen/<?php echo rawurlencode($producto['imagen'] ?: 'pan.png'); ?>"
                                        alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                        class="admin-product-thumb">

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php require __DIR__ . '/template/pie.php'; ?>
