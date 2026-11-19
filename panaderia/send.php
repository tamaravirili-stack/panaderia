
<?php

if (isset($_POST['send'])) {

    if (
        strlen($_POST['name']) >= 1 &&
        strlen($_POST['email']) >= 1 &&
        strlen($_POST['mensaje']) >= 1
    ) {

        require_once __DIR__ . '/admin/config/bd.php';

        $nombre = trim($_POST['name']);
        $email = trim($_POST['email']);
        $mensaje = trim($_POST['mensaje']);

        $consulta = $conexion->prepare(
            "INSERT INTO mensajes (nombre, email, mensaje)
             VALUES (?, ?, ?)"
        );

        $consulta->execute([$nombre, $email, $mensaje]);

        echo '<div class="alert alert-success mt-3 shadow-sm">
                ¡Gracias! Tu mensaje se guardó correctamente.
              </div>';

    } else {

        echo '<div class="alert alert-danger mt-3 shadow-sm">
                Error: Por favor completá todos los campos.
              </div>';
    }
}
?>
