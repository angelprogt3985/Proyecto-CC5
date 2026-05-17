<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';
?>
<html>  
  <head>
   <link rel = "stylesheet" href = "../style.css">
   <meta charset="UTF-8">
     <title>
        Equipos - Listado
     </title>
  </head>
  <body>

<?php
    $query = "SELECT * FROM Equipo ORDER BY Nombre";

    $result = pg_query($conn, $query) or die('La query fallo: ' .pg_last_error($conn));
    if(pg_num_rows($result) == 0){
        echo "No hay equipos";
    } else {
        $nombre = "";
        $bandera = "";
        $grupo = "";
        $id = 0;
        echo "<div style='text-align:center;'>";
        echo "<table border = 1 style = 'margin:auto;'>\n";
        echo "\t<tr>\n";
        echo "\t\t<th><b>Nombre</b></th>\n";
        echo "\t\t<th>Bandera</th>\n";
        echo "\t\t<th>Grupo</th>\n";
        echo "\t\t<th>ID</th>\n";
        echo "\t</tr>\n";

        while ($line = pg_fetch_assoc($result)) {

            $nombre = $line["nombre"];
            $bandera = $line["bandera"];
            $grupo = $line["grupo"];
            $id = $line["id_equipo"];
            
                echo "\t<tr>\n";
                echo "\t\t<td>$nombre</td>\n";
                echo "\t\t<td>$bandera</td>\n";
                echo "\t\t<td>$grupo</td>\n";
                echo "\t\t<td>$id</td>\n";
                if ($_SESSION["admin"]){
                    echo "\t\t<td><a href='eliminar.php?ID_Equipo=$id&Nombre=$nombre&Bandera=$bandera&Grupo=$grupo'>
                        <button style='background:red;color:white;'> Eliminar </button></a></td>\n";
                    echo "\t\t<td><a href='editar.php?ID_Equipo=$id&Nombre=$nombre&Bandera=$bandera&Grupo=$grupo'>
                        <button style='background:green;color:white;'> Editar </button></a></td>\n";
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
                    echo "<a href=agregar.php> Agregar Equipo </a><br>";
                } 
        ?>
         <a href="../index.php"> Menu Principal</a>
     </center>
  </body>
</html>