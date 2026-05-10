<?php require __DIR__ . '/../postsql.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Partido</title>
</head>
<body>
<h2>Agregar Partido - Fase 1</h2>

<a href="listado.php">Ver calendario</a>

<br><br>

<?php
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_equipo1 = intval($_POST['id_equipo1'] ?? 0);
    $id_equipo2 = intval($_POST['id_equipo2'] ?? 0);
    $id_fase    = intval($_POST['id_fase'] ?? 0);
    $fecha      = trim($_POST['fecha'] ?? '');
    $hora       = trim($_POST['hora'] ?? '');

    if (!$id_equipo1 || !$id_equipo2 || !$id_fase || $fecha === '' || $hora === '') {
        $mensaje = 'Todos los campos son obligatorios.';
    } elseif ($id_equipo1 === $id_equipo2) {
        $mensaje = 'Un equipo no puede jugar contra si mismo.';
    } else {
        $check = pg_query_params($conn,
            "SELECT Id_Partido FROM Partido
             WHERE Fecha = $1 AND Hora = $2
               AND (ID_equipo1 IN ($3, $4) OR ID_equipo2 IN ($3, $4))",
            [$fecha, $hora, $id_equipo1, $id_equipo2]
        );

        if (pg_num_rows($check) > 0) {
            $mensaje = 'Traslape detectado: uno de los equipos ya tiene un partido programado a esa fecha y hora.';
        } else {
            $next_id = pg_fetch_result(pg_query($conn, "SELECT COALESCE(MAX(Id_Partido), 0) + 1 FROM Partido"), 0, 0);

            $insert = pg_query_params($conn,
                "INSERT INTO Partido (Id_Partido, Fecha, Hora, ID_equipo1, ID_equipo2, ID_Fase)
                 VALUES ($1, $2, $3, $4, $5, $6)",
                [$next_id, $fecha, $hora, $id_equipo1, $id_equipo2, $id_fase]
            );

            $mensaje = $insert ? 'Partido agregado correctamente.' : 'Error al guardar: ' . pg_last_error($conn);
        }
    }
}

$equipos = pg_query($conn, "SELECT ID_equipo, TRIM(Nombre) AS nombre FROM Equipo ORDER BY Nombre");
$lista_equipos = [];
while ($e = pg_fetch_assoc($equipos)) {
    $lista_equipos[] = $e;
}

$fases = pg_query($conn, "SELECT ID_Fase, TRIM(Nombre) AS nombre FROM Fase ORDER BY Orden");
$lista_fases = [];
while ($f = pg_fetch_assoc($fases)) {
    $lista_fases[] = $f;
}
?>

<?php if ($mensaje): ?>
    <p><b><?= htmlspecialchars($mensaje) ?></b></p>
<?php endif; ?>

<form method="POST">
    <label>Equipo Local:</label><br>
    <select name="id_equipo1" required>
        <option value="">-- Seleccionar equipo --</option>
        <?php foreach ($lista_equipos as $eq): ?>
            <option value="<?= $eq['id_equipo'] ?>"
                <?= (($_POST['id_equipo1'] ?? '') == $eq['id_equipo']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($eq['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label>Equipo Visitante:</label><br>
    <select name="id_equipo2" required>
        <option value="">-- Seleccionar equipo --</option>
        <?php foreach ($lista_equipos as $eq): ?>
            <option value="<?= $eq['id_equipo'] ?>"
                <?= (($_POST['id_equipo2'] ?? '') == $eq['id_equipo']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($eq['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label>Fase:</label><br>
    <select name="id_fase" required>
        <option value="">-- Seleccionar fase --</option>
        <?php foreach ($lista_fases as $f): ?>
            <option value="<?= $f['id_fase'] ?>"
                <?= (($_POST['id_fase'] ?? '') == $f['id_fase']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($f['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label>Fecha:</label><br>
    <input type="date" name="fecha" value="<?= htmlspecialchars($_POST['fecha'] ?? '') ?>" required>

    <br><br>

    <label>Hora:</label><br>
    <input type="time" name="hora" value="<?= htmlspecialchars($_POST['hora'] ?? '') ?>" required>

    <br><br>

    <input type="submit" value="Guardar Partido">
</form>

<?php pg_close($conn); ?>
</body>
</html>
