<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['profile_type'])) {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../../PHP/Page_login/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Card</title>
  <link rel="stylesheet" href="../../css/prof/prof_header.css">
  <link rel="stylesheet" href="../../css/prof/acceuil_prof.css">
</head>
<body>
<div class="header">
  <div class="dlogo">
      <img class="logo" src="../../source/img/logo/logo-nom.png" alt="logo">
  </div>
  <div class="navbar">
      <button class="value">Note</button>
      <button class="value">Profile</button>
      <a href="../../PHP/Page_login/logout.php" class="value" style="text-decoration: none;">Déconnexion</a>
  </div>
</div>

 <!-- Container principal pour centrer les cartes -->
<div class="card-container">
  <!-- Container de chaque carte -->
  <div class="card">
    <!-- Texte de la carte avec le lien vers le popup -->
    <div class="card-text" data-popup-id="popup1">
      <p>Ressources</p>
    </div>
    <!-- Boutons de la carte avec les liens vers les popups -->
    <div class="card-icons">
      <button class="card-btn" data-popup-id="popup2"><img src="../../source/img/icon/noir/ancien-n.png" alt="anciennes_note" width="40"></button>
      <button class="card-btn" data-popup-id="popup3"><img src="../../source/img/icon/noir/ajouter-n.png" alt="anciennes_note" width="60"></button>
    </div>
  </div>
</div>

<!-- Containers des popups -->
<div id="popup1" class="popup">
  <div class="popup-content">
    <span class="popup-close">&times;</span>
    <p>Contenu pour le Popup 1...</p>
  </div>
</div>

<div id="popup2" class="popup">
  <div class="popup-content">
    <span class="popup-close">&times;</span>
    <p>Contenu pour le Popup 2...</p>
  </div>
</div>

<div id="popup3" class="popup">
  <div class="popup-content">
    <span class="popup-close">&times;</span>
    <p>Contenu pour le Popup 3...</p>
  </div>
</div>

<script src="../../js/prof/acceuil_prof.js"></script>
</body>
</html>
