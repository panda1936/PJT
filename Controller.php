<?php

require ('Model/global.php');

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
		session_start();
		$mailconnect = htmlspecialchars($_POST['mailconnect']);
		$mdpconnect = sha1($_POST['mdpconnect']);
		
		if(!empty($mailconnect) AND !empty($mdpconnect)) 
		{
			$id = securIdent($mailconnect, $mdpconnect);
			if( $id >= 0) 
			{
				/* recuperation nom et id $userinfo = $requser->fetch();*/
				
				$_SESSION['profs']['id'] = $id;
				$_SESSION['profs']['pseudo'] =recupPseudo ($id) ;
				if (recupPseudo ($id))
				{
					header("Location: Index2.php?action=creation");
				}
				else 
				{
					echo ("test");
				}
			}
			else {
				
				$erreur = "Adresse mail ou mot de passe incorrect  !";
				$_SESSION['profs']['erreur'] = $erreur;
				header("location: Index2.php?co=connexion" );
			}
		} else {
			$erreur = "Veuillez compléter tous les champs ! !";
			$_SESSION['profs']['erreur'] = $erreur;
			header("location: Index2.php?co=connexion" );
		}
	}
	//inscription
	else if (isset($_POST['forminscription']))
	{
		session_start();
	
		$pseudo = htmlspecialchars($_POST['pseudo']);
		$mail = htmlspecialchars($_POST['mail']);
		$mail2 = htmlspecialchars($_POST['mail2']);
		$mdp = sha1($_POST['mdp']);
		$mdp2 = sha1($_POST['mdp2']);
		
		$_SESSION['inscription']['pseudo']=$pseudo;
		$_SESSION['inscription']['mail']=$mail;
		
		if(!empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2'])) 
		{
			$pseudolength = strlen($pseudo);
			if($pseudolength <= 255) {
				if($mail == $mail2) {
					if(filter_var($mail, FILTER_VALIDATE_EMAIL)) {
						if(securMail($mail)) {
							if($mdp == $mdp2) {
						
								addProf($pseudo, $mdp, $mail);
								unset($_SESSION['inscription']);
								$_SESSION['profs']['erreur'] = "Inscription reussie ";
								header("location: Index2.php?co=connexion" );
								
							} else {
								$erreur = "Vos mots de passe ne correspondent pas !";
								$_SESSION['inscription']['erreur']=$erreur;
								header("location: Index2.php?co=inscription" );
							}
						} else {
							$erreur = "Cette adresse mail est déjà utilisee !";
							$_SESSION['inscription']['erreur']=$erreur;
							header("location: Index2.php?co=inscription" );
						}
					} else {
						$erreur = "Cette adresse mail n'est pas valide !";
						$_SESSION['inscription']['erreur']=$erreur;
						header("location: Index2.php?co=inscription" );
					}
				} else {
					$erreur = "Ces adresses mail ne correspondent pas !";
					$_SESSION['inscription']['erreur']=$erreur;
					header("location: Index2.php?co=inscription" );
				}
			} else {
				$erreur = "Votre pseudo ne doit pas dépasser 255 charactères !";
				$_SESSION['inscription']['erreur']=$erreur;
				header("location: Index2.php?co=inscription" );
			}
		} else {
			
			$erreur = "Veuillez completer tous les champs !";
			$_SESSION['inscription']['erreur']=$erreur;
			header("location: Index2.php?co=inscription" );
		}
	}
	
	

	//ajout de classe
	else 
	{
		session_start();
		
		$nom_classe = htmlspecialchars($_POST['nom_classe']);
		$rang = htmlspecialchars($_POST['rang']);
		$colonne = htmlspecialchars($_POST['colonne']);

		
		#conservation des élement en cas d'erreur 
		$_SESSION['connexion']['nom_classe'] = $nom_classe;
		$_SESSION['connexion']['rang'] = $rang;
		$_SESSION['connexion']['colonne'] = $colonne;
		
		$fichier = $_FILES["test"]["name"];
		
		if($fichier and $nom_classe and $rang and $colonne )
		{
			# verification $nom_classe
			
			if (securNom($nom_classe))
			{
				$nb_place = $_POST['rang'] * $_POST['colonne'] ; 
				if ($nb_place >= count(file($_FILES["test"]["tmp_name"])) )
				{
					newClasse($nom_classe, $_SESSION['profs']['id']);
					ajouterClasse ($nom_classe, $_FILES["test"]["tmp_name"]);
					
					if ($_POST['placement'] == "Aléatoire")
					{
						triAlea($nom_classe, $colonne, $rang);
						
					}
					else 
					{
						triAlpha($nom_classe, $colonne, $rang);
					}
					
					unlink ($_FILES["test"]["tmp_name"]);
					unset($_SESSION['connexion']);
				}
				else 
				{
					
					$erreur = "il y a plus d'eleves que de places";
					$_SESSION['connexion']['erreur'] = $erreur;
					header("location:".  $_SERVER['HTTP_REFERER']);
				}
			}
			else 
			{
				$erreur = "nom de classe deja utiliser";
				$_SESSION['connexion']['erreur'] = $erreur;
				header("location:".  $_SERVER['HTTP_REFERER']);
			}
			
		}
		else
		{
			$erreur = "Il manque un element";
			$_SESSION['connexion']['erreur'] = $erreur;
			header("location:".  $_SERVER['HTTP_REFERER']);
		}
		
	
	}
	
}




