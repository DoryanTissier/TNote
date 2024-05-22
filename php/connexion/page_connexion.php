<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>
    <link rel="stylesheet" href="../../css/connexion/connexion.css">
</head>
<body>

    <div class="fp" id="connex">

        <!-- DEBUT rectangle 1 -->
        <div class="formulaire-co">

            <!-- DEBUT logo -->
            <div class="dlogo">
                <img class="logo" src="../../source/img/logo/logo-nom.png" alt="logo">
            </div>
            <!-- FIN logo -->

            <!-- DEBUT formulaire de connexion -->
            <form class="pos" action="back-end_connexion.php" method="POST">

                <!-- DEBUT choix profile -->
                <div class="type-p">
                    <label class="choix" >
                        Choix du profil :
                    </label>
                    <div class="profile-images" >
                        <label>
                            <input type="radio" name="etudiant" value="etudiant" required>
                            <img class="choix-p" src="../../source/img/profile/e1.png" alt="Étudiant">
                            <span class="message">Étudiant(e)</span>
                        </label>
                        <label>
                            <input type="radio" name="prof" value="professeur">
                            <img class="choix-p" src="../../source/img/profile/p1.png" alt="Professeur">
                            <span class="message">Professeur(e)</span>
                        </label>
                        <label>
                            <input type="radio" name="admin" value="admin">
                            <img class="choix-p" src="../../source/img/profile/admin.png" alt="admin">
                            <span class="message">Admin</span>
                        </label>
                    </div>
                </div>
                <!-- FIN choix profile -->

                <!-- DEBUT ID et MDP -->
                <div class="id-mdp">
                    <input type="text" name="id" placeholder=" " required>
                    <span>
                        Identifiant
                    </span>
                </div>
                <div class="id-mdp">
                    <input type="password" name="mdp" placeholder=" " required>
                    <span>
                        Mot de passe
                    </span>
                </div>
                <!-- FIN ID et MDP -->

                <!-- DEBUT button connexion -->
                <button type="submit">
                    Connexion
                    <div class="arrow-wrapper">
                        <div class="arrow"></div>
                
                    </div>
                </button>
                <!-- FIN button connexion -->

            </form>
            <!-- FIN formulaire de connexion -->

        </div>
        <!-- FIN rectangle 1 -->

        <!-- DEBUT rectangle 2 -->
        <div class="block-logo">
            <img src="../../source/img/logo/uge.png" alt="logo universite gustave eiffel" height="50px">
            <img src="../../source/img/logo/x.png" alt="" height="50px">
            <img src="../../source/img/logo/logo-nom.png" alt="logo Tnote" height="50px">
        </div>
         <!-- FIN rectangle 2 -->

    </div>
    <?php
    if (isset($errorMessage)) {
        echo "<p>$errorMessage</p>";
    }
    ?>

    <script src="../../js/connexion/connexion.js"></script>

</body>
</html>