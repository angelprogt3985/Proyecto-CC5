<?php
    require __DIR__ . '/../auth.php';
    require __DIR__ . '/../postsql.php';
    if (!$_SESSION["admin"]) {
        header("Location: ../login.php");
        exit;
    }

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($id <= 0) {
        header("Location: listado.php");
        exit;
    }

    pg_query_params($conn, "DELETE FROM Partido WHERE Id_Partido = $1", [$id]);

    pg_close($conn);
    header("Location: listado.php");
    exit;
?>
