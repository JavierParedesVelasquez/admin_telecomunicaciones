<?php
include 'partials/header.php';
require "../config/conexion.php";

$error = null;
$flashMessage = null;

// Obtener los clientes desde la base de datos
$stmtClientes = $conn->prepare("SELECT id, nombre, apellido FROM clientes");
$stmtClientes->execute();
$clientes = $stmtClientes->fetchAll();

$stmtServicios = $conn->prepare("SELECT id, nombre_servicio, descripcion, precio, duracion, requisitos, responsable FROM servicio");
$stmtServicios->execute();
$servicios = $stmtServicios->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["ruc"]) || empty($_POST["cliente"]) || empty($_POST["fecha"]) || empty($_POST["descripcion"])) {
        $error = "Por favor complete todos los campos.";
    } else {
        $n_factura = "FACT-" . date("Y") . "-" . uniqid();
        $ruc = $_POST["ruc"];
        $cliente = $_POST["cliente"];
        $fecha = $_POST["fecha"];
        $descripcion = $_POST["descripcion"];

        try {
            // Insertar la factura en la tabla de facturas
            $stmt = $conn->prepare("INSERT INTO factura (numero_factura, fecha_emision, cliente_id, descripcion,numero_ruc) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$n_factura, $fecha, $cliente, $descripcion, $ruc]);
            $facturaId = $conn->lastInsertId();

            // Calcular subtotal
            $subtotal = 0;
            foreach ($_POST["servicio"] as $servicioId) {
                // Obtener el precio del servicio desde la base de datos
                $stmtServicio = $conn->prepare("SELECT precio FROM servicio WHERE id = ?");
                $stmtServicio->execute([$servicioId]);
                $servicio = $stmtServicio->fetch();
                $subtotal += $servicio["precio"];

                // Insertar el servicio en la factura
                $stmt = $conn->prepare("INSERT INTO factura_servicios (factura_id, servicio_id) VALUES (?, ?)");
                $stmt->execute([$facturaId, $servicioId]);
            }

            // Obtener los porcentajes de impuestos y descuentos desde la base de datos
            $stmtPorcentajes = $conn->prepare("SELECT impuestos, descuentos FROM configuracion WHERE id = 1");
            $stmtPorcentajes->execute();
            $porcentajes = $stmtPorcentajes->fetch();

            $impuestosPorcentaje = $porcentajes["impuestos"];
            $descuentosPorcentaje = $porcentajes["descuentos"];

            // Calcular impuestos y descuentos basados en los porcentajes obtenidos
            $impuestos = $subtotal * ($impuestosPorcentaje / 100);
            $descuentos = $subtotal * ($descuentosPorcentaje / 100);

            // Calcular el total
            $total = $subtotal + $impuestos - $descuentos;

            // Actualizar los campos subtotal, impuestos y descuentos en la tabla de facturas
            $stmtTotal = $conn->prepare("UPDATE factura SET subtotal = ?, impuestos = ?, descuentos = ? WHERE id = ?");
            $stmtTotal->execute([$subtotal, $impuestos, $descuentos, $facturaId]);

            // Actualizar el total en la tabla de facturas
            $stmtTotal = $conn->prepare("UPDATE factura SET total = ? WHERE id = ?");
            $stmtTotal->execute([$total, $facturaId]);

            $flashMessage = "La factura se ha guardado correctamente.";
        } catch (PDOException $e) {
            $error = "Error al guardar la factura: " . $e->getMessage();
        }
    }
}

// Mostrar mensaje flash o error en la página
if ($flashMessage) {
    $_SESSION['flash_message'] = ["message" => $flashMessage, "type" => "success"];
    header('Location: facturas.php');
    exit;
} elseif ($error) {
    $_SESSION['flash_message'] = ["message" => $error, "type" => "danger"];
}
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
                <p>
                    <a class="btn btn-primary" href="facturas.php"><i class='bx bxs-left-arrow-circle'></i> Regresar</a>
                </p>
            </div>
            <style>
                .producto {
                    border: 1px solid #dee2e6;
                    padding: 10px;
                    border-radius: 5px;
                    margin-bottom: 10px;
                }
            </style>
            <!-- /.container-fluid -->
            <div class="container">
                <div class="row">
                    <div class="container pt-5">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header"><i class="bx bxs-user-rectangle"></i> Agregar Factura </div>
                                    <div class="card-body">
                                        <form method="POST" action="addFactura.php">
                                            <div class="col-12 d-flex">
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="ruc" class="form-label">N° RUC:</label>
                                                        <input type="text" class="form-control" id="ruc" name="ruc" value="20601597498" autocomplete="off" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="cliente" class="form-label">Cliente:</label>
                                                        <select class="form-select" id="cliente" name="cliente" required>
                                                            <option selected disabled>Selecciona un Cliente:</option>
                                                            <?php foreach ($clientes as $cliente) : ?>
                                                                <option value="<?= $cliente['id'] ?>"><?= $cliente['nombre'] ?> <?= $cliente['apellido'] ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="fecha" class="form-label">Fecha:</label>
                                                        <input type="datetime-local" class="form-control" id="fecha" name="fecha" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="descripcion" class="form-label">Descripción:</label>
                                                        <textarea rows="3" cols="30" class="form-control" id="descripcion" name="descripcion"></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="d-flex justify-content-between py-3">
                                                <h3>Servicios</h3>
                                                <a href="addServicio.php" class="btn btn-success d-flex align-items-center gap-1"><i class='bx bxs-plus-circle'></i>
                                                    Crear Servicio
                                                </a>
                                                <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" onclick="agregarServicio()"><i class='bx bxs-plus-circle'></i> Agregar Servicio</button>
                                            </div>
                                            <div id="servicios-container">
                                                <div class="producto">
                                                    <div class="row">
                                                        <div class="col">
                                                            <label for="servicio1" class="form-label">Servicio:</label>
                                                            <select class="form-select" name="servicio[]" required>
                                                                <option selected disabled>Selecciona un Servicio:</option>
                                                                <?php foreach ($servicios as $servicio) : ?>
                                                                    <option value="<?= $servicio['id'] ?>"><?= $servicio['nombre_servicio'] ?> - <?= $servicio['descripcion'] ?> - <?= $servicio['precio'] ?> - <?= $servicio['duracion'] ?> - <?= $servicio['requisitos'] ?> - <?= $servicio['responsable'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-primary d-flex align-items-center gap-1"><i class='bx bxs-plus-circle'></i> Agregar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- End of Main Content -->
        <?php include 'partials/footer.php' ?>
        <script>
            function agregarServicio() {
                const serviciosContainer = document.getElementById("servicios-container");

                const nuevoServicio = document.createElement("div");
                nuevoServicio.className = "producto";

                nuevoServicio.innerHTML = `
            <div class="row">
                <div class="col">
                    <label for="servicio" class="form-label">Servicio:</label>
                    <select class="form-select" name="servicio[]" required>
                        <option selected disabled>Selecciona un Servicio:</option>
                        <?php foreach ($servicios as $servicio) : ?>
                            <option value="<?= $servicio['id'] ?>"><?= $servicio['nombre_servicio'] ?> - <?= $servicio['descripcion'] ?> - <?= $servicio['precio'] ?> - <?= $servicio['duracion'] ?> - <?= $servicio['requisitos'] ?> - <?= $servicio['responsable'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        `;

                serviciosContainer.appendChild(nuevoServicio);
            }
        </script>