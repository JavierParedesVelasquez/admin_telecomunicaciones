<?php
require "../config/conexion.php";
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../index.php");
    return;
}
$id = $_GET["id"];

$statement = $conn->prepare("SELECT nombre FROM clientes WHERE id = :id LIMIT 1");

$statement->execute([':id' => $id]);

$cliente = $statement->fetch(PDO::FETCH_ASSOC);

$nombre_cliente = $cliente['nombre'];

$statement = $conn->prepare("SELECT * FROM clientes WHERE id  = :id LIMIT 1");

$statement->execute([':id' => $id]);

if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
}


$conn->prepare("DELETE FROM clientes WHERE id = :id")->execute([':id' => $id]);
// Mensaje Flash
$_SESSION['flash_message'] = ["message" => "Cliente $nombre_cliente eliminado.", "type" => "danger"];
header("Location: clientes.php");
