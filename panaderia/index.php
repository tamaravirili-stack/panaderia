<?php include("template/cabecera.php"); ?>
<div class="container my-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h1 class="display-2 fw-bold">EL BUEN PAN</h1>
        </div>
        <div class="col-md-6">
            <img src="./imagen/cafeteria.png"
                class="img-fluid rounded-4 shadow-lg"
                alt="Banner PANADERIA">
        </div>
    </div>
    <div class="row text-center mt-5">
        <div class="col-12">
            <p class="fs-4 mb-4">LOS DESAYUNOS MÁS RICOS, LOS ENCONTRAS ACÁ.</p>
            <a class="btn btn-dark btn-lg rounded-pill px-5" href="productos.php">Ver catálogo</a>
        </div>
    </div>
</div>
<div class="container pb-5">
    <h2 class="text-center mb-5 fw-bold">Productos Destacados</h2>
    <div class="row g-4">
        <!-- Producto 1 -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <img src="./imagen/café2.png"
                    class="card-img-top rounded-top-3"
                    alt="CAFÉ CON LECHE"
                    style="height: 200px; object-fit: cover;">
                <div class="card-body text-center">
                    <h4 class="card-title">Café con leche</h4>
                    <p class="card-text text-muted">CAFÉ IMPORTADO | 100% NATURAL</p>
                    <p class="fs-4 fw-bold text-dark">$ 1.500</p>
                </div>
            </div>
        </div>
        <!-- Producto 2 -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <img src="./imagen/facturas.png"
                    class="card-img-top rounded-top-3"
                    alt="Facturas Surtidas"
                    style="height: 200px; object-fit: cover;">
                <div class="card-body text-center">
                    <h4 class="card-title">Facturas</h4>
                    <p class="card-text text-muted">FACTURAS SURTIDAS ( DOCENA ) | 100% CASERAS</p>
                    <p class="fs-4 fw-bold text-dark">$ 5.000</p>
                </div>
            </div>
        </div>
        <!-- Producto 3 -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <img src="./imagen/pan.png"
                    class="card-img-top rounded-top-3"
                    alt="Pan Artesanal"
                    style="height: 200px; object-fit: cover;">
                <div class="card-body text-center">
                    <h4 class="card-title">Pan de campo</h4>
                    <p class="card-text text-muted">PAN DE MASA MADRE | 100% CALIDAD</p>
                    <p class="fs-4 fw-bold text-dark">$ 2.800</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include("template/pie.php"); ?>