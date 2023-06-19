<?php
require "../config/conexion.php";
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../index.php");
    return;
}
$id = $_GET["id"];

$statement = $conn->prepare("SELECT nombre_servicio FROM servicio WHERE id = :id LIMIT 1");

$statement->execute([':id' => $id]);

$servicio = $statement->fetch(PDO::FETCH_ASSOC);

$nombre_servicio = $servicio['nombre_servicio'];

$statement = $conn->prepare("SELECT * FROM servicio WHERE id  = :id LIMIT 1");

$statement->execute([':id' => $id]);

if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
}


$conn->prepare("DELETE FROM servicio WHERE id = :id")->execute([':id' => $id]);
// Mensaje Flash
$_SESSION['flash_message'] = ["message" => "Servicio $nombre_servicio eliminado.", "type" => "danger"];
header("Location: servicios.php");
