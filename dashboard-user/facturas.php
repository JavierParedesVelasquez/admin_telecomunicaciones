<?php include 'partials/header.php' ?>
<?php
$statement = $conn->query("SELECT factura.id, factura.numero_factura, factura.fecha_emision, clientes.nombre AS nombre_cliente, clientes.apellido AS apellido_cliente, factura.descripcion, factura.total, factura.numero_ruc, factura.subtotal, factura.impuestos, factura.descuentos
FROM factura
INNER JOIN clientes ON factura.cliente_id = clientes.id");

$facturas = $statement->fetchAll(PDO::FETCH_ASSOC);

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
                    <h1 class="h3 d-flex align-items-center gap-2 m-0"><i class="bx bx-spreadsheet"></i> Listado de Facturas</h1>
                </div>
                <p>
                    <a href="addFactura.php" class="text-decoration-none">
                        <button class="btn btn-primary d-flex align-items-center gap-1"><i class="bx bxs-plus-circle"></i> Agregar Factura</button>
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
                                    <th class="text-center" scope="col">Nr° Factura</th>
                                    <th class="text-center" scope="col">RUC</th>
                                    <th class="text-center" scope="col">Fecha de Emisión</th>
                                    <th class="text-center" scope="col">Cliente</th>
                                    <th class="text-center" scope="col">Descripción</th>
                                    <th class="text-center" scope="col">Subtotal</th>
                                    <th class="text-center" scope="col">Impuestos</th>
                                    <th class="text-center" scope="col">Descuentos</th>
                                    <th class="text-center" scope="col">Total</th>
                                    <th class="text-center" scope="col">Ver Servicios</th>
                                    <th class="text-center" scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($facturas as $elements) : ?>
                                    <!-- Every time I go through that data I want to show what is in the database -->
                                    <tr>
                                        <td class="text-center"><?= $elements["numero_factura"] ?></td>
                                        <td class="text-center"><?= $elements["numero_ruc"] ?></td>
                                        <td class="text-center"><?= $elements["fecha_emision"] ?></td>
                                        <td class="text-center"><?= $elements["nombre_cliente"] ?> <?= $elements["apellido_cliente"] ?></td>
                                        <td class="text-center"><?= $elements["descripcion"] ?></td>
                                        <td class="text-center"><?= $elements["subtotal"] ?></td>
                                        <td class="text-center"><?= $elements["impuestos"] ?></td>
                                        <td class="text-center"><?= $elements["descuentos"] ?></td>
                                        <td class="text-center"><?= $elements["total"] ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalFactura<?= $elements['id'] ?>">Ver Servicios</button>
                                        </td>
                                        <td class="d-grid gap-2 sin_border_bottom">
                                            <a href="editFactura.php?id=<?= $elements["id"] ?>" class="btn btn-warning btn-sm"><i class='bx bxs-edit-alt'></i> Editar</a>
                                            <a href="deleteFactura.php?id=<?= $elements["id"] ?>" class="btn btn-danger btn-sm"><i class='bx bx-trash'></i> Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal de facturas -->
        <?php
        foreach ($facturas as $factura) : ?>
            <div class="modal fade" id="modalFactura<?= $factura['id'] ?>" tabindex="-1" aria-labelledby="modalFacturaLabel<?= $factura['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalFacturaLabel<?= $factura['id'] ?>">Factura <?= $factura['id'] ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <div class="row font-weight-bold">
                                        <div class="col-6">Servicio</div>
                                        <div class="col-4">Descripción</div>
                                        <div class="col-2 text-end">Precio</div>
                                    </div>
                                </li>
                                <?php
                                $stmt = $conn->prepare("SELECT servicio.* FROM servicio JOIN factura_servicios ON servicio.id = factura_servicios.servicio_id WHERE factura_servicios.factura_id = ?");
                                $stmt->execute([$factura['id']]);
                                $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                // Calcular la suma total de precios de servicios
                                $totalPrecioServicios = 0;
                                foreach ($servicios as $servicio) {
                                    $totalPrecioServicios += $servicio['precio'];
                                }
                                ?>
                                <?php foreach ($servicios as $servicio) : ?>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-6"><?= $servicio['nombre_servicio'] ?></div>
                                            <div class="col-4"><?= $servicio['descripcion'] ?></div>
                                            <div class="col-2 text-end"><?= $servicio['precio'] ?></div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-10 text-end">Total:</div>
                                        <div class="col-2 text-end"><?= $totalPrecioServicios ?></div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach ?>

        <!-- End of Main Content -->

        <?php include 'partials/footer.php' ?>