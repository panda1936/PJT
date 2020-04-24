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
			
			verification ();
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