<?php 
    require("lib/connect.php"); 
    session_start();
    session_destroy();
    header("Location: index.php");
?>