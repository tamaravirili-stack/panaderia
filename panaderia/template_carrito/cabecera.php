
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panadería "El Buen Pan"</title>
    <link href="estilo/bootstrap.min.css" rel="stylesheet">
    <link href="estilo/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary py-3 shadow-sm">
        <div class="container-fluid px-4 px-lg-5">
            <a class="navbar-brand logo-ovalado-dark">🍞pansusu</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarID">
                <span class="navbar-toggler-icon"></span>
            </button>

            
            <div class="collapse navbar-collapse justify-content-end" id="navbarID">
                <ul class="navbar-nav gap-3">
                    <li class="nav-item"><a class="nav-link fs-5 text-white" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link fs-5 text-white" href="productos.php">Catálogo</a></li>
                    <li class="nav-item"><a class="nav-link fs-5 text-white" href="nosotros.php">Nosotros</a></li>
                    <li class="nav-item"><a class="nav-link fs-5 text-white" href="contacto.php">Contacto</a></li>
                                    <li class="nav-item">
    <a class="nav-link fs-5 text-white" href="mostrar_carrito.php">
        <i class="fas fa-shopping-cart"></i> Carrito
    </a>
</li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-5 mb-5">