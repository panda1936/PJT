<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Hosptimal</title>
        <link rel="icon" href="icone.png" type="x-icon">
        <link rel="stylesheet" href="Affichage/style/accueil.css">
    </head>
    
    <body>
        <h1 class="titre">HOSPTIMAL</h1>
        
        <!-- formulaire -->
        <div class="formulaire">
            <a class="choix" id="choix-connecter" onclick="connecter()"> SE CONNECTER </a><a class="choix" id="choix-inscrire" onclick="inscrire()"> S'INSCRIRE </a><br><br><br><br>
            
            <!-- se connecter -->
            <?php
            session_start();
            ?>
            <div id="connecter">
                <form method="POST" action="Index2.php?action=verification">
                    <label for="email" class="label">EMAIL</label><br>
                    <input id="email" type="email" name="mailconnect" class="input" placeholder="Votre email"><br><br>

                    <label for="motdepasse" class="label">MOT DE PASSE</label><br>
                    <input id="motdepasse" type="password" name="mdpconnect" class="input" placeholder ="Votre mot de passe"><br><br><br>

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
                <form method="POST" action="Index2.php?action=verification">
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
			if (isset($_SESSION['inscription']['erreur']))
			{
				echo '<script type="text/javascript">alert("' . $_SESSION['inscription']['erreur'] . '", "Information !");</script>';
				unset($_SESSION['inscription']);

			}
				
		?>
        <script src="Affichage/accueil.js"></script>
		
    </body>
</html>