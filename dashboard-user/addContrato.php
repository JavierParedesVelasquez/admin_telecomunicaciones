<?php include 'partials/header.php' ?>
<?php
require "../config/conexion.php";

$error = null;

$statement = $conn->query("SELECT * FROM clientes");
$clientes = $statement->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["cliente"]) || empty($_POST["fecha_inicio"]) || empty($_POST["fecha_final"]) || empty($_POST["descripcion"]) || empty($_POST["precio"])) {
        $error = "Por favor llene todos los campos.";
    } else {
        $cliente = $_POST["cliente"];
        $fecha_inicio = $_POST["fecha_inicio"];
        $fecha_final = $_POST["fecha_final"];
        $descripcion = $_POST["descripcion"];
        $precio = $_POST["precio"];


        $statement = $conn->prepare("INSERT INTO contrato (cliente_id , fecha_inicio_contrato, fecha_fin_contrato, descripcion_contrato, precio_contrato) VALUES (:cliente, :fecha_inicio, :fecha_final ,:descripcion, :precio)"); // Prepara una consulta SQL para insertar los valores del formulario en la tabla "contacts".
        // VALIDAR ENTRADA DE LOS USUARIOS A LA BD
        // hemos puesto valores limpios, que no se pueden hacer inyecciones SQL, porque la funcion bindParam lo analiza, y le quita todas las cosas que le puedan hacer un ataque a la DB
        // Basicamente las inyecciones SQL desde el usuario ya no funcionan
        // Moraleja: nunca pongas en la base de datos lo que te manda un usuario, y tienes que validar siempre los datos
        $statement->bindParam(":cliente", $_POST["cliente"]);
        $statement->bindParam(":fecha_inicio", $_POST["fecha_inicio"]);
        $statement->bindParam(":fecha_final", $_POST["fecha_final"]);
        $statement->bindParam(":descripcion", $_POST["descripcion"]);
        $statement->bindParam(":precio", $_POST["precio"]);
        $statement->execute(); // Ejecuta la consulta SQL para insertar los valores en la base de datos.



        // Después de procesar la operación CRUD

        $_SESSION['flash_message'] = ["message" => "Contrato agregado.", "type" => "primary"];
        header('Location: contratos.php');
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
                    <a class="btn btn-primary" href="contratos.php"><i class='bx bxs-left-arrow-circle'></i> Regresar</a>
                </p>
            </div>
            <!-- /.container-fluid -->
            <div class="container">
                <div class="row">
                    <div class="container pt-5">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header"><i class='bx bxs-briefcase'></i> Agregar Contrato</div>
                                    <div class="card-body">
                                        <form method="POST" action="addContrato.php">
                                            <div class="mb-3">
                                                <label for="cliente" class="form-label">Cliente:</label>
                                                <select class="form-select" id="cliente" name="cliente" required>
                                                    <option selected disabled>Selecciona un Cliente:</option>
                                                    <?php foreach ($clientes as $cliente) : ?>
                                                        <option value="<?= $cliente['id'] ?>"><?= $cliente['nombre'] ?> <?= $cliente['apellido'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="fecha_inicio" class="form-label">Fecha Inicio Contrato:</label>
                                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="fecha_final" class="form-label">Fecha Fin Contrato:</label>
                                                <input type="date" class="form-control" id="fecha_final" name="fecha_final" autocomplete="off" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="descripcion" class="form-label">Descripción:</label>
                                                <textarea rows="3" cols="30" class="form-control" id="descripcion" name="descripcion"></textarea>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="precio" class="form-label">Precio:</label>
                                                <input type="number" step="0.01" class="form-control" id="precio" name="precio" placeholder="Ingresar Precio del Contrato" autocomplete="off" required>
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