<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';
    if($_SESSION["admin"]){
        header("Location: ../login.php");
        exit;
    }
    $mensaje = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $accion = $_POST["decision"];
        if ($accion == "si"){
            $id = $_POST["ID_Pred"];
            $queryFecha = "SELECT P.fecha, P.hora
               FROM Prediccion PR
               JOIN Partido P ON PR.ID_Partido = P.ID_Partido
               WHERE PR.ID_Pred = $id";

            $resultadoFecha = pg_query($conn, $queryFecha);
            $partido = pg_fetch_assoc($resultadoFecha);
            $fechaHoraPartido = strtotime($partido["fecha"] . " " . $partido["hora"]);
            $limite = $fechaHoraPartido - (5 * 60);
            $ahora = time();

            if($ahora >= $limite){
                die("Ya no puedes eliminar esta predicción porque faltan menos de 5 minutos para el partido.");
            }
            
            $query = "DELETE FROM Prediccion WHERE ID_Pred = $id";
            $result = pg_query($conn, $query);

            if (!$result){
                echo "Error: Ocurrio un error al eliminar la quiniela";
            } else {
                header("Location: listado.php");
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
    <meta charset="UTF-8">
     <title>
         Quiniela - Eliminar
     </title>
  </head>
  <body>
    <h1>Eliminar quiniela</h1>
    <?php
        echo $mensaje;
    ?>

    <form method = "post">
        <?php
            $id = $_GET["ID_Pred"];
            $nombreL = $_GET["NombreL"];
            $nombreV = $_GET["NombreV"];
            $predGolL = $_GET["predGolL"];
            $predGolV = $_GET["predGolV"];
            echo "<input type = hidden name = ID_Pred value = $id required>";

            echo "<b>Partido: </b>$nombreL - $nombreV<br>\n";

            echo "<b>Predicción: </b>$nombreL $predGolL - $predGolV $nombreV<br>\n";
        
        ?>
        <h4>¿Quieres eliminar la quiniela?</h4>
        <button type = "submit" name = "decision" value = "si">
                Si
            </button>

            <button type = "submit" name = "decision" value = "no">
                No
            </button>

    </form>
    <center>
         <a href="listado.php"> Listado de quinielas </a><br>
         <a href="ingresar.php"> Agregar quiniela </a><br>
         <a href="../index.php"> Menu Principal </a>
     </center>
</body>
</html>
