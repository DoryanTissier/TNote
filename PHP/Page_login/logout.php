<?php
session_start();
session_unset();
session_destroy();
header("Location: login.php"); // Redirige vers la page de connexion ou la page d'accueil
exit();
?>
