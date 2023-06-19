<?php include 'partials/header.php' ?>
<?php
require "../config/conexion.php";

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


        $statement = $conn->prepare("INSERT INTO servicio (nombre_servicio, descripcion, precio, duracion, requisitos, responsable) VALUES (:nombre, :descripcion, :precio, :duracion, :requisitos, :responsable)");
         // Prepara una consulta SQL para insertar los valores del formulario en la tabla "contacts".
        // VALIDAR ENTRADA DE LOS USUARIOS A LA BD
        // hemos puesto valores limpios, que no se pueden hacer inyecciones SQL, porque la funcion bindParam lo analiza, y le quita todas las cosas que le puedan hacer un ataque a la DB
        // Basicamente las inyecciones SQL desde el usuario ya no funcionan
        // Moraleja: nunca pongas en la base de datos lo que te manda un usuario, y tienes que validar siempre los datos
        $statement->bindParam(":nombre", $_POST["nombre"]);
        $statement->bindParam(":descripcion", $_POST["descripcion"]);
        $statement->bindParam(":precio", $_POST["precio"]);
        $statement->bindParam(":duracion", $_POST["duracion"]);
        $statement->bindParam(":requisitos", $_POST["requisitos"]); 
        $statement->bindParam(":responsable", $_POST["responsable"]);
        $statement->execute(); // Ejecuta la consulta SQL para insertar los valores en la base de datos.



        // Después de procesar la operación CRUD

        $_SESSION['flash_message'] = ["message" => "Servicio {$_POST['nombre']} agregado.", "type" => "primary"];
        header('Location: servicios.php');
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
                    <a class="btn btn-primary" href="servicios.php"><i class='bx bxs-left-arrow-circle'></i> Regresar</a>
                </p>
            </div>
            <!-- /.container-fluid -->
            <div class="container">
                <div class="row">
                    <div class="container pt-5">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header"><i class="bx bxs-user-rectangle"></i> Agregar Servicio</div>
                                    <div class="card-body">
                                        <form method="POST" action="addServicio.php">
                                            <div class="mb-3">
                                                <label for="nombre" class="form-label">Nombre:</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresar nombre" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="descripcion" class="form-label">Descripción:</label>
                                                <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Ingresar descripcion" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="precio" class="form-label">Precio:</label>
                                                <input type="number" step="0.01" class="form-control" id="precio" name="precio" placeholder="Ingresar precio" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="duracion" class="form-label">Duración:</label>
                                                <input type="text" class="form-control" id="duracion" name="duracion" placeholder="Ingresar duracion" autocomplete="off">
                                            </div>
                                            <div class="mb-3">
                                                <label for="requisitos" class="form-label">Requisitos:</label>
                                                <input type="text" class="form-control" id="requisitos" name="requisitos" placeholder="Ingresar requisitos" autocomplete="off">
                                            </div>
                                            <div class="mb-3">
                                                <label for="responsable" class="form-label">Responsable:</label>
                                                <input type="text" class="form-control" id="responsable" name="responsable" placeholder="Ingresar responsable" autocomplete="off">
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