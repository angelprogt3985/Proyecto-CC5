<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';
    $id = $_SESSION["id"];
    if($_SERVER["REQUEST_METHOD"] == "POST" && !$_SESSION["admin"]){
        foreach($_POST["predGolL"] as $id_partido => $predL){
             $predV = $_POST["predGolV"][$id_partido];

            $queryFecha = "SELECT fecha, hora 
                        FROM Partido 
                        WHERE id_partido = $id_partido";

            $resultadoFecha = pg_query($conn, $queryFecha);

            $partido = pg_fetch_assoc($resultadoFecha);

            $fechaHoraPartido = strtotime($partido["fecha"] . " " . $partido["hora"]);

            $limite = $fechaHoraPartido - (5 * 60);

            $ahora = time();

            if($ahora >= $limite){
                echo "La predicción para el partido $id_partido ya no puede editarse.<br>";
                continue;
            }

            $query = "UPDATE Prediccion 
                    SET pred_gol1 = $predL, pred_gol2 = $predV
                    WHERE ID_Partido = $id_partido 
                    AND ID_usuario = $id";

            pg_query($conn, $query) or die('La query fallo: ' . pg_last_error($conn));
        }
    }



?>
<html>  
  <head>
   <link rel = "stylesheet" href = "../style.css">
   <meta charset="UTF-8">
     <title>
        Quinielas - Listado
     </title>
  </head>
  <body>

  <form method = "post">
<?php
    $id = $_SESSION["id"];
    if ($_SESSION["admin"]){
        $query = "SELECT pr.ID_usuario, pr.id_pred, p.id_partido, p.fecha, p.hora, pr.pred_gol1, pr.pred_gol2, pr.puntos_obt, 
            p.goles_equip1, p.goles_equip2, E1.nombre AS E_Local, 
            E2.nombre AS E_visitante
            FROM Prediccion pr
            JOIN Partido p ON pr.id_partido = p.id_partido
            JOIN Equipo E1 ON p.id_equipo1 = E1.id_equipo
            JOIN Equipo E2 ON p.id_equipo2 = E2.id_equipo
            WHERE pr.ID_usuario <> 0
            ORDER BY pr.ID_usuario";

        $result = pg_query($conn, $query) or die('La query fallo: ' .pg_last_error($conn));
        if(pg_num_rows($result) == 0){
            echo "No hay quinielas";
        } else {
            $nombreL = "";
            $nombreV = "";

            $predGolL = 0; // Local
            $predGolV = 0; // Visitante

            $golesL = 0;
            $golesV = 0;

            $idPred = 0;
            $idUsuario = 0;

            $puntos = 0;
            echo "<div style='text-align:center;'>";
            echo "<table border = 1 style = 'margin:auto;'>\n";
            echo "\t<tr>\n";
            echo "\t\t<th><b>Usuario</b></th>\n";
            echo "\t\t<th><b>Partido</b></th>\n";
            echo "\t\t<th>Resultado</th>\n";
            echo "\t\t<th>Prediccion</th>\n";
            echo "\t\t<th>Puntos obtenidos</th>\n";
            echo "\t</tr>\n";

            while ($line = pg_fetch_assoc($result)) {

                $idPred = $line["id_pred"];
                $idPar = $line["id_partido"];

                $predGolL = $line["pred_gol1"];
                $predGolV = $line["pred_gol2"];

                if(!isset($predGolL)){
                    continue;
                }

                $idUsuario = $line["id_usuario"];
                $queryU = "SELECT Nombre FROM Usuario WHERE ID_usuario = $idUsuario";
                $resultU = pg_query($conn, $queryU);
                $fila = pg_fetch_assoc($resultU);
                $nombreU = $fila["nombre"];

                $nombreL = $line["e_local"];
                $nombreV = $line["e_visitante"];

                $golesL = $line["goles_equip1"];
                $golesV = $line["goles_equip2"];

                $puntos = $line["puntos_obt"];
                
                echo "\t<tr>\n";
                echo "\t\t<td>$nombreU</td>\n";
                echo "\t\t<td>$nombreL - $nombreV</td>\n";

                if(isset($golesL)){
                    echo "\t\t<td>$golesL - $golesV</td>\n";
                } else {
                    echo "\t\t<td>Por definirse</td>\n";
                }

                echo "\t\t<td>$predGolL - $predGolV</td>\n";

                if(isset($golesL)){
                    echo "\t\t<td>$puntos</td>\n";
                } else {
                    echo "\t\t<td>Por definirse</td>\n";
                }
                        
            }
            echo "</table>\n";
            echo "</div>";
        }
        
        
    } else {      
        $query = "SELECT pr.id_pred, p.id_partido, p.fecha, p.hora, pr.pred_gol1, pr.pred_gol2, pr.puntos_obt, 
        p.goles_equip1, p.goles_equip2, E1.nombre AS E_Local, 
        E2.nombre AS E_visitante
        FROM Prediccion pr
        JOIN Partido p ON pr.id_partido = p.id_partido
        JOIN Equipo E1 ON p.id_equipo1 = E1.id_equipo
        JOIN Equipo E2 ON p.id_equipo2 = E2.id_equipo
        WHERE pr.id_usuario = $id";

        $result = pg_query($conn, $query) or die('La query fallo: ' .pg_last_error($conn));
        if(pg_num_rows($result) == 0){
            echo "No hay quinielas";
        } else {
            $nombreL = "";
            $nombreV = "";

            $predGolL = 0; // Local
            $predGolV = 0; // Visitante

            $golesL = 0;
            $golesV = 0;

            $idPred = 0;

            $puntos = 0;
            echo "<div style='text-align:center;'>";
            echo "<table border = 1 style = 'margin:auto;'>\n";
            echo "\t<tr>\n";
            echo "\t\t<th><b>Partido</b></th>\n";
            echo "\t\t<th>Resultado</th>\n";
            echo "\t\t<th>Prediccion</th>\n";
            echo "\t\t<th>Puntos obtenidos</th>\n";
            echo "\t</tr>\n";

            while ($line = pg_fetch_assoc($result)) {

                $idPred = $line["id_pred"];
                $idPar = $line["id_partido"];

                $predGolL = $line["pred_gol1"];
                $predGolV = $line["pred_gol2"];
                if(!isset($predGolL)){
                    continue;
                }

                $nombreL = $line["e_local"];
                $nombreV = $line["e_visitante"];

                $golesL = $line["goles_equip1"];
                $golesV = $line["goles_equip2"];

                $puntos = $line["puntos_obt"];
                
                echo "\t<tr>\n";
                echo "\t\t<td>$nombreL - $nombreV</td>\n";

                if(isset($golesL)){
                    echo "\t\t<td>$golesL - $golesV</td>\n";
                } else {
                    echo "\t\t<td>Por definirse</td>\n";
                }

                echo "\t\t<td> <input type = 'number' name = 'predGolL[$idPar]' value = '$predGolL'> - <input type = 'number' name = 'predGolV[$idPar]' value = '$predGolV'></td>\n";
                    
                if(isset($golesL)){
                    echo "\t\t<td>$puntos</td>\n";
                } else {
                    echo "\t\t<td>Por definirse</td>\n";
                }
                    
                echo "\t\t<td><a href='eliminar.php?ID_Pred=$idPred&predGolL=$predGolL&predGolV=$predGolV&NombreL=$nombreL&NombreV=$nombreV'>
                        <button type = 'button' style='background:red;color:white;'> Eliminar Quiniela </button></a></td>\n";
                echo "\t</tr>\n";
                        
            }
            echo "</table>\n";
            echo "</div>";
        }
    }

    
?>
    <?php 
        if(!$_SESSION["admin"]){
            $query = "SELECT p.id_partido, p.fecha, p.hora, pr.pred_gol1, pr.pred_gol2, pr.puntos_obt, 
            p.goles_equip1, p.goles_equip2, E1.nombre AS E_Local, 
            E2.nombre AS E_visitante
            FROM Prediccion pr
            JOIN Partido p ON pr.id_partido = p.id_partido
            JOIN Equipo E1 ON p.id_equipo1 = E1.id_equipo
            JOIN Equipo E2 ON p.id_equipo2 = E2.id_equipo
            WHERE pr.id_usuario = $id";

            $result = pg_query($conn, $query) or die('La query fallo: ' .pg_last_error($conn));
            if(pg_num_rows($result) != 0){
                echo "<button type = submit> Actualizar predicciones </button>";
            }
        }
    ?>
</form>
    <center>
        <?php 
            if(!$_SESSION["admin"]){
                    echo "<a href=ingresar.php> Ingresar Prediccion </a><br>";
                }
        ?>
        
        <a href="../calendario/listado.php"> Ver Partidos </a><br>
        <a href="../equipos/listado.php"> Ver Equipos </a><br>
        <a href="../fases/listado_fase.php"> Ver Fases </a><br>
        <a href="resultados.php"> Ver posiciones de los usuarios </a><br>
        <a href="../index.php"> Menu Principal</a>
     </center>
  </body>
</html>