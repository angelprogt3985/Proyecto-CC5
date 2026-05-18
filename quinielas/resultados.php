<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';
    $id = $_SESSION["id"];
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
    <?php
        $id = $_SESSION["id"]; 
        $query = "SELECT U.nombre, PR.puntos_obt FROM Usuario U
        JOIN Prediccion PR ON U.id_usuario = PR.id_usuario 
        ORDER BY PR.puntos_obt DESC";
        $result = pg_query($conn, $query);

        $pos = 1;
        if(pg_num_rows($result) == 0){
            echo "Ningun usuario ha hecho alguna prediccion";
        } else {
            echo "<div style='text-align:center;'>";
            echo "<table border = 1 style = 'margin:auto;'>\n";
            echo "\t<tr>\n";
            echo "\t\t<th><b>Posición</b></th>\n";
            echo "\t\t<th>Usuario</th>\n";
            echo "\t\t<th>Puntos obtenidos</th>\n";
            echo "\t</tr>\n";

            while($line = pg_fetch_assoc($result)){
                $nombre = $line["nombre"];
                $puntos = $line["puntos_obt"];
                echo "\t<tr>\n";
                echo "\t\t<td>$pos</td>\n";
                echo "\t\t<td>$nombre</td>\n";
                echo "\t\t<td>$puntos</td>\n";
                $pos++;
            }

            echo "</table>\n";
            echo "</div>";
        }

    ?>

  <center>
         <a href="../index.php"> Menu Principal</a>
     </center>
  </body>
</html>