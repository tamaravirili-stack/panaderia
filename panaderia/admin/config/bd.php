
<?php
//servidor
$host = '127.0.0.1';
$bd = 'panaderia2';
$dbUsuario = 'root';
$contrasenia = '';

try {

    $conexion = new PDO(
        "mysql:host=$host;dbname=$bd;charset=utf8mb4",
        $dbUsuario,
        $contrasenia,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            //guarda los datos
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            //desactiva las consultas
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

} catch (PDOException $error) {

    die('Error al conectar con la base de datos. Verificá la configuración.');

}

define("KEY", "panaderia");
define("COD", "AES-128-ECB");

?>
