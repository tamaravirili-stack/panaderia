
<?php

session_start();

// Calcular la ruta principal del proyecto.
$scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/');
$projectRoot = dirname($scriptPath);

if (strpos($scriptPath, '/admin/') !== false) {
    $projectRoot = substr($scriptPath, 0, strpos($scriptPath, '/admin/')) ?: '/';
}

if ($projectRoot === '.' || $projectRoot === '') {
    $projectRoot = '/';
}

$basePath = $projectRoot === '/' ? '' : $projectRoot;


// Si ya inició sesión, ir al panel.
if (isset($_SESSION['admin_logueado']) && $_SESSION['admin_logueado'] === true) {
    header('Location: ' . $basePath . '/admin/index.php');
    exit();
}

$error = '';


// Procesar el formulario.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    // Buscar el administrador en la base de datos.
    require __DIR__ . '/config/bd.php';

    $consulta = $conexion->prepare(
        "SELECT * FROM administradores WHERE usuario = ?"
    );

    $consulta->execute([$usuario]);

    $administrador = $consulta->fetch(PDO::FETCH_ASSOC);

    // Comprobar usuario y contraseña.
    if ($administrador && password_verify($password, $administrador['password'])) {

        // Renovar identificador de sesión.
        session_regenerate_id(true);

        $_SESSION['admin_logueado'] = true;
        $_SESSION['usuario'] = $administrador['usuario'];

        header('Location: ' . $basePath . '/admin/index.php');
        exit();

    } else {

        $error = 'Usuario o contraseña incorrectos.';

    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login administrativo</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Estilos propios de PANSUSU -->
    <link href="<?= $basePath ?>/estilo/styles.css" rel="stylesheet">

</head>


<body class="admin-auth-body">

    <div class="container">

        <div class="row align-items-center min-vh-100">


            <!-- PRESENTACIÓN VISUAL DE TAMARA -->

            <div class="col-lg-6 d-none d-lg-block">

                <div class="login-visual">

                    <span class="badge text-bg-light mb-3">
                        Panel del administrador
                    </span>

                    <h1>
                        Administrá tu panadería desde un solo lugar.
                    </h1>

                    <p>
                        Controlá productos, precios y promociones para ofrecer
                        la mejor experiencia en tu tienda.
                    </p>

                    <img
                        src="<?= $basePath ?>/imagen/cafeteria.png"
                        alt="Panadería"
                        class="img-fluid rounded-4 shadow-lg mt-4">

                </div>

            </div>


            <!-- FORMULARIO DE ACCESO DE TAMARA -->

            <div class="col-lg-6">

                <div class="admin-card">

                    <div class="text-center mb-4">

                        <div class="brand-badge">🍞 pansusu</div>

                        <h2 class="fw-bold">
                            Acceso administrativo
                        </h2>

                    </div>


                    <!-- MENSAJE DE ERROR -->

                    <?php if ($error !== ''): ?>

                        <div class="alert alert-danger text-center mb-4">

                            <?php echo htmlspecialchars($error); ?>

                        </div>

                    <?php endif; ?>


                    <!-- FORMULARIO -->

                    <form action="" method="post">

                        <div class="mb-3">

                            <label for="usuario" class="form-label">
                                Usuario
                            </label>

                            <input
                                type="text"
                                name="usuario"
                                id="usuario"
                                class="form-control"
                                placeholder="Ingrese su usuario"
                                required>

                        </div>


                        <div class="mb-4">

                            <label for="password" class="form-label">
                                Contraseña
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                placeholder="Ingrese su contraseña"
                                required>

                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary w-100 btn-lg rounded-pill">

                            Ingresar al panel

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
