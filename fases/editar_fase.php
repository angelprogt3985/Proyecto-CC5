<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';

    if(!$_SESSION["admin"]){
        header("Location: ../login.php");
        exit;
    }

    $mensaje = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $id = $_POST["ID_Fase"];
        $nombre = $_POST["Nombre"];
        $orden = $_POST["Orden"];

        $query = "SELECT * FROM Fase 
                  WHERE Orden = $orden 
                  AND ID_Fase != $id";

        $result = pg_query($conn, $query)
            or die('La query fallo: ' . pg_last_error($conn));

        if(pg_num_rows($result) > 0){

            $mensaje = "Ya existe otra fase con ese orden";

        } else {

            $query = "UPDATE Fase
                      SET Nombre = '$nombre',
                          Orden = $orden
                      WHERE ID_Fase = $id";

            $result = pg_query($conn, $query)
                or die('La query fallo: ' . pg_last_error($conn));

            $mensaje = "La fase fue editada exitosamente";
        }
    }
?>

<html>
<head>
    <link rel="stylesheet" href="../style.css">
    <meta charset="UTF-8">
    <title>
        Fases - Editar
    </title>
</head>

<body>

    <h1>Editar fase</h1>

    <?php
        echo $mensaje;
    ?>

    <form method="post">

        <?php

            $id = $_GET["ID_Fase"];
            $nombre = $_GET["Nombre"];
            $orden = $_GET["Orden"];

            echo "<b>ID de la fase:</b> $id<br>\n";

            echo "<input type='hidden' 
                         name='ID_Fase' 
                         value='$id' required><br>";

            echo "<b>Nombre de la fase</b><br>\n";

            echo "<input type='text'
                         name='Nombre'
                         value='$nombre'
                         required><br>\n";

            echo "<b>Orden</b><br>\n";

            echo "<input type='number'
                         name='Orden'
                         value='$orden'
                         required><br>\n";
        ?>

        <button type="submit">
            Enviar
        </button>

    </form>

    <center>
        <a href="listado.php">Listado de fases</a><br>
        <a href="agregar.php">Agregar fase</a><br>
        <a href="../index.php">Menu Principal</a>
    </center>

</body>
</html>

<?php
    pg_close($conn);
?>