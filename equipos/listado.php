<?php
    require '../Proyecto-CC5/postsql.php'
?>
<html>  
  <head>
   <link rel = "stylesheet" href = "../style.css">
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
        echo "<div style='text-align:center;'>";
        echo "<table border = 1 style = 'margin:auto;'>\n";
        echo "\t<tr>\n";
        echo "\t\t<th><b>Nombre</b></th>\n";
        echo "\t\t<th>Bandera</th>\n";
        echo "\t\t<th>Grupo</th>\n";
        echo "\t</tr>\n";

        while ($line = pg_fetch_assoc($result)) {

            $nombre = $line["Nombre"];
            $bandera = $line["Bandera"];
            $grupo = $line["Grupo"];
            $tipos = [
                'A' => 'Activo',
                'P' => 'Pasivo',
                'C' => 'Capital',
                'I' => 'Ingreso',
                'G' => 'Gasto'
            ];
            
                echo "\t<tr>\n";
                echo "\t\t<td>$nombre</td>\n";
                echo "\t\t<td>$bandera</td>\n";
                echo "\t\t<td>$grupo</td>\n";
                echo "\t\t<td><a >
                    <button style='background:red;color:white;'> Eliminar </button></a></td>\n";
                echo "\t\t<td><a >
                    <button style='background:green;color:white;'> Editar </button></a></td>\n";
                echo "\t</tr>\n";
        
        }
        echo "</table>\n";
        echo "</div>";
    }

    pg_close($conn);
?>

</html>