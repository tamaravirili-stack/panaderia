<?php
include_once("carrito.php");
include_once("template/cabecera.php");
?>

<div class="container">

    <!-- Buscador de productos -->
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 col-lg-6">
            <label for="buscadorProductos" class="form-label fw-bold">
                Buscar productos
            </label>

            <input
                type="text"
                id="buscadorProductos"
                class="form-control form-control-lg rounded-pill"
                placeholder="Escribí el nombre de un producto...">
        </div>
    </div>

    <?php
    $sqlCategorias = "SELECT DISTINCT categoria FROM productos";
    $resultadoCategorias = $conexion->query($sqlCategorias);

    while ($categoria = $resultadoCategorias->fetch(PDO::FETCH_ASSOC)) {
    ?>

        <h2 class="text-center mb-4">
            <?php echo htmlspecialchars($categoria["categoria"], ENT_QUOTES, 'UTF-8'); ?>
        </h2>

        <?php
        $categoriaActual = $categoria["categoria"];

        $sentenciaSQL = $conexion->prepare(
            "SELECT id, nombre, precio, categoria, imagen
            FROM productos
            WHERE categoria = ?"
        );

        $sentenciaSQL->execute([$categoriaActual]);
        $resultadoProductos = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="row g-4 mb-5">

            <?php foreach ($resultadoProductos as $producto) { ?>

                <div class="col-md-4 producto-item"
                    data-nombre="<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES, 'UTF-8'); ?>">

                    <!-- Producto -->
                    <div class="card h-100 border-0 shadow-sm">

                        <img
                            class="card-img-top"
                            src="./imagen/<?php echo htmlspecialchars($producto["imagen"], ENT_QUOTES, 'UTF-8'); ?>"
                            alt="<?php echo htmlspecialchars($producto["nombre"], ENT_QUOTES, 'UTF-8'); ?>"
                            style="height: 200px; object-fit: cover;">

                        <div class="card-body text-center">

                            <h4 class="card-title">
                                <?php echo htmlspecialchars($producto["nombre"], ENT_QUOTES, 'UTF-8'); ?>
                            </h4>

                            <p class="fs-4 fw-bold text-dark">
                                $ <?php echo number_format(
                                        $producto["precio"],
                                        0,
                                        ",",
                                        "."
                                    ); ?>
                            </p>

                            <form action="" method="post">

                                <input type="hidden" name="id"
                                    value="<?php echo openssl_encrypt($producto['id'], COD, KEY); ?>">

                                <input type="hidden" name="nombre"
                                    value="<?php echo openssl_encrypt($producto['nombre'], COD, KEY); ?>">

                                <input type="hidden" name="cantidad"
                                    value="<?php echo openssl_encrypt(1, COD, KEY); ?>">

                                <input type="hidden" name="precio"
                                    value="<?php echo openssl_encrypt($producto['precio'], COD, KEY); ?>">

                                <button
                                    class="btn btn-primary"
                                    type="submit"
                                    name="btnAccion"
                                    value="Agregar">
                                    Agregar al carrito
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

    <?php } ?>

    <p id="sinResultados" class="alert alert-info text-center" style="display: none;">
        No se encontraron productos con ese nombre.
    </p>

    <?php if (!empty($mensaje)) { ?>

        <div class="alert alert-success alert-dismissible fade show mt-4"
            role="alert">

            <?php echo $mensaje; ?>

            <a href="mostrar_carrito.php"
                class="badge bg-primary text-decoration-none ms-2">
                Ver carrito
            </a>

            <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Cerrar">
            </button>

        </div>

    <?php } ?>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const buscador = document.getElementById("buscadorProductos");
        const productos = document.querySelectorAll(".producto-item");
        const sinResultados = document.getElementById("sinResultados");
        const titulosCategorias = document.querySelectorAll("h2.text-center");

        buscador.addEventListener("input", function() {

            const texto = buscador.value.toLowerCase().trim();
            let encontrados = 0;

            productos.forEach(function(producto) {

                const nombre = producto.dataset.nombre.toLowerCase();
                const coincide = nombre.includes(texto);

                producto.style.display = coincide ? "" : "none";

                if (coincide) {
                    encontrados++;
                }
            });

            sinResultados.style.display =
                encontrados === 0 ? "block" : "none";

            titulosCategorias.forEach(function(titulo) {

                const contenedor = titulo.nextElementSibling;

                if (!contenedor || !contenedor.classList.contains("row")) {
                    return;
                }

                const productosVisibles =
                    contenedor.querySelectorAll(".producto-item:not([style*='display: none'])");

                titulo.style.display =
                    productosVisibles.length > 0 ? "" : "none";
            });

        });

    });
</script>

<?php include("template/pie.php"); ?>