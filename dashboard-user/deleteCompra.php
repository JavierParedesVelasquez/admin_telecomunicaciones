<?php
require "../config/conexion.php";
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../index.php");
    return;
}
$id = $_GET["id"];

$statement = $conn->prepare("SELECT * FROM compras WHERE id  = :id LIMIT 1");

$statement->execute([':id' => $id]);

if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
}

$conn->prepare("DELETE FROM productos WHERE compra_id = :compra_id")->execute([':compra_id' => $id]);
$conn->prepare("DELETE FROM compras WHERE id = :id")->execute([':id' => $id]);
// Mensaje Flash
$_SESSION['flash_message'] = ["message" => "Compra eliminado.", "type" => "danger"];
header("Location: compras.php");
