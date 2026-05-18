<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';
    if (!$_SESSION["admin"]) {
        header("Location: ../login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../style.css">
    <meta charset="UTF-8">
    <title>Editar Partido</title>
</head>
<body>
<h2>Editar Partido</h2>

<a href="listado.php">Ver calendario</a>

<br><br>

<?php
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensaje = '';

if ($id <= 0) {
    echo "<p>ID de partido invalido.</p>";
    pg_close($conn);
    exit;
}

$partido = pg_fetch_assoc(pg_query_params($conn,
    "SELECT * FROM Partido WHERE Id_Partido = $1", [$id]
));

if (!$partido) {
    echo "<p>Partido no encontrado.</p>";
    pg_close($conn);
    exit;
}

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
               AND Id_Partido <> $3
               AND (ID_equipo1 IN ($4, $5) OR ID_equipo2 IN ($4, $5))",
            [$fecha, $hora, $id, $id_equipo1, $id_equipo2]
        );

        if (pg_num_rows($check) > 0) {
            $mensaje = 'Traslape detectado: uno de los equipos ya tiene partido a esa fecha y hora.';
        } else {
            $upd = pg_query_params($conn,
                "UPDATE Partido SET ID_equipo1 = $1, ID_equipo2 = $2, ID_Fase = $3, Fecha = $4, Hora = $5
                 WHERE Id_Partido = $6",
                [$id_equipo1, $id_equipo2, $id_fase, $fecha, $hora, $id]
            );
            $mensaje = $upd ? 'Partido actualizado correctamente.' : 'Error al actualizar: ' . pg_last_error($conn);
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

$eq1_actual = $_POST['id_equipo1'] ?? $partido['id_equipo1'];
$eq2_actual = $_POST['id_equipo2'] ?? $partido['id_equipo2'];
$fase_actual = $_POST['id_fase']   ?? $partido['id_fase'];
$fecha_actual = $_POST['fecha']    ?? $partido['fecha'];
$hora_actual  = $_POST['hora']     ?? substr($partido['hora'], 0, 5);
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
                <?= ($eq1_actual == $eq['id_equipo']) ? 'selected' : '' ?>>
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
                <?= ($eq2_actual == $eq['id_equipo']) ? 'selected' : '' ?>>
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
                <?= ($fase_actual == $f['id_fase']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($f['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>

    <label>Fecha:</label><br>
    <input type="date" name="fecha" value="<?= htmlspecialchars($fecha_actual) ?>" required>

    <br><br>

    <label>Hora:</label><br>
    <input type="time" name="hora" value="<?= htmlspecialchars($hora_actual) ?>" required>

    <br><br>

    <input type="submit" value="Guardar Cambios">
</form>

<?php pg_close($conn); ?>
</body>
</html>
