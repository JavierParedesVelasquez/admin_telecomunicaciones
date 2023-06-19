<?php include 'partials/header.php' ?>
<?php
require "../config/conexion.php";

$id = $_GET["id"];
$statement = $conn->prepare("SELECT * FROM clientes WHERE id = :id LIMIT 1");
$statement->execute([':id' => $id]);
if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
}

$cliente = $statement->fetch(PDO::FETCH_ASSOC);

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["nombre"]) || empty($_POST["apellido"]) || empty($_POST["correo"]) || empty($_POST["telefono"]) || empty($_POST["direccion"])) {
        $error = "Por favor llene todos los campos.";
    } else {
        $nombre = $_POST["nombre"];
        $apellido = $_POST["apellido"];
        $correo = $_POST["correo"];
        $telefono = $_POST["telefono"];
        $direccion = $_POST["direccion"];

        $statement = $conn->prepare("UPDATE clientes SET nombre = :nombre, 
                                                          apellido = :apellido,
                                                          correo = :correo,
                                                          telefono = :telefono,
                                                          direccion = :direccion
                                                           WHERE id = :id");
        $statement->execute([
            ":id" => $id,
            ":nombre" => $_POST["nombre"],
            ":apellido" => $_POST["apellido"],
            ":correo" => $_POST["correo"],
            ":telefono" => $_POST["telefono"],
            ":direccion" => $_POST["direccion"],

        ]);
        $_SESSION["flash_message"] = ["message" => "Cliente {$_POST['nombre']} editado.", "type" => "warning"];
        header('Location: clientes.php');
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
                    <a href="clientes.php" class="text-decoration-none">
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
                                    <div class="card-header"><i class='bx bx-edit-alt'></i> Editar Clientes</div>
                                    <div class="card-body">
                                        <form method="POST" action="editCliente.php?id=<?= $cliente["id"] ?>">
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre:</label>
                                                <input value="<?= $cliente["nombre"] ?>" type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresar nombre" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="apellido" class="form-label">Apellidos:</label>
                                                <input value="<?= $cliente["apellido"] ?>" type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingresar apellido" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="correo" class="form-label">Correo:</label>
                                                <input value="<?= $cliente["correo"] ?>" type="text" class="form-control" id="correo" name="correo" placeholder="Ingresar correo" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="telefono" class="form-label">Teléfono:</label>
                                                <input value="<?= $cliente["telefono"] ?>" type="text " class="form-control" id="telefono" name="telefono" placeholder="Ingresar teléfono" autocomplete="off">
                                            </div>
                                            <div class="mb-3">
                                                <label for="direccion" class="form-label">Dirección:</label>
                                                <input value="<?= $cliente["direccion"] ?>" type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingresar direccion" autocomplete="off">
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