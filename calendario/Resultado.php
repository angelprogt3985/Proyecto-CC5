<?php 
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';
    if(!$_SESSION["admin"]){
        header("Location: ../login.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <link rel = "stylesheet" href = "../style.css">
    <meta charset="UTF-8">
    <title>Ingresar Resultado</title>
</head>
<body>
<h2>Ingresar Resultado</h2>

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
    "SELECT p.Id_Partido, p.Fecha, p.Hora, p.goles_equip1, p.goles_equip2,
            TRIM(e1.Nombre) AS equipo1, TRIM(e2.Nombre) AS equipo2,
            TRIM(f.Nombre) AS fase_nombre
     FROM Partido p
     JOIN Equipo e1 ON p.ID_equipo1 = e1.ID_equipo
     JOIN Equipo e2 ON p.ID_equipo2 = e2.ID_equipo
     JOIN Fase   f  ON p.ID_Fase    = f.ID_Fase
     WHERE p.Id_Partido = $1",
    [$id]
));

if (!$partido) {
    echo "<p>Partido no encontrado.</p>";
    pg_close($conn);
    exit;
}

if ($partido['goles_equip1'] !== null) {
    echo "<p>Este partido ya tiene resultado ingresado: {$partido['equipo1']} {$partido['goles_equip1']} - {$partido['goles_equip2']} {$partido['equipo2']}.</p>";
    echo "<a href='listado.php'>Volver al calendario</a>";
    pg_close($conn);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $goles1 = $_POST['goles1'] ?? '';
    $goles2 = $_POST['goles2'] ?? '';

    if ($goles1 === '' || $goles2 === '' || !is_numeric($goles1) || !is_numeric($goles2)) {
        $mensaje = 'Ingresa un numero valido de goles para ambos equipos.';
    } elseif (intval($goles1) < 0 || intval($goles2) < 0) {
        $mensaje = 'Los goles no pueden ser negativos.';
    } else {
        $upd = pg_query_params($conn,
            "UPDATE Partido SET goles_equip1 = $1, goles_equip2 = $2 WHERE Id_Partido = $3",
            [intval($goles1), intval($goles2), $id]
        );

        if ($upd) {
            $mensaje = "Resultado guardado: {$partido['equipo1']} {$goles1} - {$goles2} {$partido['equipo2']}.";
            $partido['goles_equip1'] = intval($goles1);
            $partido['goles_equip2'] = intval($goles2);
        } else {
            $mensaje = 'Error al guardar: ' . pg_last_error($conn);
        }
    }
}

$fecha = date('d/m/Y', strtotime($partido['fecha']));
$hora  = substr($partido['hora'], 0, 5);
?>

<p><?= $partido['fase_nombre'] ?> &mdash; <?= $fecha ?> <?= $hora ?></p>

<?php if ($mensaje): ?>
    <p><b><?= htmlspecialchars($mensaje) ?></b></p>
<?php endif; ?>

<?php if ($partido['goles_equip1'] === null): ?>
<form method="POST">
    <b><?= htmlspecialchars($partido['equipo1']) ?></b>
    <input type="number" name="goles1" min="0" 
           value="<?= htmlspecialchars($_POST['goles1'] ?? '') ?>" required style="width:50px; text-align:center;">
    -
    <input type="number" name="goles2" min="0" 
           value="<?= htmlspecialchars($_POST['goles2'] ?? '') ?>" required style="width:50px; text-align:center;">
    <b><?= htmlspecialchars($partido['equipo2']) ?></b>

    <br><br>

    <input type="submit" value="Guardar Resultado">
</form>
<?php endif; ?>

<?php pg_close($conn); ?>
</body>
</html>

