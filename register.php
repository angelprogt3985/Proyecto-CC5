<?php
    session_start();
    require __DIR__ . '/postsql.php';

    $mensaje = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nombre = $_POST["Nombre"];
        $correo = $_POST["Correo"];
        $contrasena = $_POST["Contrasena"];

        
        $query = "INSERT INTO Usuario VALUES (
            (SELECT MAX(ID_usuario) + 1 FROM Usuario), 
            '$nombre', '$correo', '$contrasena')";

        $result = pg_query($conn, $query) or die('La query fallo: ' .pg_last_error($conn));
            
        $query = "SELECT * FROM Usuario WHERE Correo = '$correo' AND TRIM(Contraseña) = '$contrasena'";
        $result = pg_query($conn, $query) or die ('La query fallo: ' .pg_last_error($conn));
        
        $usuario = pg_fetch_assoc($result);
        $_SESSION["id"] = $usuario["id_usuario"];
        $_SESSION["nombre"] = trim($usuario["nombre"]);
        if($usuario["id_usuario"] == 0){
            $_SESSION["admin"] = true;
        } else {
            $_SESSION["admin"] = false;
        }
        header("Location: index.php");
        exit;
        
        pg_close($conn);
    }
?>

<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Registrarse</h1>
    <?php
        echo $mensaje;
    ?>
    <form method = "post" autocomplete = "off">

        <b>Nombre de usuario</b>
        <input type = "text" name = "Nombre" required><br>

        <b>Correo</b>
        <input type = "text" name = "Correo" required><br>

        <b>Contraseña</b>
        <input type = "password" name = "Contrasena" required><br>

        <button type = "submit">
                Enviar
            </button>

    </form>

    <center>
         <a href="login.php"> Iniciar sesion </a><br>
     </center>
</body>
</html>