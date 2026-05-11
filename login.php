<?php
    session_start();
    if(isset($_SESSION["id"])){
        header("Location: index.php");
        exit;
    }
    require __DIR__ . '/../postsql.php';

    $mensaje = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $correo = $_POST["Correo"];
        $contrasena = $_POST["Contrasena"];

        $query = "SELECT * FROM Usuario WHERE Correo = '$correo' AND TRIM(Contraseña) = '$contrasena'";
        $result = pg_query($conn, $query) or die ('La query fallo: ' .pg_last_error($conn));
        if(pg_num_rows($result)){
            echo "Error: Las credenciales son incorrectas";
        } else {
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
            
        }
        pg_close($conn);
    }
?>


<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method = "post">
<!--Temporalmente inician con correo y contraseña-->
        <b>Correo</b>
        <input type = "text" name = "Correo" required><br>

        <b>Contraseña</b>
        <input type = "text" name = "Contraseña" required><br>

        <button type = "submit">
                Enviar
            </button>

    </form>

    <center>
         <a href="register.php"> Registrarse </a><br>
     </center>
</body>
</html>