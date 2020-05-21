<!DOCTYPE html>
<html lang="fr">
    <head>
		<meta charset="UTF-8">
        <title>Studyboards</title>
        <link rel="icon" href="icone.png" type="x-icon">
        <link rel="stylesheet" href="Affichage/style/accueil.css">
        
        <link rel="apple-touch-icon" sizes="180x180" href="Affichage/style/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="Affichage/style/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="Affichage/style/favicon-16x16.png">
        <link rel="manifest" href="Affichage/style/site.webmanifest">
        <link rel="mask-icon" href="Affichage/style/safari-pinned-tab.svg" color="#5bbad5">
        <link rel="shortcut icon" href="Affichage/style/favicon.ico">
        <meta name="apple-mobile-web-app-title" content="Studyboards">
        <meta name="application-name" content="Studyboards">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-config" content="Affichage//browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
    </head>
  <?php 
	session_start();
  ?>
    <body>
        <img class="Logo" src="Affichage/style/TitleLogo_WhiteBG.svg"/>
        
        <!-- formulaire -->
        <div class="formulaire">
            <a class="choix" id="choix-connecter" onclick="connecter()"> SE CONNECTER </a><a class="choix" id="choix-inscrire" onclick="inscrire()"> S'INSCRIRE </a><br><br>
            
            
            <!-- se connecter -->
            <div id="connecter">
                <form method="POST" action="Index.php?action=verification">
                    <label for="email" class="label">EMAIL</label><br>
                    <input id="email" type="email" name="mailconnect" class="input" placeholder="Votre email"><br><br>

                    <label for="mdp" class="label">MOT DE PASSE</label><br>
                    <input id="mdp" type="password" name="mdpconnect" class="input" placeholder ="Votre mot de passe"><br><br><br>

                    <input type="submit" name="formconnexion" value="CONNECTEZ-VOUS" class="bouton"><br><br>
                </form><br><br><br><br><br><br><br><br>
                <?php
         		if(isset($erreur)) {
            		echo '<font color="red">'.$erreur.'</font>';
         		}
         		?><br><br>
                <div class="separateur"></div>
                <div class="oublier">
                    <a href="#"> Mot de passe oublié ? </a>
                </div>
            </div>
            
            <!-- s'inscrire -->

            <div id="inscrire">
                <form method="POST" action="Index.php?action=verification">
                    <label for="utilisateur" class="label">NOM UTILISATEUR</label><br>
                    <input id="utilisateur" type="texte" placeholder="Votre nom d'utilisateur" name="pseudo" class="input" value="<?php if(isset($_SESSION['inscription']['pseudo'])) { echo $_SESSION['inscription']['pseudo']; } ?>"><br><br>

                    <label for="mail" class="label">ADRESSE MAIL</label><br>
                    <input id="mail" type="email" placeholder="Votre adresse mail" name="mail" class="input" value="<?php if(isset($_SESSION['inscription']['mail'])) { echo $_SESSION['inscription']['mail']; } ?>"><br><br>

                    <label for="mail2" class="label">CONFIRMATION DE L'ADRESSE MAIL</label><br>
                    <input id="mail2" type="email" placeholder="Confirmer votre adresse mail"name="mail2" class="input" ><br><br>

                    <label for="mdp" class="label">MOT DE PASSE</label><br>
                    <input id="mdp" type="password" placeholder="Votre mot de passe" name="mdp" class="input"><br><br>

                    <label for="mdp2" class="label">CONFIRMATION DU MOT DE PASSE</label><br>
                    <input id="mdp2" type="password" placeholder="Confirmez votre mot de passe" name="mdp2" class="input"><br><br><br>

                    <input type="submit" name="forminscription" value="VALIDER" class="bouton"><br><br>
                </form><br>
                <?php
         		if(isset($erreur)) {
            		echo '<font color="red">'.$erreur."</font>";
         		}
         		?><br>
            </div>
            
        </div>
        
        <?php
		      
	
				
        if (isset($_GET['co'])) {
            if ($_GET['co'] == "inscription") {
                echo '<script>
                document.getElementById("connecter").style.left = "-500px";
                document.getElementById("inscrire").style.left = "60px";
                document.getElementById("choix-inscrire").style.borderBottom = "3px solid #1161ee";
                document.getElementById("choix-inscrire").style.opacity = "1";
                document.getElementById("choix-connecter").style.borderBottom = "none";
                document.getElementById("choix-connecter").style.opacity = "0.7";
                </script>';
            }
        }
        if (isset($_GET['co'])) {
            if ($_GET['co'] == "connexion") {
                echo '<script>
                document.getElementById("connecter").style.left = "60px";
                document.getElementById("inscrire").style.left = "600px";
                document.getElementById("choix-connecter").style.borderBottom = "3px solid #1161ee";
                document.getElementById("choix-connecter").style.opacity = "1";
                document.getElementById("choix-inscrire").style.borderBottom = "none";
                document.getElementById("choix-inscrire").style.opacity = "0.7";
                </script>';
            }
        }
		if (isset($_SESSION['inscription']['erreur']))
		{
			echo '<script type="text/javascript">alert("' . $_SESSION['inscription']['erreur'] . '", "Information !");</script>';
			unset($_SESSION['inscription']);
			

		}
		if (isset($_SESSION['profs']['erreur']))
		{
			echo '<script type="text/javascript">alert("' . $_SESSION['profs']['erreur'] . '", "Information !");</script>';
			unset($_SESSION['profs']);
			header('Content-type: text/html; charset=UTF-8');
		}
	
        ?>
        
        <script src="Affichage/accueil.js"></script>
    </body>
</html>