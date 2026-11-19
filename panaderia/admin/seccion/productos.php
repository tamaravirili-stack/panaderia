
<?php

session_start();

if (!isset($_SESSION['admin_logueado']) || $_SESSION['admin_logueado'] !== true) {
    header('Location: ../login.php');
    exit();
}

require __DIR__ . '/../config/bd.php';

$mensajeError = '';
$productoSeleccionado = null;

$directorioImagenes = dirname(__DIR__, 2) . '/imagen/';

if (!is_dir($directorioImagenes)) {
    mkdir($directorioImagenes, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $accion = $_POST['accion'] ?? '';
    $id = (int) ($_POST['id'] ?? 0);

    if ($accion === 'agregar' || $accion === 'guardar') {

        $nombre = trim($_POST['nombre'] ?? '');
        $precio = filter_var($_POST['precio'] ?? '', FILTER_VALIDATE_FLOAT);
        $categoria = trim($_POST['categoria'] ?? '');

        if ($nombre === '' || $precio === false || $precio <= 0 || $categoria === '') {

            $mensajeError = 'Completá todos los campos correctamente.';

        } else {

            $imagen = 'pan.png';

            if ($accion === 'guardar' && $id > 0) {

                $consulta = $conexion->prepare(
                    'SELECT imagen FROM productos WHERE id = :id'
                );

                $consulta->execute([':id' => $id]);

                $imagenAnterior = $consulta->fetchColumn();

                if ($imagenAnterior === false) {
                    $mensajeError = 'El producto seleccionado no existe.';
                } else {
                    $imagen = $imagenAnterior ?: 'pan.png';
                }
            }

            if (
                $mensajeError === '' &&
                isset($_FILES['imagen']) &&
                $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE
            ) {

                if ($_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {

                    $mensajeError = 'Ocurrió un error al subir la imagen.';

                } elseif ($_FILES['imagen']['size'] > 2 * 1024 * 1024) {

                    $mensajeError = 'La imagen no puede superar los 2 MB.';

                } else {

                    $tipoImagen = mime_content_type($_FILES['imagen']['tmp_name']);

                    $extensionesPermitidas = [
                        'image/jpeg' => 'jpg',
                        'image/png' => 'png',
                        'image/webp' => 'webp',
                        'image/gif' => 'gif'
                    ];

                    if (!isset($extensionesPermitidas[$tipoImagen])) {

                        $mensajeError = 'Formato de imagen no permitido. Usá JPG, PNG, WEBP o GIF.';

                    } else {

                        $nombreImagen = uniqid('producto_', true) . '.' .
                            $extensionesPermitidas[$tipoImagen];

                        $rutaImagen = $directorioImagenes . $nombreImagen;

                        if (move_uploaded_file(
                            $_FILES['imagen']['tmp_name'],
                            $rutaImagen
                        )) {
                            $imagen = $nombreImagen;
                        } else {
                            $mensajeError = 'No se pudo guardar la imagen.';
                        }
                    }
                }
            }

            if ($mensajeError === '' && $accion === 'agregar') {

                $sentencia = $conexion->prepare(
                    'INSERT INTO productos (nombre, precio, categoria, imagen)
                     VALUES (:nombre, :precio, :categoria, :imagen)'
                );

                $sentencia->execute([
                    ':nombre' => $nombre,
                    ':precio' => $precio,
                    ':categoria' => $categoria,
                    ':imagen' => $imagen
                ]);

                header('Location: productos.php');
                exit();
            }

            if ($mensajeError === '' && $accion === 'guardar' && $id > 0) {

                $sentencia = $conexion->prepare(
                    'UPDATE productos
                     SET nombre = :nombre,
                         precio = :precio,
                         categoria = :categoria,
                         imagen = :imagen
                     WHERE id = :id'
                );

                $sentencia->execute([
                    ':id' => $id,
                    ':nombre' => $nombre,
                    ':precio' => $precio,
                    ':categoria' => $categoria,
                    ':imagen' => $imagen
                ]);

                header('Location: productos.php');
                exit();
            }
        }
    }

    if ($accion === 'eliminar' && $id > 0) {

        try {

            $sentencia = $conexion->prepare(
                'DELETE FROM productos WHERE id = :id'
            );

            $sentencia->execute([':id' => $id]);

            header('Location: productos.php');
            exit();

        } catch (PDOException $error) {

            $mensajeError = 'No se puede eliminar este producto porque está asociado a un pedido registrado.';
        }
    }
}

$idEditar = (int) ($_GET['editar'] ?? 0);

if ($idEditar > 0) {

    $sentencia = $conexion->prepare(
        'SELECT * FROM productos WHERE id = :id'
    );

    $sentencia->execute([':id' => $idEditar]);

    $productoSeleccionado = $sentencia->fetch() ?: null;
}

$productos = $conexion->query(
    'SELECT * FROM productos ORDER BY id DESC'
)->fetchAll();

require __DIR__ . '/../template/cabecera.php';

?>

<div class="row g-4">

    <div class="col-lg-4">

        <div class="admin-product-form">

            <h3 class="mb-4">
                <?php echo $productoSeleccionado ? 'Editar producto' : 'Agregar producto'; ?>
            </h3>

            <?php if ($mensajeError !== ''): ?>

                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($mensajeError); ?>
                </div>

            <?php endif; ?>

            <form method="post"
                  action="productos.php"
                  class="row g-3"
                  enctype="multipart/form-data">

                <input type="hidden"
                       name="id"
                       value="<?php echo htmlspecialchars((string) ($productoSeleccionado['id'] ?? '')); ?>">

                <div class="col-12">

                    <label for="nombre" class="form-label">Nombre</label>

                    <input type="text"
                           name="nombre"
                           id="nombre"
                           class="form-control"
                           maxlength="150"
                           value="<?php echo htmlspecialchars($productoSeleccionado['nombre'] ?? ''); ?>"
                           required>

                </div>

                <div class="col-12">

                    <label for="precio" class="form-label">Precio</label>

                    <input type="number"
                           name="precio"
                           id="precio"
                           class="form-control"
                           min="1"
                           step="0.01"
                           value="<?php echo htmlspecialchars((string) ($productoSeleccionado['precio'] ?? '')); ?>"
                           required>

                </div>

                <div class="col-12">

                    <label for="categoria" class="form-label">Categoría</label>

                    <input type="text"
                           name="categoria"
                           id="categoria"
                           class="form-control"
                           maxlength="100"
                           value="<?php echo htmlspecialchars($productoSeleccionado['categoria'] ?? 'Panadería'); ?>"
                           required>

                </div>

                <div class="col-12">

                    <label for="imagen" class="form-label">Imagen del producto</label>

                    <input type="file"
                           name="imagen"
                           id="imagen"
                           class="form-control"
                           accept=".jpg,.jpeg,.png,.webp,.gif">

                    <?php if (!empty($productoSeleccionado['imagen'])): ?>

                        <small class="text-muted">
                            Imagen actual:
                            <?php echo htmlspecialchars($productoSeleccionado['imagen']); ?>
                        </small>

                    <?php endif; ?>

                </div>

                <div class="col-12 d-flex gap-2 flex-wrap">

                    <button type="submit"
                            name="accion"
                            value="<?php echo $productoSeleccionado ? 'guardar' : 'agregar'; ?>"
                            class="btn btn-primary rounded-pill">

                        <?php echo $productoSeleccionado ? 'Guardar cambios' : 'Agregar producto'; ?>

                    </button>

                    <?php if ($productoSeleccionado): ?>

                        <a href="productos.php"
                           class="btn btn-outline-secondary rounded-pill">
                            Cancelar
                        </a>

                    <?php endif; ?>

                </div>

            </form>

        </div>

    </div>

    <div class="col-lg-8">

        <div class="admin-panel">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

                <h3 class="mb-0">Catálogo actual</h3>

                <span class="badge bg-dark rounded-pill px-3 py-2">
                    <?php echo count($productos); ?> productos
                </span>

            </div>

            <?php if ($mensajeError !== ''): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($mensajeError); ?>
                </div>
            <?php endif; ?>

            <div class="table-responsive">

                <table class="table align-middle table-hover mb-0">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($productos as $producto): ?>

                            <tr>

                                <td>
                                    <?php echo htmlspecialchars((string) $producto['id']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($producto['nombre']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($producto['categoria']); ?>
                                </td>

                                <td>
                                    $<?php echo number_format((float) $producto['precio'], 0, ',', '.'); ?>
                                </td>

                                <td>

                                    <img
                                        src="../../imagen/<?php echo rawurlencode($producto['imagen'] ?: 'pan.png'); ?>"
                                        alt="<?php echo htmlspecialchars($producto['nombre']); ?>"
                                        class="admin-product-thumb">

                                </td>

                                <td class="admin-table-actions">

                                    <a href="productos.php?editar=<?php echo (int) $producto['id']; ?>"
                                       class="btn btn-sm btn-outline-primary rounded-pill">
                                        Editar
                                    </a>

                                    <form method="post"
                                          action="productos.php"
                                          class="d-inline">

                                        <input type="hidden"
                                               name="id"
                                               value="<?php echo (int) $producto['id']; ?>">

                                        <button type="submit"
                                                name="accion"
                                                value="eliminar"
                                                class="btn btn-sm btn-outline-danger rounded-pill"
                                                onclick="return confirm('¿Deseas eliminar este producto?');">
                                            Eliminar
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php require __DIR__ . '/../template/pie.php'; ?>
