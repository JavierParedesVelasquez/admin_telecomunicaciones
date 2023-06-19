<?php include 'partials/header.php' ?>
<?php
require "../config/conexion.php";

$id = $_GET["id"];
$statement = $conn->prepare("SELECT * FROM servicio WHERE id = :id LIMIT 1");
$statement->execute([':id' => $id]);
if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
}

$servicio = $statement->fetch(PDO::FETCH_ASSOC);

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["nombre"]) || empty($_POST["descripcion"]) || empty($_POST["precio"]) || empty($_POST["duracion"]) || empty($_POST["requisitos"]) || empty($_POST["responsable"])) {
        $error = "Por favor llene todos los campos.";
    } else {
        $nombre = $_POST["nombre"];
        $descripcion = $_POST["descripcion"];
        $precio = $_POST["precio"];
        $duracion = $_POST["duracion"];
        $requisitos = $_POST["requisitos"];
        $responsable = $_POST["responsable"];

        $statement = $conn->prepare("UPDATE servicio SET nombre_servicio = :nombre, 
                                                          descripcion = :descripcion,
                                                          precio = :precio,
                                                          duracion = :duracion,
                                                          requisitos = :requisitos,
                                                          responsable = :responsable
                                                           WHERE id = :id");
        $statement->execute([
            ":id" => $id,
            ":nombre" => $_POST["nombre"],
            ":descripcion" => $_POST["descripcion"],
            ":precio" => $_POST["precio"],
            ":duracion" => $_POST["duracion"],
            ":requisitos" => $_POST["requisitos"],
            ":responsable" => $_POST["responsable"],

        ]);
        $_SESSION["flash_message"] = ["message" => "Servicio {$_POST['nombre']} editado.", "type" => "warning"];
        header('Location: servicios.php');
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
                    <a href="servicios.php" class="text-decoration-none">
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
                                    <div class="card-header"><i class='bx bx-edit-alt'></i> Editar Servicios</div>
                                    <div class="card-body">
                                        <form method="POST" action="editServicio.php?id=<?= $servicio["id"] ?>">
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre:</label>
                                                <input value="<?= $servicio["nombre_servicio"] ?>" type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresar nombre" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="descripcion" class="form-label">Descripción:</label>
                                                <input value="<?= $servicio["descripcion"] ?>" type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Ingresar descripcion" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="precio" class="form-label">Precio:</label>
                                                <input value="<?= $servicio["precio"] ?>" type="number" step="0.01" class="form-control" id="precio" name="precio" placeholder="Ingresar precio" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="duracion" class="form-label">Duración:</label>
                                                <input value="<?= $servicio["duracion"] ?>" type="text" class="form-control" id="duracion" name="duracion" placeholder="Ingresar duracion" autocomplete="off">
                                            </div>
                                            <div class="mb-3">
                                                <label for="requisitos" class="form-label">Requisitos:</label>
                                                <input value="<?= $servicio["requisitos"] ?>" type="text" class="form-control" id="requisitos" name="requisitos" placeholder="Ingresar requisitos" autocomplete="off">
                                            </div>
                                            <div class="mb-3">
                                                <label for="responsable" class="form-label">Responsable:</label>
                                                <input value="<?= $servicio["responsable"] ?>" type="text" class="form-control" id="responsable" name="responsable" placeholder="Ingresar responsable" autocomplete="off">
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