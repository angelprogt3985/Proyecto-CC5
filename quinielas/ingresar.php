<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';


    if($_SERVER["REQUEST_METHOD"] == "POST"){
        


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
        echo $mensaje;
    ?>

    <form method = "post">
        <b>ID de la predicción</b>
        <input type = "number" name = "ID_Pred" required><br>

        <b>ID del partido</b>
        <input type = "text" name = "Nombre" required><br>

        <b>Bandera</b>
        <input type = "text" name = "Bandera" required><br>

        <b>Grupo</b>
        <input type = "text" name = "Grupo" maxlength = "1" 
        pattern = "[A-La-l]" style = "text-transform: uppercase;" required><br>

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

