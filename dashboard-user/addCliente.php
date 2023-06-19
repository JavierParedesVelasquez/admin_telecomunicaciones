<?php include 'partials/header.php' ?>
<?php
require "../config/conexion.php";

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
 

        $statement = $conn->prepare("INSERT INTO clientes (nombre, apellido, correo, telefono, direccion) VALUES (:nombre, :apellido, :correo ,:telefono, :direccion)"); // Prepara una consulta SQL para insertar los valores del formulario en la tabla "contacts".
        // VALIDAR ENTRADA DE LOS USUARIOS A LA BD
        // hemos puesto valores limpios, que no se pueden hacer inyecciones SQL, porque la funcion bindParam lo analiza, y le quita todas las cosas que le puedan hacer un ataque a la DB
        // Basicamente las inyecciones SQL desde el usuario ya no funcionan
        // Moraleja: nunca pongas en la base de datos lo que te manda un usuario, y tienes que validar siempre los datos
        $statement->bindParam(":nombre", $_POST["nombre"]);
        $statement->bindParam(":apellido", $_POST["apellido"]);
        $statement->bindParam(":correo", $_POST["correo"]);
        $statement->bindParam(":telefono", $_POST["telefono"]);
        $statement->bindParam(":direccion", $_POST["direccion"]);
        $statement->execute(); // Ejecuta la consulta SQL para insertar los valores en la base de datos.



        // Después de procesar la operación CRUD

        $_SESSION['flash_message'] = ["message" => "Cliente {$_POST['nombre']} agregado.", "type" => "primary"];
        header('Location: clientes.php');
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
                    <a class="btn btn-primary" href="clientes.php"><i class='bx bxs-left-arrow-circle'></i> Regresar</a>
                </p>
            </div>
            <!-- /.container-fluid -->
            <div class="container">
                <div class="row">
                    <div class="container pt-5">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header"><i class="bx bxs-user-rectangle"></i> Agregar Cliente</div>
                                    <div class="card-body">
                                        <form method="POST" action="addCliente.php">
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre:</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresar nombre" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="apellido" class="form-label">Apellidos:</label>
                                                <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ingresar apellido" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="correo" class="form-label">Correo:</label>
                                                <input type="text" class="form-control" id="correo" name="correo" placeholder="Ingresar correo" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="telefono" class="form-label">Teléfono:</label>
                                                <input type="number" class="form-control" id="telefono" name="telefono" placeholder="Ingresar teléfono" autocomplete="off" required maxlength="8" pattern="\d{8}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="direccion" class="form-label">Dirección:</label>
                                                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ingresar direccion" autocomplete="off">
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