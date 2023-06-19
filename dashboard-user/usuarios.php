<?php include 'partials/header.php' ?>
<?php $resultado = $conn->query("SELECT * FROM usuarios"); ?>
<!-- Page Wrapper -->
<div id="wrapper">

    <?php include 'partials/sidebar.php' ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <?php include 'partials/topbar.php' ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <?php if (isset($_SESSION['flash_message'])) : ?>
                    <?php $flashMessage = $_SESSION['flash_message']; ?>
                    <?php $alertType = $flashMessage['type']; ?>
                    <div class="alert alert-<?php echo $alertType; ?>" role="alert">
                        <?php echo $flashMessage['message']; ?>
                    </div>
                    <?php unset($_SESSION['flash_message']); ?>
                <?php endif; ?>
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 d-flex align-items-center gap-2 m-0"><i class="bx bx-spreadsheet"></i> Listado de Usuarios</h1>
                </div>
                <p>
                    <a href="addUsuarios.php" class="text-decoration-none">
                        <button class="btn btn-primary d-flex align-items-center gap-1"><i class="bx bxs-plus-circle"></i> Agregar Usuario</button>
                    </a>
                </p>

            </div>
            <!-- /.container-fluid -->
            <div class="container-fluid">
                <div class="row">
                    <div class="table-responsive shadow">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center" scope="col">Nombre</th>
                                    <th class="text-center" scope="col">Usuario</th>
                                    <th class="text-center" scope="col">Contraseña</th>
                                    <th class="text-center" scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($resultado as $elements) : ?>
                                    <!-- Every time I go through that data I want to show what is in the database -->
                                    <tr>
                                        <td class="text-center"><?= $elements["nombre_usuario"] ?></td>
                                        <td class="text-center"><?= $elements["user_usuario"] ?></td>
                                        <td class="text-center"><?= $elements["contrasena_usuario"] ?></td>
                                        <td class="d-grid gap-2 sin_border_bottom">
                                            <a href="editUsuario.php?id=<?= $elements["id_usuario"] ?>" class="btn btn-warning btn-sm"><i class='bx bxs-edit-alt'></i> Editar</a>
                                            <a href="deleteUsuario.php?id=<?= $elements["id_usuario"] ?>" class="btn btn-danger btn-sm"><i class='bx bx-trash'></i> Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <!-- End of Main Content -->

        <?php include 'partials/footer.php' ?>