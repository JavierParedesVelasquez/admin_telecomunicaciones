<?php include 'partials/header.php' ?>
<?php
require "../config/conexion.php";

$id = $_GET["id"];
$statement = $conn->prepare("SELECT * FROM factura WHERE id = :id LIMIT 1");
$statement->execute([':id' => $id]);
if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
}

$factura = $statement->fetch(PDO::FETCH_ASSOC);

// Consulta para obtener los clientes
$clientesQuery = $conn->query("SELECT * FROM clientes");
$clientes = $clientesQuery->fetchAll(PDO::FETCH_ASSOC);



$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["ruc"]) || empty($_POST["cliente"]) || empty($_POST["fecha"]) || empty($_POST["descripcion"])) {
        $error = "Por favor llene todos los campos.";
    } else {
        $ruc = $_POST["ruc"];
        $cliente = $_POST["cliente"];
        $fecha = $_POST["fecha"];
        $descripcion = $_POST["descripcion"];
    

        $statement = $conn->prepare("UPDATE factura SET fecha_emision = :fecha, 
                                                          cliente_id  = :cliente,
                                                          descripcion = :descripcion,
                                                          numero_ruc = :ruc
                                                           WHERE id = :id");
        $statement->execute([
            ":id" => $id,
            ":ruc" => $_POST["ruc"],
            ":cliente" => $_POST["cliente"],
            ":fecha" => $_POST["fecha"],
            ":descripcion" => $_POST["descripcion"],


        ]);
        $_SESSION["flash_message"] = ["message" => "Factura editada.", "type" => "warning"];
        header('Location: facturas.php');
        return;
    }
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
                    <a href="facturas.php" class="text-decoration-none">
                        <button class="btn btn-primary d-flex align-items-center gap-1"><i class='bx bxs-left-arrow-circle'></i> Regresar</button>
                    </a>
                </p>
            </div>
            <!-- /.container-fluid -->
            <div class="container">
                <div class="row">
                    <div class="container pt-5">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header"><i class='bx bx-edit-alt'></i> Editar Factura</div>
                                    <div class="card-body">
                                        <form method="POST" action="editFactura.php?id=<?= $factura["id"] ?>">
                                            <div class="col-12 d-flex">
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="ruc" class="form-label">N° RUC:</label>
                                                        <input type="text" class="form-control" id="ruc" name="ruc" value="<?= $factura['numero_ruc']; ?>" autocomplete="off" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="cliente" class="form-label">Cliente:</label>
                                                        <select class="form-select" id="cliente" name="cliente" required>
                                                            <option selected disabled>Selecciona un Cliente:</option>
                                                            <?php foreach ($clientes as $cliente) : ?>
                                                                <option value="<?= $cliente['id'] ?>" <?= ($cliente['id'] == $factura['cliente_id']) ? 'selected' : '' ?>>
                                                                    <?= $cliente['nombre'] ?> <?= $cliente['apellido'] ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="mb-3">
                                                        <label for="fecha" class="form-label">Fecha:</label>
                                                        <input value="<?= $factura['fecha_emision']; ?>" type="date" class="form-control" id="fecha" name="fecha" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="descripcion" class="form-label">Descripción:</label>
                                                        <textarea rows="3" cols="30" class="form-control" id="descripcion" name="descripcion"><?= $factura['descripcion']; ?></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn btn-primary d-flex align-items-center gap-1"><i class='bx bxs-edit-alt'></i> Editar</button>
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