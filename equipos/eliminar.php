<?php
    require __DIR__ . '/../postsql.php';

    $mensaje = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $accion = $_POST["decision"];
        if ($accion == "si"){
            $id = $_POST["ID_Equipo"];
            $nombre = $_POST["Nombre"];
            $bandera = $_POST["Bandera"];
            $grupo = strtoupper($_POST["Grupo"]);
            
            $query = "DELETE FROM Equipo WHERE ID_Equipo = $id";
            $result = pg_query($conn, $query);

            if (!$result){
                echo "Error: No se puede eliminar el equipo porque tiene partidos asociados";
            } else {
                echo "Equipo eliminado correctamente";
            }
        } else {
            header("Location: listado.php");
            exit;
        }

        
    }
        pg_close($conn);
    

?>

<html>
  <head>
    <link rel = "stylesheet" href = "../style.css">
     <title>
         Equipos - Eliminar
     </title>
  </head>
  <body>
    <h1>Eliminar equipo</h1>
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

            echo "<b>Nombre del equipo: </b>$nombre<br>\n";
            echo "<input type = hidden name = Nombre value = $nombre required><br>\n";

            echo "<b>Bandera: </b>$bandera<br>\n";
            echo "<input type = hidden name = Bandera value = $bandera required><br>\n";
        
            echo "<b>Grupo: </b>$grupo<br>\n";
            echo "<input type = hidden name = Grupo maxlength = 1 
        pattern = [A-La-l] style = text-transform: uppercase; value = $grupo required><br>";
        ?>
        <h4>¿Quieres eliminar el equipo?</h4>
        <button type = "submit" name = "decision" value = "si">
                Si
            </button>

            <button type = "submit" name = "decision" value = "no">
                No
            </button>

    </form>
    <center>
         <a href="listado.php"> Listado de equipos </a><br>
         <a href="agregar.php"> Agregar equipo </a><br>
         <a href="../index.php"> Menu Principal </a>
     </center>
</body>
</html>
