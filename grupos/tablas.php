<?php require __DIR__ . '/../postsql.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tabla Mundial</title>
</head>
<body>

<h1>Fase de Grupos</h1>

<?php

$grupos = range('A', 'L');

foreach($grupos as $grupo){

    $sqlEquipos = "
        SELECT *
        FROM equipo
        WHERE grupo = '$grupo'
    ";

    $resultadoEquipos = pg_query($conn, $sqlEquipos);

    echo "<h2>Grupo $grupo</h2>";

    echo "
    <table border='1'>

        <tr>
            <th>Equipo</th>
            <th>PJ</th>
            <th>PG</th>
            <th>PE</th>
            <th>PP</th>
            <th>GF</th>
            <th>GC</th>
            <th>DG</th>
            <th>PTS</th>
        </tr>
    ";

    while($equipo = pg_fetch_assoc($resultadoEquipos)){

        $idEquipo = $equipo['id_equipo'];

        $pj = 0;
        $pg = 0;
        $pe = 0;
        $pp = 0;
        $gf = 0;
        $gc = 0;
        $pts = 0;

        $sqlPartidos = "
            SELECT *
            FROM partido
            WHERE id_equipo1 = $idEquipo
            OR id_equipo2 = $idEquipo
        ";

        $resultadoPartidos = pg_query($conn, $sqlPartidos);

        while($partido = pg_fetch_assoc($resultadoPartidos)){

            if(
                $partido['goles_equip1'] === null ||
                $partido['goles_equip2'] === null
            ){
                continue;
            }

            $pj++;

            if($partido['id_equipo1'] == $idEquipo){

                $golesFavor = $partido['goles_equip1'];
                $golesContra = $partido['goles_equip2'];

            }else{

                $golesFavor = $partido['goles_equip2'];
                $golesContra = $partido['goles_equip1'];
            }

            $gf += $golesFavor;
            $gc += $golesContra;

            if($golesFavor > $golesContra){

                $pg++;
                $pts += 3;

            }elseif($golesFavor == $golesContra){

                $pe++;
                $pts += 1;

            }else{

                $pp++;
            }
        }

        $dg = $gf - $gc;

        echo "
        <tr>
            <td>".$equipo['nombre']."</td>
            <td>$pj</td>
            <td>$pg</td>
            <td>$pe</td>
            <td>$pp</td>
            <td>$gf</td>
            <td>$gc</td>
            <td>$dg</td>
            <td>$pts</td>
        </tr>
        ";
    }

    echo "</table>";

    echo "<br><br>";
}

?>

</body>
</html>