<?php include 'partials/header.php' ?>
<?php
require "../config/conexion.php";

$id = $_GET["id"];
$statement = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = :id LIMIT 1");
$statement->execute([':id' => $id]);
if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
}

$usuario = $statement->fetch(PDO::FETCH_ASSOC);

$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["nombre"]) || empty($_POST["usuario"]) || empty($_POST["password"])) {
        $error = "Por favor llene todos los campos.";
    } else {
        $nombre = $_POST["nombre"];
        $usuario = $_POST["usuario"];
        $password = md5($_POST["password"]);

        $statement = $conn->prepare("UPDATE usuarios SET nombre_usuario = :nombre, 
                                                          user_usuario = :usuario,
                                                          contrasena_usuario = :password
                                                           WHERE id_usuario = :id");
        $statement->execute([
            ":id" => $id,
            ":nombre" => $_POST["nombre"],
            ":usuario" => $_POST["usuario"],
            ":password" => md5($_POST["password"]),
        ]);
        $_SESSION["flash_message"] = ["message" => "Usuario {$_POST['nombre']} editado.", "type" => "warning"];
        header('Location: usuarios.php');
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
                    <a href="usuarios.php" class="text-decoration-none">
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
                                    <div class="card-header"><i class='bx bx-edit-alt'></i> Editar Usuarios</div>
                                    <div class="card-body">
                                        <form method="POST" action="editUsuario.php?id=<?= $usuario["id_usuario"] ?>">
                                        <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre:</label>
                                                <input value="<?= $usuario["nombre_usuario"] ?>" type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresar Nombre" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="usuario" class="form-label">Usuario:</label>
                                                <input value="<?= $usuario["user_usuario"] ?>" type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingresar Usuario" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Contraseña:</label>
                                                <input value="<?= $usuario["contrasena_usuario"] ?>" type="password" class="form-control" id="password" name="password" placeholder="Ingresar Contraseña" autocomplete="off" required>
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