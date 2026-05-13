<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';


    if($_SERVER["REQUEST_METHOD"] == "POST"){

    if($_SERVER["REQUEST_METHOD"] == "POST"){

    $id_usuario = $_SESSION["id"];

    $id_pred = $_POST["ID_Pred"];
    $id_partido = $_POST["Id_Partido"];
    $pred_gol1 = $_POST["goles1"];
    $pred_gol2 = $_POST["goles2"];
    $puntos_obt = 0;

    }
}

?>

<html>             
  <head>
    <link rel = "stylesheet" href = "../style.css">
     <title>
         Quiniela - Insertar
     </title>
  </head>
  <body>
    <h1>Agregar equipo</h1>
    <?php
    ?>

    <form method = "post">
        <b>ID de la predicción</b>
        <input type = "number" name = "ID_Pred" required><br>

        <b>ID del partido</b>
        <input type = "text" name = "Nombre" required><br>

        <b>Goles equipo 1</b>
        <input type = "text" name = "goles1" required><br>

        <b>Goles equipo 2</b>
        <input type = "text" name = "goles2" required><br>

        

        <button type = "submit">
                Enviar
            </button>

    </form>
    <center>
         <a href="listado.php"> Listado de equipos </a><br>
         <a href="../index.php"> Menu Principal </a>
     </center>
</body>
</html>

