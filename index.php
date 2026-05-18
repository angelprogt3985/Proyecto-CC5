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

        <a href="quinielas/listado.php" class = "button"><button>Ver Quiniela</button></a><br>
        
        <?php
            if($_SESSION["admin"]){
                echo "<a href=equipos/agregar.php class=button><button>Agregar Equipo</button></a><br>";
            }
        ?>

         <?php
            if($_SESSION["admin"]){
                echo "<a href=fases/agregar_fase.php class=button>
                <button>Agregar Fase</button></a><br>";
            }
        ?>
        
        
        <a href="equipos/listado.php" class = "button"><button>Listar Equipos</button></a><br>

        <a href="calendario/listado.php" class="button"><button>Calendario</button></a><br>

        <a href="grupos/tablas.php" class="button"><button>Tablas de grupos</button></a><br>

        <a href="quinielas/resultados.php" class="button">
        <button>Ver Resultados</button></a>
        
        <a href="logout.php" class="button"><button>Cerrar sesion</button></a><br>
    </div>

</body>
</html>