<?php include 'partials/header.php' ?>
<?php
require "../config/conexion.php";

$error = null;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["nombre"]) || empty($_POST["usuario"]) || empty($_POST["password"])) {
        $error = "Por favor llene todos los campos.";
    } else {
        $nombre = $_POST["nombre"];
        $usuario = $_POST["usuario"];
        $password = md5($_POST["password"]);
        
        $statement = $conn->prepare("INSERT INTO usuarios (nombre_usuario, user_usuario, contrasena_usuario) VALUES (:nombre, :usuario, :password)"); // Prepara una consulta SQL para insertar los valores del formulario en la tabla "contacts".
        // VALIDAR ENTRADA DE LOS USUARIOS A LA BD
        // hemos puesto valores limpios, que no se pueden hacer inyecciones SQL, porque la funcion bindParam lo analiza, y le quita todas las cosas que le puedan hacer un ataque a la DB
        // Basicamente las inyecciones SQL desde el usuario ya no funcionan
        // Moraleja: nunca pongas en la base de datos lo que te manda un usuario, y tienes que validar siempre los datos
        $statement->bindParam(":nombre", $_POST["nombre"]);
        $statement->bindParam(":usuario", $_POST["usuario"]);
        $statement->bindParam(":password", md5($_POST["password"]));
        $statement->execute(); // Ejecuta la consulta SQL para insertar los valores en la base de datos.

    

        // Después de procesar la operación CRUD
       
        $_SESSION['flash_message'] = ["message"=>"Usuario {$_POST['nombre']} agregado.", "type" => "primary"];
        header('Location: usuarios.php');
        return; //esto hace que funcione el flash message
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
                    <a class="btn btn-primary" href="usuarios.php"><i class='bx bxs-left-arrow-circle'></i> Regresar</a>
                </p>
            </div>
            <!-- /.container-fluid -->
            <div class="container">
                <div class="row">
                    <div class="container pt-5">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header"><i class="bx bxs-user"></i> Agregar Usuario</div>
                                    <div class="card-body">
                                        <form method="POST" action="addUsuarios.php">
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre:</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresar Nombre" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="usuario" class="form-label">Usuario:</label>
                                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Ingresar Usuario" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Contraseña:</label>
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Ingresar Contraseña" autocomplete="off" required>
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