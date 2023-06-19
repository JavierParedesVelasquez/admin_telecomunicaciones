<?php
require "../config/conexion.php";
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: ../index.php");
    return;
}
$id = $_GET["id"];

$statement = $conn->prepare("SELECT nombre_usuario FROM usuarios WHERE id_usuario = :id LIMIT 1");

$statement->execute([':id' => $id]);

$usuario = $statement->fetch(PDO::FETCH_ASSOC);

$nombre_usuario = $usuario['nombre_usuario'];

$statement = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario  = :id LIMIT 1");

$statement->execute([':id' => $id]);

if ($statement->rowCount() == 0) {
    http_response_code(404);
    echo ("HTTP 404 NOT FOUND");
    return;
}


$conn->prepare("DELETE FROM usuarios WHERE id_usuario = :id")->execute([':id' => $id]);
// Mensaje Flash
$_SESSION['flash_message'] = ["message" => "Usuario $nombre_usuario eliminado.", "type" => "danger"];
header("Location: usuarios.php");
