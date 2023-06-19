<?php
require "../config/conexion.php";
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../index.php");
    return;
}
$id = $_GET["id"];

$statement = $conn->prepare("SELECT * FROM factura WHERE id  = :id LIMIT 1");

$statement->execute([':id' => $id]);

if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
}

$conn->prepare("DELETE FROM factura_servicios WHERE factura_id = :factura_id")->execute([':factura_id' => $id]);
$conn->prepare("DELETE FROM factura WHERE id = :id")->execute([':id' => $id]);
// Mensaje Flash
$_SESSION['flash_message'] = ["message" => "Factura eliminada.", "type" => "danger"];
header("Location: facturas.php");
