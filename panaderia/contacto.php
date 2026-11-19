<?php include("template/cabecera.php"); ?>

<div class="container my-5">
    <h1 class="text-center mb-5 fw-bold">¡Contactanos!</h1>
    <div class="row g-5">
        <div class="col-md-6">
            <div class="p-4 bg-light rounded-4 shadow-sm">
                <h3 class="mb-4">Envíanos un mensaje</h3>
                <?php include("send.php"); ?>
                <form method="post" autocomplete="off">
                    <div class="mb-3">
                        <input type="text" name="name" class="form-control rounded-pill p-3" placeholder="Nombre">
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control rounded-pill p-3" placeholder="Correo electrónico" required>
                    </div>
                    <div class="mb-3">
                        <textarea name="mensaje" class="form-control rounded-4 p-3" rows="4" placeholder="Mensaje" required></textarea>
                    </div>
                    <div class="d-grid">
                        <button name="send" type="submit" class="btn btn-dark btn-lg rounded-pill">Enviar mensaje</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 bg-light rounded-4 shadow-sm h-100">
                <h3 class="mb-4">Información de Contacto</h3>
                <div class="d-flex align-items-center mb-3">
                    <i class="fa-solid fa-circle-info fs-3 me-3 text-primary"></i>
                    <span>Dirección de la Panadería</span>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <i class="fa-solid fa-phone fs-3 me-3 text-primary"></i>
                    <span>+54 9 3775 47-3803</span>
                </div>
                <div class="d-flex align-items-center mb-4">
                    <i class="fa-solid fa-envelope fs-3 me-3 text-primary"></i>
                    <span>contacto@pansusu.com</span>
                </div>
                <hr>
                <h4 class="mt-4 mb-3">Redes Sociales</h4>

              <div class="d-flex gap-4">
    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" class="text-dark fs-2">
        <i class="fa-brands fa-instagram"></i>
    </a>

    <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" class="text-dark fs-2">
        <i class="fa-brands fa-facebook"></i>
    </a>

    <a href="https://wa.me/543775473803" target="_blank" rel="noopener noreferrer" class="text-dark fs-2">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
</div>
            </div>

        </div>
    </div>
</div>
<?php include("template/pie.php"); ?>