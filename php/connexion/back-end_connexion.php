<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["id"]) || empty($_POST["mdp"])) {
        $errorMessage = "Veuillez remplir tous les champs.";
        
    } else {
        $id = $_POST["id"];
        $mdp = $_POST["mdp"];
        
        if ($id === "admin" && $mdp === "mmimeaux") {
            $_SESSION["lastname"] = "ISLAM NAYAN";
            $_SESSION["firstname"] = "Ariful";
            header("Location: ../admin/admin_header.php");
        } else {
            $errorMessage = "Identifiant ou mot de passe incorrect";
        }
    }
}
if (isset($_SESSION["firstname"])) {
    header("Location: ../admin/admin_header.php");
}

require_once "page_connexion.php";
?>