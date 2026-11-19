
<?php
include_once("admin/config/bd.php");
include_once("carrito.php");
include_once("template_carrito/cabecera.php");
?>

<h3>Lista del carrito</h3>

<div class="col-md-12">

    <?php if (!empty($_SESSION['CARRITO'])) { ?>

        <table class="table table-light">
            <thead>
                <tr>
                    <th width="40%">Descripción</th>
                    <th width="15%" class="text-center">Cantidad</th>
                    <th width="20%" class="text-center">Precio</th>
                    <th width="20%" class="text-center">Total</th>
                    <th width="5%">Acción</th>
                </tr>
            </thead>

            <tbody>

                <?php $total = 0; ?>

                <?php foreach ($_SESSION['CARRITO'] as $indice => $producto) { ?>

                    <tr>
                        <td width="40%">
                            <?php echo htmlspecialchars($producto['NOMBRE']); ?>
                        </td>

                        <td width="15%" class="text-center">
                            <?php echo $producto['CANTIDAD']; ?>
                        </td>

                        <td width="20%" class="text-center">
                            $<?php echo number_format($producto['PRECIO'], 2, ',', '.'); ?>
                        </td>

                        <td width="20%" class="text-center">
                            $<?php
                            echo number_format(
                                $producto['PRECIO'] * $producto['CANTIDAD'],
                                2,
                                ',',
                                '.'
                            );
                            ?>
                        </td>

                        <td width="5%">
                            <form action="" method="post">

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?php echo openssl_encrypt($producto['ID'], COD, KEY); ?>"
                                >

                                <button
                                    class="btn btn-danger"
                                    type="submit"
                                    name="btnAccion"
                                    value="Eliminar"
                                >
                                    Eliminar
                                </button>

                            </form>
                        </td>
                    </tr>

                    <?php
                    $total += $producto['PRECIO'] * $producto['CANTIDAD'];
                    ?>

                <?php } ?>

                <tr>
                    <td colspan="3" class="text-end">
                        <h3>Total</h3>
                    </td>

                    <td class="text-end">
                        <h3>$<?php echo number_format($total, 2, ',', '.'); ?></h3>
                    </td>

                    <td></td>
                </tr>

                <tr>
                    <td colspan="5">

                        <form action="pagar.php" method="post">

                            <div class="alert alert-success" role="alert">

                                <div class="form-group">
                                    <label for="email">Correo de contacto</label>

                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control"
                                        placeholder="Por favor, escribí tu email"
                                        required
                                    >
                                </div>

                                <small class="form-text text-muted">
                                    La lista de productos se enviará a este correo.
                                </small>

                            </div>

                            <button
                                class="btn btn-primary btn-lg w-100"
                                type="submit"
                                name="btnAccion"
                                value="Proceder"
                            >
                                Proceder a pagar
                            </button>

                        </form>

                    </td>
                </tr>

            </tbody>
        </table>

    <?php } else { ?>

        <div class="alert alert-success" role="alert">
            No hay productos en el carrito.
        </div>

    <?php } ?>

</div>

<?php include_once("template_carrito/pie.php"); ?>
