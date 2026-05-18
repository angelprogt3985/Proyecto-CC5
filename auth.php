<?php
    session_start();
    if(!isset($_SESSION["id"])){
        header("Location: /R/ProyectoCC/Proyecto-CC5/login.php");
        exit;
    }
?>