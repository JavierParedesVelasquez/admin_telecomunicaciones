<?php
include 'partials/header.php';
require "../config/conexion.php";

$error = null;
$flashMessage = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["fecha"]) || empty($_POST["comprador"])) {
        $error = "Por favor complete todos los campos.";
    } else {
        $fecha = $_POST["fecha"];
        $comprador = $_POST["comprador"];
        
        $nombresProductos = $_POST["nombre"];
        $descripcionesProductos = $_POST["descripcion"];
        $preciosProductos = $_POST["precio"];

        try {
            // Insertar la compra en la tabla de compras
            $stmt = $conn->prepare("INSERT INTO compras (fecha, cliente_comprador) VALUES (?, ?)");
            $stmt->execute([$fecha, $comprador]); // Corregir el nombre de la variable aquí
            $compraId = $conn->lastInsertId();

            // Insertar los productos de la compra en la tabla productos_compra
            $stmt = $conn->prepare("INSERT INTO productos (compra_id, nombre, descripcion, precio) VALUES (?, ?, ?, ?)");
            for ($i = 0; $i < count($nombresProductos); $i++) {
                $nombreProducto = $nombresProductos[$i];
                $descripcionProducto = $descripcionesProductos[$i];
                $precioProducto = $preciosProductos[$i];

                $stmt->execute([$compraId, $nombreProducto, $descripcionProducto, $precioProducto]);
            }

            $flashMessage = "La compra se ha guardado correctamente.";
        } catch (PDOException $e) {
            $error = "Error al guardar la compra: " . $e->getMessage();
        }
    }
}

// Mostrar mensaje flash o error en la página
if ($flashMessage) {
    $_SESSION['flash_message'] = ["message" => $flashMessage, "type" => "success"];
    header('Location: compras.php');
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
                    <a class="btn btn-primary" href="compras.php"><i class='bx bxs-left-arrow-circle'></i> Regresar</a>
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
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header"><i class="bx bxs-user-rectangle"></i> Agregar Compra</div>
                                    <div class="card-body">
                                        <form method="POST" action="addCompra.php">
                                            <div class="mb-3">
                                                <label for="fecha" class="form-label">Fecha:</label>
                                                <input type="date" class="form-control" id="fecha" name="fecha" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="comprador" class="form-label">Comprador:</label>
                                                <input type="text" class="form-control" id="comprador" name="comprador" required>
                                            </div>
                                            <div class="d-flex justify-content-between py-3">
                                                <h3>Productos</h3>
                                                <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-1" onclick="agregarProducto()"><i class='bx bxs-plus-circle'></i> Agregar Producto</button>
                                            </div>
                                            <div id="productos-container">
                                                <div class="producto">
                                                    <div class="row">
                                                        <div class="col">
                                                            <label for="nombre1" class="form-label">Nombre:</label>
                                                            <input type="text" class="form-control" id="nombre1" name="nombre[]" required>
                                                        </div>
                                                        <div class="col">
                                                            <label for="descripcion1" class="form-label">Descripción:</label>
                                                            <input type="text" class="form-control" id="descripcion1" name="descripcion[]" required>
                                                        </div>
                                                        <div class="col">
                                                            <label for="precio1" class="form-label">Precio:</label>
                                                            <input type="number" step="0.01" class="form-control" id="precio1" name="precio[]" required>
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
            function agregarProducto() {
                const productosContainer = document.getElementById("productos-container");

                const nuevoProducto = document.createElement("div");
                nuevoProducto.className = "producto";

                nuevoProducto.innerHTML = `
        <div class="row">
          <div class="col">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" class="form-control" name="nombre[]" required>
          </div>
          <div class="col">
            <label for="descripcion" class="form-label">Descripción:</label>
            <input type="text" class="form-control" name="descripcion[]" required>
          </div>
          <div class="col">
            <label for="precio" class="form-label">Precio:</label>
            <input type="number" step="0.01" class="form-control" name="precio[]" required>
          </div>
        </div>
      `;

                productosContainer.appendChild(nuevoProducto);
            }
        </script>