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
        
        $check = pg_query($conn, "SELECT * FROM Fase WHERE ID_Fase = $id");
        if(pg_num_rows($check) > 0){
            echo"Error: El equipo con el id $id ya existe. Ingrese otro equipo";
        } else {
            // Verificar cuántas fases hay registradas
            $query = "SELECT COUNT(*) AS total FROM Fase";
            $result = pg_query($conn, $query)
                    or die('La query fallo: ' . pg_last_error($conn));

            $fila = pg_fetch_assoc($result);
            $total = $fila["total"];


            if($total < 7){

                $checkOrden = pg_query($conn,
                    "SELECT * FROM Fase WHERE Orden = $orden");

                if(pg_num_rows($checkOrden) > 0){

                    echo "Error: Ya existe una fase con el orden $orden.";

                } else {
                    $query = "INSERT INTO Fase VALUES ($id, '$nombre', $orden)";
                    $result = pg_query($conn, $query)
                            or die('La query fallo: ' . pg_last_error($conn));

                    echo "La fase fue insertada exitosamente";
                }
            } else {
                echo "Ya se alcanzó el máximo de fases permitidas.";
            }
        }
        pg_close($conn);
    }

?>

<html>
  <head>
    <link rel = "stylesheet" href = "../style.css">
     <title>
         Equipos - Insertar
     </title>
  </head>
  <body>
    <h1>Agregar equipo</h1>
    <?php
        echo $mensaje;
    ?>

    <form method = "post">
        <b>ID de Fase</b>
        <input type = "number" name = "ID_Fase" required><br>

        <b>Nombre de la Fase</b>
        <input type = "text" name = "Nombre" required><br>

        <b>Orden de la fase</b>
        <input type = "text" name = "Orden" required><br>

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