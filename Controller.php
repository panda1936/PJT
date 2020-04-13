<?php

#require ('Model/global.php');

function creation()
{
	require ('Affichage/creation_classe.html');
}

function classe ()
{

	require ('Affichage/index.php');
	
}

function validation()
{
	
}




function verification ()
{
	//connexion
	if(isset($_POST['formconnexion']))
	{
		$bdd = new PDO('mysql:host=localhost; dbname=global; charset=utf8','root', '');
		session_start();
		$mailconnect = htmlspecialchars($_POST['mailconnect']);
		$mdpconnect = sha1($_POST['mdpconnect']);
		if(!empty($mailconnect) AND !empty($mdpconnect)) {
			$requser = $bdd->prepare("SELECT * FROM profs WHERE mail = ? AND motdepasse = ?");
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
	//inscription
	else if (isset($_POST['forminscription']))
	{
	
		$bdd = new PDO('mysql:host=localhost; dbname=global; charset=utf8','root', '');
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$mail = htmlspecialchars($_POST['mail']);
		$mail2 = htmlspecialchars($_POST['mail2']);
		$mdp = sha1($_POST['mdp']);
		$mdp2 = sha1($_POST['mdp2']);
		if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])) {
			$pseudolength = strlen($pseudo);
			if($pseudolength <= 255) {
				if($mail == $mail2) {
					if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
						$reqmail = $bdd->prepare("SELECT * FROM prof WHERE mail = ?");
						$reqmail->execute(array($mail));
						$mailexist = $reqmail->rowCount();
						if($mailexist == 0) {
							if($mdp == $mdp2) {
								$insertmbr = $bdd->prepare("INSERT INTO profs(pseudo, mail, motdepasse) VALUES(?, ?, ?)");
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
	//ajout de classe
	else 
	{
	
		$fichier = $_FILES["test"]["name"];
		if($fichier)
		{
			$eleve = count(file($_FILES["test"]["tmp_name"]));
			echo ($eleve);
			unlink ($_FILES["test"]["tmp_name"]);
		}
		else
		{
			echo ($_POST['rang']);
		}
		
	
	}
	
}




