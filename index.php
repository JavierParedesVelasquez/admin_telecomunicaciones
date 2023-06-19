<?php include 'partials/header.php' ?>
<div class="container">
    <div class="row gap-lg-0 gap-md-4 gap-sm-4 gap-4 mx-3">
        <div class="col-lg-6 col-md-12 col-sm-12 div_left text-center">
            <img src="static/img/banner_login.svg" class="img-fluid" alt="Banner Login">
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 div_right">
            <form class="form_login" action="index.php" method="POST">
                <div class="text-center mb-3">
                    <span><i class='bx bxs-user-circle'></i></span>
                    <h2 class="text-center">Bienvenido</h2>
                </div>
                <?php if ($error != null) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $error ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif ?>
                <div class="mb-3">
                    <label for="usuario" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingresar Usuario" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ingresar Contraseña" required>
                </div>
                <div class="text-center mb-5">
                    <button type="submit" class="btn btn-primary">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include 'partials/footer.php' ?>