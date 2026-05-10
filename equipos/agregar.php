<?php
    require __DIR__ . '/../postsql.php';

    $mensaje = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $id = $_POST["ID_Equipo"];
        $nombre = $_POST["Nombre"];
        $bandera = $_POST["Bandera"];
        $grupo = strtoupper($_POST["Grupo"]);
        
        
        $check = pg_query($conn, "SELECT * FROM Equipos WHERE ID_Equipo = $id");
        if(pg_num_rows($check) > 0){
            echo"Error: El equipo con el id $id ya existe. Ingrese otro equipo";
        } else {
            $query = "INSERT INTO Equipo VALUES ($id, '$nombre', '$bandera', '$grupo')";
            $result = pg_query($conn, $query) or die('La query fallo: ' .pg_last_error($conn));
            echo "El equipo fue insertado exitosamente";
        }
        pg_close($conn);
    }

?>

<html>
  <head>
    <link rel = "stylesheet" href = "../style.css">
     <title>
         Equipos - Insertar
     </title>
  </head>
  <body>
    <h1>Agregar equipo</h1>
    <?php
        echo $mensaje;
    ?>

    <form method = "post">
        <b>ID del equipo</b>
        <input type = "number" name = "ID_Equipo" required><br>

        <b>Nombre del equipo</b>
        <input type = "text" name = "Nombre" required><br>

        <b>Bandera</b>
        <input type = "text" name = "Bandera" required><br>

        <b>Grupo</b>
        <input type = "text" name = "Grupo" maxlength = "1" 
        pattern = "[A-La-l]" style = "text-transform: uppercase;" required><br>

        <button type = "submit">
                Enviar
            </button>

    </form>
    <center>
         <a href="listado.php"> Listado de equipos </a><br>
         <a href="../index.php"> Menu Principal </a>
     </center>
</body>
</html>