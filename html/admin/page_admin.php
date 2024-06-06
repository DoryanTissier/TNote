<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>menu</title>
    <link rel="stylesheet" href="../../css/admin/admin_header.css">
    <link rel="stylesheet" href="../../css/admin/admin_card.css">
    <link rel="stylesheet" href="../../css/admin/admin.css">
    <link rel="stylesheet" href="../../css/admin/admin_aj_td_prof_form.css">
</head>
<body>


<!-- Menu -->
  <div class="header">
    <div class="dlogo">
        <img class="logo" src="../../source/img/logo/logo-nom.png" alt="logo">
    </div>
    <div class="navbar">
        <button onclick="window.location.href='page_admin.html'" class="value">Note</button>
        <button class="value">Profile</button>
        <button onclick="window.location.href='../connexion/connexion.html'" class="value" id="rouge">Déconnexion</button>
    </div>
  </div>
<!-- FIN menu -->

<div class="block">

<!-- Conteneur pour aligner les cartes des TD -->
  <h1>MMI1<button class="btn_exp"><b>EXPORTER</b></button></h1>
    <div class="card-container">
      
      <!-- TD1 -->
      <div class="card" data-popup-id="td1-popup">
        <div class="card-text">
          <p>TD1</p>
        </div>
        <div class="card-icons">
          <button class="card-btn" data-popup-id="aj-td1-popup"><b>ajouter</b></button>
        </div>
      </div>

      <!-- TD2 -->
      <div class="card" data-popup-id="td2-popup">
        <div class="card-text">
          <p>TD2</p>
        </div>
        <div class="card-icons">
          <button class="card-btn" data-popup-id="aj-td2-popup"><b>ajouter</b></button>
        </div>
      </div>
      
      <!-- TD3 -->
      <div class="card" data-popup-id="td3-popup">
        <div class="card-text">
          <p>TD3</p>
        </div>
        <div class="card-icons">
          <button class="card-btn" data-popup-id="aj-td3-popup"><b>ajouter</b></button>
        </div>
      </div>

      <!-- ajouter -->
      <div class="aj" data-popup-id="aj-td-popup">
        <img class="plus" src="../../source/img/icon/blanc/ajouter-b.png" alt="ajouter un TD">
      </div>
    </div>
    <br>
<!-- FIN conteneur pour aligner les cartes des TD -->



<!-- Conteneur pour aligner les cartes des professeur(e)s -->
  <h1>Professeur(e)s</h1>
    <div class="card-container">

      <!-- Nadia AL SALTI -->
      <div class="card">
        <div class="card-text">
          <p>Anglais<br><b class="nom">Nadia AL SALTI</b></p>
        </div>
        <div class="card-icons">
          <button class="card-btn" data-popup-id="detail_nadia-al-salti_popup"><b>détails</b></button>
        </div>
      </div>

      <!-- clément SIGALAS -->
      <div class="card">
        <div class="card-text">
          <p>Culture num<br><b class="nom">Clément SIGALAS</b></p>
        </div>
        <div class="card-icons">
          <button class="card-btn" data-popup-id="detail_clement-sigalas"><b>détails</b></button>
        </div>
      </div>

      <!-- ajouter -->
      <div class="aj" data-popup-id="aj-prof-popup">
        <img class="plus" src="../../source/img/icon/blanc/ajouter-b.png" alt="ajouter un TD">
      </div>
      
    </div>
<!-- FIN conteneur pour aligner les cartes des professeur(e)s -->

</div>


<!-- Containers des popups TD -->
    <!-- td1 -->
    <div id="td1-popup" class="popup">
      <div class="popup-content">
        <span class="popup-close">&times;</span>
        <img src="../../source/img/logo/logo-nom.png" width="200px" alt="">
        <p>td 1</p>
      </div>
    </div>
    <div id="aj-td1-popup" class="popup">
      <div class="popup-content">
        <span class="popup-close">&times;</span>
        <?php include 'aj_etudiant.php'; ?>
      </div>
    </div><br>

    <!-- td2 -->
    <div id="td2-popup" class="popup">
      <div class="popup-content">
        <span class="popup-close">&times;</span>
        <img src="../../source/img/logo/logo-nom.png" width="200px" alt="">
        <p>td2</p>
      </div>
    </div>
    <div id="aj-td2-popup" class="popup">
      <div class="popup-content">
        <span class="popup-close">&times;</span>
        <?php include 'aj_etudiant.php'; ?>
      </div>
    </div><br>

    <!-- td3 -->
    <div id="td3-popup" class="popup">
      <div class="popup-content">
        <span class="popup-close">&times;</span>
        <img src="../../source/img/logo/logo-nom.png" width="200px" alt="">
        <p>td3</p>
      </div>
    </div>
    <div id="aj-td3-popup" class="popup">
      <div class="popup-content">
        <span class="popup-close">&times;</span>
        <?php include 'aj_etudiant.php'; ?>
      </div>
    </div><br>
<!-- FIN containers des popups TD -->

<!-- Containers des popups ajout TD -->
<div id="aj-td-popup" class="popup">
  <div class="popup-content">
    <span class="popup-close">&times;</span>
    <?php include 'aj_td.php'; ?>
  </div>
</div>
<!-- FIN containers des popups ajout TD -->



<!-- Containers des popups prof -->
  <!-- Nadia AL SALTI -->
  <div id="detail_nadia-al-salti_popup" class="popup">
    <div class="popup-content">
      <span class="popup-close">&times;</span>
      <p>Détails Nadia AL SALTI</p>
    </div>
  </div>
  <!-- FIN Nadia AL SALTI -->

  <!-- clément SIGALAS -->
  <div id="detail_clement-sigalas" class="popup">
    <div class="popup-content">
      <span class="popup-close">&times;</span>
      <p>Détails clément SIGALAS</p>
    </div>
  </div>
  <!-- FIN clément SIGALAS -->
<!-- Fin containers des popups prof -->

<!-- Containers des popups ajout prof -->
<div class="popup" id="aj-prof-popup" >
  <div class="popup-content">
    <span class="popup-close">&times;</span>
    <?php include 'aj_prof.php'; ?>
  </div>
</div>
<!-- FIN containers des popups ajout prof -->



  <script src="../../js/admin/card.js"></script>
</body>
</html>

