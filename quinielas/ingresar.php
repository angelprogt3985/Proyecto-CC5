<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';

    if($_SESSION["admin"]){
        header("Location: ../login.php");
        exit;
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        $id_partido = $_POST["partido"];
        $id_usuario = $_SESSION["id"];

        $pred_gol1 = $_POST["goles1"];
        $pred_gol2 = $_POST["goles2"];
        $puntos_obt = 0;

        $query = "INSERT INTO Prediccion
                (ID_Pred, Id_Partido, ID_usuario, pred_gol1, pred_gol2, puntos_obt)
                VALUES
                ((SELECT COALESCE(MAX(ID_Pred),0) + 1 FROM Prediccion), 
                $id_partido, $id_usuario, $pred_gol1, $pred_gol2, $puntos_obt)";

        $result = pg_query($conn, $query);

        if($result){
            echo "Predicción guardada";
        } else {
            echo "Error al guardar";
        }

        pg_close($conn);

    }

    $queryP = "SELECT P.Id_Partido, E1.Nombre AS ELocal, E2.Nombre AS EVisitante
        FROM Partido P
        JOIN Equipo E1 ON P.ID_equipo1 = E1.ID_equipo
        JOIN Equipo E2 ON P.ID_equipo2 = E2.ID_equipo
        ORDER BY P.Fecha";
        

    $resultP = pg_query($conn, $queryP);
    $options = "";
    while ($fila = pg_fetch_assoc($resultP)) {
        $options .= "<option value='{$fila["id_partido"]}'>";
        $options .= trim($fila["elocal"]) . " vs " . trim($fila["evisitante"]);
        $options .= "</option>";
    }
    


?>

<html>             
  <head>
    <link rel = "stylesheet" href = "../style.css">
    <meta charset="UTF-8">
     <title>
         Quiniela - Insertar
     </title>
  </head>
  <body>
    <h1>Agregar equipo</h1>
    <?php
    ?>

    <form method = "post" autocomplete = "off">
        <b> Partido </b>
        <select name="partido">
            <?php echo $options; ?>
        </select><br>

        <b>Goles equipo Local</b>
        <input type = "text" name = "goles1" required><br>

        <b>Goles equipo Visitante</b>
        <input type = "text" name = "goles2" required><br>

        

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

