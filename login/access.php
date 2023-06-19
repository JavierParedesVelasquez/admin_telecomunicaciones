<?php
// Incluye el archivo de configuración de la conexión a la base de datos
require "config/conexion.php";

// Variable para almacenar mensajes de error
$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["usuario"]) || empty($_POST["password"])) {
        $error = "Por favor llene todos los campos.";
    } else {
        $statement = $conn->prepare("SELECT * FROM usuarios WHERE user_usuario = :usuario LIMIT 1");
        $statement->bindParam(":usuario", $_POST["usuario"]);
        $statement->execute();

        if ($statement->rowCount() == 0) {
            $error = "Credenciales no válidas.";
        } else {
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            if (md5($_POST["password"]) !== $user["contrasena_usuario"]) {
                $error = "Credenciales no válidas.";    
            } else {
                session_start();
                $_SESSION["user"] = $user;
                
                // Redirecciona siempre a "dashboard-user"
                header("Location: dashboard-user/");
                exit(); // Asegura que el script se detenga después de la redirección
            }
        }
    }
}
?>