<?php include 'partials/header.php' ?>
<?php
require "../config/conexion.php";

$id = $_GET["id"];
$statement = $conn->prepare("SELECT * FROM contrato WHERE id_contrato = :id LIMIT 1");
$statement->execute([':id' => $id]);
if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
}

$contrato = $statement->fetch(PDO::FETCH_ASSOC);

// Consulta para obtener los clientes
$clientesQuery = $conn->query("SELECT * FROM clientes");
$clientes = $clientesQuery->fetchAll(PDO::FETCH_ASSOC);

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["cliente"]) || empty($_POST["fecha_inicio"]) || empty($_POST["fecha_final"]) || empty($_POST["descripcion"]) || empty($_POST["precio"])) {
        $error = "Por favor llene todos los campos.";
    } else {
        $cliente = $_POST["cliente"];
        $fecha_inicio = $_POST["fecha_inicio"];
        $fecha_final = $_POST["fecha_final"];
        $descripcion = $_POST["descripcion"];
        $precio = $_POST["precio"];

        $statement = $conn->prepare("UPDATE contrato SET cliente_id = :cliente, 
        fecha_inicio_contrato = :fecha_inicio,
        fecha_fin_contrato = :fecha_final,
        descripcion_contrato = :descripcion,
        precio_contrato = :precio
        WHERE id_contrato = :id");
        $statement->execute([
            ":id" => $id,
            ":cliente" => $_POST["cliente"],
            ":fecha_inicio" => $_POST["fecha_inicio"],
            ":fecha_final" => $_POST["fecha_final"],
            ":descripcion" => $_POST["descripcion"],
            ":precio" => $_POST["precio"],
        ]);
        $_SESSION["flash_message"] = ["message" => "Contrato editado.", "type" => "warning"];
        header('Location: contratos.php');
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
                    <a href="contratos.php" class="text-decoration-none">
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
                                    <div class="card-header"><i class='bx bx-edit-alt'></i> Editar Contrato</div>
                                    <div class="card-body">
                                        <form method="POST" action="editContrato.php?id=<?= $contrato["id_contrato"] ?>">
                                            <div class="mb-3">
                                                <label for="cliente" class="form-label">Cliente:</label>
                                                <select class="form-select" id="cliente" name="cliente" required>
                                                    <option selected disabled>Selecciona un Cliente:</option>
                                                    <?php foreach ($clientes as $cliente) : ?>
                                                        <option value="<?= $cliente['id'] ?>" <?= ($cliente['id'] == $contrato['cliente_id']) ? 'selected' : '' ?>>
                                                            <?= $cliente['nombre'] ?> <?= $cliente['apellido'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="fecha_inicio" class="form-label">Fecha Inicio Contrato:</label>
                                                <input type="date" class="form-control" id="fecha_inicio" value="<?= $contrato['fecha_inicio_contrato']; ?>" name="fecha_inicio" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="fecha_final" class="form-label">Fecha Fin Contrato:</label>
                                                <input type="date" class="form-control" id="fecha_final" value="<?= $contrato['fecha_fin_contrato']; ?>" name="fecha_final" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="descripcion" class="form-label">Descripción:</label>
                                                <textarea rows="3" cols="30" class="form-control" id="descripcion" name="descripcion"><?= $contrato['descripcion_contrato']; ?></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="precio" class="form-label">Precio:</label>
                                                <input type="number" step="0.01" value="<?= $contrato['precio_contrato']; ?>" class="form-control" id="precio" name="precio" placeholder="Ingresar Precio del Contrato" autocomplete="off" required>
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