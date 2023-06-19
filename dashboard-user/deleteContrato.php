<?php
require "../config/conexion.php";
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../index.php");
    return;
}
$id = $_GET["id"];


$statement = $conn->prepare("SELECT * FROM contrato WHERE id_contrato  = :id LIMIT 1");

$statement->execute([':id' => $id]);

if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
}


$conn->prepare("DELETE FROM contrato WHERE id_contrato = :id")->execute([':id' => $id]);
// Mensaje Flash
$_SESSION['flash_message'] = ["message" => "Contrato eliminado.", "type" => "danger"];
header("Location: contratos.php");
