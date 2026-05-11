<?php
    require __DIR__ . '/auth.php';
    echo "Bienvenido " . $_SESSION["nombre"] . "<br>";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiniela</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="menu">

        <h1>Quiniela</h1>

        <button>Ver Quiniela</button>
        
        <?php
            if ($_SESSION["id"] == 0){
                echo "<a href=equipos/agregar.php class=button>Agregar Equipo</a><br>";
            }
        ?>
        
        
        <a href="equipos/listado.php" class = "button">Listar Equipos</a><br>

        <a href="calendario/listado.php" class="button">Calendario</a><br>

        <a href="grupos/tablas.php" class="button">Tablas de grupos</a><br>

        <button>Ver Resultados</button>

    </div>

</body>
</html>