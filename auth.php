<?php
    session_start();
    if(!isset($_SESSION["id"])){
        $base = "http://" . $_SERVER['HTTP_HOST'];
        header("Location: $base/login.php");
        exit;
    }
?>