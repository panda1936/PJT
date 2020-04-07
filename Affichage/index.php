<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Hosptimal</title>
        <link rel="icon" href="icone.png" type="x-icon">
        <link rel="stylesheet" href="style/accueil.css">
    </head>
    
    <body>
        <h1 class="titre">HOSPTIMAL</h1>
        
        <!-- formulaire -->
        <div class="formulaire">
            <a class="choix" id="choix-connecter" onclick="connecter()"> SE CONNECTER </a><a class="choix" id="choix-inscrire" onclick="inscrire()"> S'INSCRIRE </a><br><br><br><br>
            
            <!-- se connecter -->
            <?php
            session_start();

            $bdd = new PDO('mysql:host=localhost;dbname=membres;charset=utf8', 'root', '');

            if(isset($_POST['formconnexion'])) {
                $mailconnect = htmlspecialchars($_POST['mailconnect']);
                $mdpconnect = sha1($_POST['mdpconnect']);
                if(!empty($mailconnect) AND !empty($mdpconnect)) {
                    $requser = $bdd->prepare("SELECT * FROM test WHERE mail = ? AND motdepasse = ?");
                    $requser->execute(array($mailconnect, $mdpconnect));
                    $userexist = $requser->rowCount();
                    if($userexist > 0) {
                        $userinfo = $requser->fetch();
                        if($userinfo['user_type'] == 'admin') {
                            $_SESSION['id'] = $userinfo['id'];
                            $_SESSION['pseudo'] = $userinfo['pseudo'];
                            $_SESSION['mail'] = $userinfo['mail'];
                            $_SESSION['user_type'] == 'admin';
                            header("Location: accueil_admin.php?id=".$_SESSION['id']);
                        }
                        else{
                            $_SESSION['id'] = $userinfo['id'];
                            $_SESSION['pseudo'] = $userinfo['pseudo'];
                            $_SESSION['mail'] = $userinfo['mail'];
                            $_SESSION['user_type'] == 'user';
                            header("Location: creation_classe.html?id=".$_SESSION['id']);
                        }
                    }
                    else {
                        $erreur = "Adresse mail ou mot de passe incorrect  !";
                    }
                } else {
                    $erreur = "Veuillez compléter tous les champs ! !";
                }
            }
            ?>
            <div id="connecter">
                <form method="POST" action=''>
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
            <?php

            $bdd = new PDO('mysql:host=localhost;dbname=membres;charset=utf8', 'root', '');

            if(isset($_POST['forminscription'])) {
                $pseudo = htmlspecialchars($_POST['pseudo']); /*htmlspecialchars pr eviter la faille xss*/
                $mail = htmlspecialchars($_POST['mail']);
                $mail2 = htmlspecialchars($_POST['mail2']);
                $mdp = sha1($_POST['mdp']);
                $mdp2 = sha1($_POST['mdp2']);
                if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])) {
                    $pseudolength = strlen($pseudo);
                    if($pseudolength <= 255) {
                        if($mail == $mail2) {
                            if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                                $reqmail = $bdd->prepare("SELECT * FROM test WHERE mail = ?");
                                $reqmail->execute(array($mail));
                                $mailexist = $reqmail->rowCount();
                                if($mailexist == 0) {
                                    if($mdp == $mdp2) {
                                        $insertmbr = $bdd->prepare("INSERT INTO test(pseudo, mail, motdepasse, user_type) VALUES(?, ?, ?,'user')");
                                        $insertmbr->execute(array($pseudo, $mail, $mdp));
                                        $erreur = "Votre compte a bien été crée ! <a href=\"ecole.php\">Me connecter</a>";
                                    } else {
                                        $erreur = "Vos mots de passe ne correspondent pas !";
                                    }
                                } else {
                                    $erreur = "Cette adresse mail est déjà utilisée !";
                                }
                            } else {
                                $erreur = "Cette adresse mail n'est pas valide !";
                            }
                        } else {
                            $erreur = "Ces adresses mail ne correspondent pas !";
                        }
                    } else {
                        $erreur = "Votre pseudo ne doit pas dépasser 255 charactères !";
                    }
                } else {
                    $erreur = "Veuillez compléter tous les champs !";
                }
            }

            ?>
            <div id="inscrire">
                <form method="POST" action=''>
                    <label for="utilisateur" class="label">NOM UTILISATEUR</label><br>
                    <input id="utilisateur" type="texte" placeholder="Votre nom d'utilisateur" name="pseudo" class="input" value="<?php if(isset($pseudo)) { echo $pseudo; } ?>"><br><br>

                    <label for="mail" class="label">ADRESSE MAIL</label><br>
                    <input id="mail" type="email" placeholder="Votre adresse mail" name="mail" class="input" value="<?php if(isset($mail)) { echo $mail; } ?>"><br><br>

                    <label for="mail2" class="label">CONFIRMATION DE L'ADRESSE MAIL</label><br>
                    <input id="mail2" type="email" placeholder="Confirmer votre adresse mail"name="mail2" class="input" value="<?php if(isset($mail2)) { echo $mail2; } ?>"><br><br>

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
        
        <script src="accueil.js"></script>
    </body>
</html>