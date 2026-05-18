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

        $queryFecha = "SELECT fecha, hora
               FROM Partido
               WHERE id_partido = $id_partido";

        $resultadoFecha = pg_query($conn, $queryFecha);
        $partido = pg_fetch_assoc($resultadoFecha);
        $fechaHoraPartido = strtotime($partido["fecha"] . " " . $partido["hora"]);
        $limite = $fechaHoraPartido - (5 * 60);
        $ahora = time();

        if($ahora >= $limite){
            die("Ya no puedes agregar una predicción porque faltan menos de 5 minutos para el partido.");
        }

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

        

    }

    $queryP = "SELECT P.Id_Partido, P.Fecha, P.Hora,
        E1.Nombre AS ELocal, 
        E2.Nombre AS EVisitante
    FROM Partido P
    JOIN Equipo E1 ON P.ID_equipo1 = E1.ID_equipo
    JOIN Equipo E2 ON P.ID_equipo2 = E2.ID_equipo
    WHERE NOT EXISTS (
        SELECT * FROM Prediccion PR
        WHERE PR.Id_Partido = P.Id_Partido
        AND PR.ID_usuario = {$_SESSION['id']}
    )
    ORDER BY P.Fecha";
        

    $resultP = pg_query($conn, $queryP);
    $options = "";
    while ($fila = pg_fetch_assoc($resultP)) {
        $fechaHoraPartido = strtotime($fila["fecha"] . " " . $fila["hora"]);
        $limite = $fechaHoraPartido - (5 * 60);
        $ahora = time();

        if($ahora >= $limite){
            continue;
        }

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
         <a href="listado.php"> Ver Quinielas </a><br>
         <a href="../index.php"> Menu Principal </a>
     </center>
</body>
</html>

