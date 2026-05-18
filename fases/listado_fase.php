<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';
?>
<html>  
  <head>
   <link rel = "stylesheet" href = "../style.css">
   <meta charset="UTF-8">
     <title>
        Fases - Listado
     </title>
  </head>
  <body>

<?php

    $query = "SELECT * FROM Fase ORDER BY Orden";

    $result = pg_query($conn, $query)
              or die('La query fallo: ' . pg_last_error($conn));

    if(pg_num_rows($result) == 0){

        echo "No hay fases registradas";

    } else {

        $nombre = "";
        $orden = "";
        $id = 0;

        echo "<div style='text-align:center;'>";
        echo "<table border=1 style='margin:auto;'>\n";

        echo "\t<tr>\n";
        echo "\t\t<th><b>Nombre</b></th>\n";
        echo "\t\t<th>Orden</th>\n";
        echo "\t\t<th>ID</th>\n";
        echo "\t</tr>\n";

        while($line = pg_fetch_assoc($result)){

            $nombre = $line["nombre"];
            $orden = $line["orden"];
            $id = $line["id_fase"];

            echo "\t<tr>\n";

            echo "\t\t<td>$nombre</td>\n";
            echo "\t\t<td>$orden</td>\n";
            echo "\t\t<td>$id</td>\n";

            if($_SESSION["admin"]){

                echo "\t\t<td>
                    <a href='eliminar_fase.php?ID_Fase=$id&Nombre=$nombre&Orden=$orden'>
                    <button style='background:red;color:white;'>
                    Eliminar
                    </button>
                    </a>
                    </td>\n";

                echo "\t\t<td>
                    <a href='editar_fase.php?ID_Fase=$id&Nombre=$nombre&Orden=$orden'>
                    <button style='background:green;color:white;'>
                    Editar
                    </button>
                    </a>
                    </td>\n";
            }

            echo "\t</tr>\n";
        }

        echo "</table>\n";
        echo "</div>";
    }

    pg_close($conn);

?>

    <center>
         <?php
            if($_SESSION["admin"]){
                echo "<a href=fases/agregar_fase.php class=button>
                <button>Agregar Fase</button></a><br>";
            }
        ?>
        <a href="../calendario/listado.php"> Ver Partidos </a><br>
        <a href="../equipos/listado.php"> Ver Equipos </a><br>
        <a href="../quinielas/listado.php"> Ver quinielas de los usuarios </a><br>
        <a href="../quinielas/resultados.php"> Ver posiciones de los usuarios </a><br>
        <a href="../index.php"> Menu Principal</a>
     </center>
  </body>
</html>