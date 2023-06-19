<?php include 'partials/header.php' ?>
<?php
$statement = $conn->query("SELECT * FROM compras");
$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
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
                    <h1 class="h3 d-flex align-items-center gap-2 m-0"><i class="bx bx-spreadsheet"></i> Listado de compras</h1>
                </div>
                <p>
                    <a href="addCompra.php" class="text-decoration-none">
                        <button class="btn btn-primary d-flex align-items-center gap-1"><i class="bx bxs-plus-circle"></i> Agregar Compra</button>
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
                                    <th class="text-center" scope="col">Fecha</th>
                                    <th class="text-center" scope="col">Cliente</th>
                                    <th class="text-center" scope="col">Productos</th>
                                    <th class="text-center" scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($resultado as $elements) : ?>
                                    <!-- Every time I go through that data I want to show what is in the database -->
                                    <tr>
                                        <td class="text-center"><?= $elements["fecha"] ?></td>
                                        <td class="text-center"><?= $elements["cliente_comprador"] ?></td>

                                        <td class="text-center">
                                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalProductos1<?= $elements['id'] ?>">Ver Productos</button>
                                        </td>

                                        <td class="d-grid gap-2 sin_border_bottom">
                                            <a href="deleteCompra.php?id=<?= $elements["id"] ?>" class="btn btn-danger btn-sm"><i class='bx bx-trash'></i> Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Modal de productos -->
            <?php
            foreach ($resultado as $elements) : ?>
                <div class="modal fade" id="modalProductos1<?= $elements['id'] ?>" tabindex="-1" aria-labelledby="modalProductosLabel1<?= $elements['id'] ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalProductosLabel1<?= $elements['id'] ?>">Productos de la Compra <?= $elements['id'] ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="row font-weight-bold">
                                            <div class="col-6">Producto</div>
                                            <div class="col-4">Descripción</div>
                                            <div class="col-2 text-end">Precio</div>
                                        </div>
                                    </li>
                                    <?php
                                    $stmt = $conn->prepare("SELECT * FROM productos WHERE compra_id = ?");
                                    $stmt->execute([$elements['id']]);
                                    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    // Calcular la suma total de precios
                                    $totalPrecio = 0;
                                    foreach ($productos as $producto) {
                                        $totalPrecio += $producto['precio'];
                                    }
                                    ?>
                                    <?php foreach ($productos as $producto) : ?>
                                        <li class="list-group-item">
                                            <div class="row">
                                                <div class="col-6"><?= $producto['nombre'] ?></div>
                                                <div class="col-4"><?= $producto['descripcion'] ?></div>
                                                <div class="col-2 text-end"><?= $producto['precio'] ?></div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-10 text-end">Total:</div>
                                            <div class="col-2 text-end"><?= $totalPrecio ?></div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach ?>
        </div>
        <!-- End of Main Content -->

        <?php include 'partials/footer.php' ?>