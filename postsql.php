<?php
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT');
    if (!$post) {
        $port = '5432';
    }
    $dbname = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $password = getenv('DB_PASSWORD');

    $conn = pg_connect("host=$host port=$port dbname=$dbname
    user=$user password=$password") or die("No se pudo conectar");
?>