<?php
    require __DIR__ . '/../postsql.php';

    $mensaje = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $id = $_POST["ID_Equipo"];
        $nombre = $_POST["Nombre"];
        $bandera = $_POST["Bandera"];
        $grupo = strtoupper($_POST["Grupo"]);
        
        $query = "SELECT COUNT(*) AS total FROM Equipo WHERE Grupo = '$grupo'";
        $result = pg_query($conn, $query) or die('La query fallo: ' .pg_last_error($conn));
        $fila = pg_fetch_assoc($result); 
        $total = $fila["total"];
        if($total < 4){
            $query = "UPDATE Equipo SET Nombre = '$nombre', Bandera = '$bandera', Grupo = '$grupo' WHERE ID_Equipo = $id";
            $result = pg_query($conn, $query) or die('La query fallo: ' .pg_last_error($conn));
            echo "El equipo fue editado exitosamente";
        } else {
            echo "El grupo $grupo ya esta lleno";
        }
    }
        pg_close($conn);
    

?>

<html>
  <head>
    <link rel = "stylesheet" href = "../style.css">
     <title>
         Equipos - Editar
     </title>
  </head>
  <body>
    <h1>Editar equipo</h1>
    <?php
        echo $mensaje;
    ?>

    <form method = "post">
        <?php
            $id = $_GET["ID_Equipo"];
            $nombre = $_GET["Nombre"];
            $bandera = $_GET["Bandera"];
            $grupo = strtoupper($_GET["Grupo"]);
            echo "<b>ID del equipo: </b>$id<br>\n";
            echo "<input type = hidden name = ID_Equipo value = $id required><br>";

            echo "<b>Nombre del equipo</b>\n";
            echo "<input type = text name = Nombre value = $nombre required><br>\n";

            echo "<b>Bandera</b>\n";
            echo "<input type = text name = Bandera value = $bandera required><br>\n";
        
            echo "<b>Grupo</b>\n";
            echo "<input type = text name = Grupo maxlength = 1 
        pattern = [A-La-l] style = text-transform: uppercase; value = $grupo required><br>";
        ?>
        

        <button type = "submit">
                Enviar
            </button>

    </form>
    <center>
         <a href="listado.php"> Listado de equipos </a><br>
         <a href="agregar.php"> Agregar equipo </a><br>
         <a href="../index.php"> Menu Principal </a>
     </center>
</body>
</html>