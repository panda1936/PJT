<?php

require ('Model/global.php');

function creation()
{
	$idProf =$_SESSION['profs']['id'];
	$all =  allClasse($idProf);
	require ('Affichage/creation_classe.html');
}

function classe ()
{

	$idProf =$_SESSION['profs']['id'];
	
	if(isset($_GET["classe"]))
	{
		
		$nameClasse = $_GET["classe"];

	}
	
	else
	{
		$all = allClasse($idProf);
		$nameClasse = $all[0][0];
	}
	$tab = infoClasse($nameClasse, $idProf);
	
	require ('Affichage/PagePrincipale.php');
	
}

function Login ()
{

	require ('Affichage/Connexion.php');

}


function modification ()
{
	// recuperer le nom de la classe 
	$nameClasse = $_GET['classe'] ;
	$taille = infoClasse($nameClasse, $_SESSION['profs']['id']);
	$attribut = nomColonne($nameClasse);
	require ('Affichage/page_modif.html');
}

function validemodif ()
{

		$nom_classe = $_POST['valide_modif'] ;
		$rang = $_POST['rang'];
		$colonne = $_POST['colonne'];
		$nb_place = $_POST['rang'] * $_POST['colonne'] ;
		if ($nb_place )
		{

			modifClasse($nom_classe, $_SESSION['profs']['id'], (int)$rang, (int)$colonne)	;
			if ($_POST['placement'] == "Aleatoire")
			{
				triAlea($nom_classe, $colonne, $rang);
					
			}
			else
			{
				triAlpha($nom_classe, $colonne, $rang);
			}
			echo '<script type="text/javascript">alert("'. utf8_encode (  "Classe modifi�e" ) .'", "Information !");</script>';
			header("Refresh: 0; URL=Index.php?action=classe&classe=$nom_classe");
		}
		else
		{

			header("location:".  $_SERVER['HTTP_REFERER']);
		}
	
}

function Supprimer_Attribut ()
{
	$nameTab = $_POST['suppr_attribut'] ;
	$nameColumn = ($_POST['attribut']) ;
	remColumn($nameTab, $nameColumn);
	echo '<script type="text/javascript">alert("'. utf8_encode (  "Attribut supprim�" ) .'", "Information !");</script>';
	header("Refresh: 0; URL=Index.php?action=classe&classe=$nameTab");
	
}

function SupprimerClasse ()
{
	$nameTab = $_POST['suppr_classe'] ;
	if (count ( allClasse($_SESSION['profs']['id']))>1)
	{
		deleteClasse($nameTab, $_SESSION['profs']['id']);
		echo '<script type="text/javascript">alert("'. utf8_encode (  "Classe supprim�e" ) .'", "Information !");</script>';
		header('Refresh: 0; URL=Index.php?action=classe');
	}
	else 
	{
		echo '<script type="text/javascript">alert("'. utf8_encode (  "Vous devez garder au moins 1 classe " ) .'", "Information !");</script>';
		header('Refresh: 0; URL=Index.php?action=classe');
	}
	
	
}




function Connexion ()
{
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
			$all = allClasse($id);
			if (count($all)>0)
			{
				header("Location: Index.php?action=classe");
			}
			else
			{
				header("Location: Index.php?action=creation");
			}
		}
		else 
		{
	
			$erreur = "Adresse mail ou mot de passe incorrect  !";
			$_SESSION['profs']['erreur'] = $erreur;
			header("location: Index.php?co=connexion" );
		}
	} 
	else 
	{
		$erreur = "Veuillez compl�ter tous les champs ! !";
		$_SESSION['profs']['erreur'] = $erreur;
		header("location: Index.php?co=connexion" );
	}
}

function Inscription () 
{
	
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
							header("location: Index.php?co=connexion" );
	
						} else {
							$erreur = "Vos mots de passe ne correspondent pas !";
							$_SESSION['inscription']['erreur']=$erreur;
							header("location: Index.php?co=inscription" );
						}
					} else {
						$erreur = "Cette adresse mail est d�j� utilisee !";
						$_SESSION['inscription']['erreur']=$erreur;
						header("location: Index.php?co=inscription" );
					}
				} else {
					$erreur = "Cette adresse mail n'est pas valide !";
					$_SESSION['inscription']['erreur']=$erreur;
					header("location: Index.php?co=inscription" );
				}
			} else {
				$erreur = "Ces adresses mail ne correspondent pas !";
				$_SESSION['inscription']['erreur']=$erreur;
				header("location: Index.php?co=inscription" );
			}
		} else {
			$erreur = "Votre pseudo ne doit pas d�passer 255 charact�res !";
			$_SESSION['inscription']['erreur']=$erreur;
			header("location: Index.php?co=inscription" );
		}
	} else {
			
		$erreur = "Veuillez completer tous les champs !";
		$_SESSION['inscription']['erreur']=$erreur;
		header("location: Index.php?co=inscription" );
	}
}

function AjoutClasse ()
{
	
	$nom_classe = htmlspecialchars($_POST['nom_classe']);
	$rang = htmlspecialchars($_POST['rang']);
	$colonne = htmlspecialchars($_POST['colonne']);
	
	
	#conservation des �lement en cas d'erreur
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
				
				newClasse($nom_classe, $_SESSION['profs']['id'], (int)$rang, (int)$colonne, (int)count(file($_FILES["test"]["tmp_name"])));
				ajouterClasse ($nom_classe, $_FILES["test"]["tmp_name"]);
					
				if ($_POST['placement'] == "Aleatoire")
				{
					
					triAlea($nom_classe, $colonne, $rang);
	
				}
				else
				{
			
					triAlpha($nom_classe, $colonne, $rang);
				}
				
					
				unlink ($_FILES["test"]["tmp_name"]);
				unset($_SESSION['connexion']);
				echo '<script type="text/javascript">alert("'. utf8_encode (  "Classe cr��e" ) .'", "Information !");</script>';
				header('Refresh: 0; URL=Index.php?action=classe ');
				
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




