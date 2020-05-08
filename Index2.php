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
			
			verification ();
		}
		elseif ($_GET['action'] == 'modification')
		{
			modification ();
		}
		elseif ($_GET['action'] == 'validermodif')
		{
			if ($_POST['Supprimer'])
			{
				SupprimerClasse ();
			}
			else
			{
				validemodif ();
			}
		}
		elseif ($_GET['action'] == 'classe')
		{
		
		}
		elseif ($_GET['action'] == 'deconnexion')
		{
			session_destroy();
		}

	
}
else 
{
	
	
	if (((isset($_SESSION['inscription']['erreur']) || isset($_SESSION['profs']['erreur']))) )
	{
		session_destroy();
	}
	classe ();
	

	
}