<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';

    if(!$_SESSION["admin"]){
        header("Location: ../login.php");
        exit;
    }

    $mensaje = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $accion = $_POST["decision"];

        if($accion == "si"){

            $id = $_POST["ID_Fase"];
            $nombre = $_POST["Nombre"];
            $orden = $_POST["Orden"];
            $query = "DELETE FROM Fase WHERE ID_Fase = $id";
            $result = pg_query($conn, $query);
            if(!$result){
                echo "Error: No se puede eliminar la fase.";
            } else {
                header("Location: listado.php");
                exit;
            }

        } else {
            header("Location: listado.php");
            exit;
        }
    }
    pg_close($conn);
?>

<html>
  <head>
    <link rel = "stylesheet" href = "../style.css">
     <title>
         Equipos - Eliminar
     </title>
  </head>
  <body>
    <h1>Eliminar equipo</h1>
    <?php
        echo $mensaje;
    ?>

    <form method = "post">
       <?php
            $id = $_GET["ID_Fase"];
            $nombre = $_GET["Nombre"];
            $orden = $_GET["Orden"];
            echo "<b>ID de la fase: </b>$id<br>\n";
            echo "<input type='hidden' name='ID_Fase' value='$id' required><br>";
            echo "<b>Nombre de la fase: </b>$nombre<br>\n";
            echo "<input type='hidden' name='Nombre' value='$nombre' required><br>";
            echo "<b>Orden: </b>$orden<br>\n";
            echo "<input type='hidden' name='Orden' value='$orden' required><br>";
        ?>
        <h4>¿Quieres eliminar la fase?</h4>
        <button type="submit" name="decision" value="si">
            Si
        </button>

        <button type="submit" name="decision" value="no">
            No
        </button>


    </form>
    <center>
         <a href="listado_fase.php"> Listado de fases </a><br>
         <a href="agregar_fase.php"> Agregar fase </a><br>
         <a href="../index.php"> Menu Principal </a>
     </center>
</body>
</html>
