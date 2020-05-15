<?php

require('Controller.php');

if (isset($_GET['action'])) 
{
	
		if ($_GET['action'] == 'creation')
		{
			creation ();
		}
		elseif ($_GET['action'] == 'validation')
		{
			
			Validation ();
		
		}
		elseif ($_GET['action'] == 'verification')
		{
			if(isset($_POST['formconnexion']))
			{
				Connexion ();
			}
			else if (isset($_POST['forminscription']))
			{
				Inscription (); 
			}
			else if (isset($_POST['AjoutClasse'])) 
			{
				AjoutClasse () ;
			}
			if (isset($_POST['suppr_classe']))
			{
				SupprimerClasse ();
			}
			else if (isset($_POST['suppr_attribut']))
			{
				Supprimer_Attribut () ;
				
			}
			else if (isset($_POST['valide_modif']))
			{
				validemodif ();
			}
		}
		elseif ($_GET['action'] == 'modification')
		{
			modification ();
		}
		elseif ($_GET['action'] == 'classe')
		{
			classe (); 
		}
		elseif ($_GET['action'] == 'deconnexion')
		{
			session_destroy();
			header("Location: Index2.php");
		}

	
}
else 
{
	
	
	if (((isset($_SESSION['inscription']['erreur']) || isset($_SESSION['profs']['erreur']))) )
	{
		session_destroy();
	}
	Login (); 
	

	
}