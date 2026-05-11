<?php require __DIR__ . '/../postsql.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calendario de Partidos</title>
</head>
<body>

<h2>Calendario de Partidos</h2>

<a href="../index.php">Inicio</a> |
<a href="agregar_partido.php">Agregar Partido</a>

<br><br>

<?php
$sql = "SELECT p.Id_Partido, p.Fecha, p.Hora,
               TRIM(e1.Nombre) AS equipo1,
               TRIM(e2.Nombre) AS equipo2,
               TRIM(f.Nombre)  AS fase_nombre
        FROM Partido p
        JOIN Equipo e1 ON p.ID_equipo1 = e1.ID_equipo
        JOIN Equipo e2 ON p.ID_equipo2 = e2.ID_equipo
        JOIN Fase   f  ON p.ID_Fase    = f.ID_Fase
        ORDER BY p.Fecha ASC, p.Hora ASC";

$result = pg_query($conn, $sql) or die("Error: " . pg_last_error($conn));

if (pg_num_rows($result) === 0) {
    echo "<p>No hay partidos registrados.</p>";
} else {
    echo "<table border=1>";
    echo "<tr>
            <th>Fase</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Local</th>
            <th>Visitante</th>
          </tr>";

    while ($row = pg_fetch_assoc($result)) {
        $fecha = date('d/m/Y', strtotime($row['fecha']));
        $hora  = substr($row['hora'], 0, 5);

        echo "<tr>
                <td>{$row['fase_nombre']}</td>
                <td>$fecha</td>
                <td>$hora</td>
                <td>{$row['equipo1']}</td>
                <td>{$row['equipo2']}</td>
              </tr>";
    }
    echo "</table>";
}

pg_close($conn);
?>
</body>
</html>