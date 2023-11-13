<?php
session_start();
$_SESSION["rol"] = "cliente";
session_destroy();
header("Location: principal.php");
?>