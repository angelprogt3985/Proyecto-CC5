<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';
?>
<html>  
  <head>
   <link rel = "stylesheet" href = "../style.css">
     <title>
        Quinielas - Listado
     </title>
  </head>
  <body>

<?php
    $id = $_SESSION["id"];
    if ($_SESSION["admin"]){
        $query = "SELECT * FROM Prediccion ORDER BY ID_usuario";
        
    } else {      
        $query = "SELECT p.id_partido, p.fecha, p.hora, pr.pred_gol1, pr.pred_gol2, pr.puntos_obt, 
        p.goles_equip1, p.goles_equip2, F.nombre AS Fase, E1.nombre AS E_Local, 
        E2.nombre AS E_visitante
        FROM Prediccion pr
        JOIN Partido p ON pr.id_partido = p.id_partido
        JOIN Equipo E1 ON pr.id_equipo = E1.id_equipo
        JOIN Equipo E2 ON pr.id_equipo = E2.id_equipo
        WHERE pr.id_usuario = $id";

        $result = pg_query($conn, $query) or die('La query fallo: ' .pg_last_error($conn));
        if(pg_num_rows($result) == 0){
            echo "No hay quinielas";
        } else {
            $nombreL = "";
            $nombreV = "";

            $idPar = 0;
            $idPred = 0;
            $predGolL = 0; // Local
            $predGolV = 0; // Visitante
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

                $nombreL = $line["E_Local"];
                $nombreV = $line["E_Visitante"];
                
                $idPar = $line["id_partido"];

                $golesL = $line["goles_equip1"];
                $golesV = $line["goles_equip2"];

                $predGolL = $line["pred_gol1"];
                $predGolV = $line["pred_gol2"];

                $puntos = $line["puntos_obt"];
                
                    echo "\t<tr>\n";
                    echo "\t\t<td>$nombreL - $nombreV</td>\n";

                    if(isset($golesL)){
                        echo "\t\t<td>$golesL - $golesV</td>\n";
                    } else {
                        echo "Por definirse";
                    }
                    
                    echo "\t\t<td> <input type = 'number' name = 'predGolL' value = '$predGolL'> - <input type = 'number' name = 'predGolV' value = '$predGolV'></td>\n";
                    echo "\t\t<td>$puntos</td>\n";
                    
                    echo "\t\t<td><a >
                        <button style='background:red;color:white;'> Editar Quiniela </button></a></td>\n";
                    echo "\t\t<td><a >
                        <button style='background:red;color:white;'> Eliminar Quiniela </button></a></td>\n";
                    echo "\t</tr>\n";
                        
            }
            echo "</table>\n";
            echo "</div>";
        }
    }

    

    pg_close($conn);
?>
    <center>
         <a href="ingresar.php"> Ingresar Quiniela </a><br>
         <a href="../index.php"> Menu Principal</a>
     </center>
  </body>
</html>