<?php

require('Controller.php');

if (isset($_GET['action'])) 
{
	session_start();
	
		if ($_GET['action'] == 'creation')
		{
			if(isset($_SESSION['profs']['id']))
			{
				creation ();
			}
			else die("aucune SESSION");
		
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
			if(isset($_SESSION['profs']['id']))
			{
				modification ();
			}
			else die("aucune SESSION");
			
		}
		elseif ($_GET['action'] == 'classe')
		{
			if(isset($_SESSION['profs']['id']))
			{
			classe (); 
			}
			else die("aucune SESSION");
		}
		elseif ($_GET['action'] == 'deconnexion')
		{
			session_unset();
			session_destroy();
			header("Location: Index.php");
		}

	
}
else 
{
	
	
	Login (); 
	

	
}