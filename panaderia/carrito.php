<?php

session_start();
include_once("admin/config/bd.php");

$mensaje = "";

if (isset($_POST['btnAccion'])) {

    switch ($_POST['btnAccion']) {

        case 'Agregar':

            // Validamos los datos recibidos.
            if (is_numeric(openssl_decrypt($_POST['id'], COD, KEY))) {

                $ID = openssl_decrypt($_POST['id'], COD, KEY);
                $mensaje .= "OK....ID correcto:" . $ID . "<br/>";

            } else {
                $mensaje .= "UPss....Id incorrecto<br/>";
                break;
            }

            if (is_string(openssl_decrypt($_POST['nombre'], COD, KEY))) {

                $NOMBRE = openssl_decrypt($_POST['nombre'], COD, KEY);

            } else {
                $mensaje .= "UPss....Nombre incorrecto<br/>";
                break;
            }

            if (is_numeric(openssl_decrypt($_POST['cantidad'], COD, KEY))) {

                $CANTIDAD = openssl_decrypt($_POST['cantidad'], COD, KEY);

            } else {
                $mensaje .= "UPss....cantidad incorrecta<br/>";
                break;
            }

            if (is_numeric(openssl_decrypt($_POST['precio'], COD, KEY))) {

                $PRECIO = openssl_decrypt($_POST['precio'], COD, KEY);

            } else {
                $mensaje .= "UPss....precio incorrecto<br/>";
                break;
            }

            // Agregamos el producto al carrito.
            if (!isset($_SESSION['CARRITO'])) {

                $producto = array(
                    'ID' => $ID,
                    'NOMBRE' => $NOMBRE,
                    'CANTIDAD' => $CANTIDAD,
                    'PRECIO' => $PRECIO
                );

                $_SESSION['CARRITO'][0] = $producto;

                $mensaje = "Producto agregado al carrito";

            } else {

                $idProductos = array_column($_SESSION['CARRITO'], "ID");

                if (in_array($ID, $idProductos)) {

                    foreach ($_SESSION['CARRITO'] as $indice => $producto) {

                        if ($producto['ID'] == $ID) {

                            $_SESSION['CARRITO'][$indice]['CANTIDAD']++;

                            $mensaje = "Producto agregado al carrito";
                            break;
                        }
                    }

                } else {

                    $NumeroProductos = count($_SESSION['CARRITO']);

                    $producto = array(
                        'ID' => $ID,
                        'NOMBRE' => $NOMBRE,
                        'CANTIDAD' => $CANTIDAD,
                        'PRECIO' => $PRECIO
                    );

                    $_SESSION['CARRITO'][$NumeroProductos] = $producto;

                    $mensaje = "Producto agregado al carrito";
                }
            }

            break;

        case 'Eliminar':

            if (
                isset($_POST['id']) &&
                is_numeric(openssl_decrypt($_POST['id'], COD, KEY))
            ) {

                $ID = openssl_decrypt($_POST['id'], COD, KEY);

                if (isset($_SESSION['CARRITO'])) {

                    foreach ($_SESSION['CARRITO'] as $indice => $producto) {

                        if ($producto['ID'] == $ID) {

                            unset($_SESSION['CARRITO'][$indice]);

                            $_SESSION['CARRITO'] = array_values($_SESSION['CARRITO']);

                            echo "<script>alert('Elemento eliminado...');</script>";
                            break;
                        }
                    }
                }

            } else {
                $mensaje .= "Upss.... ID incorrecto para eliminar<br/>";
            }

            break;
    }
}

?>