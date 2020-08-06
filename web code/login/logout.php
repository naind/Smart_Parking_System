<?php
    session_start();
    unset($_SESSION["userid"]);
    unset($_SESSION["name"]);
    unset($_SESSION["nick"]);
    unset($_SESSION["level"]);
    header("Location:http://10.10.11.15:8080/ch09/index.php");
?>
