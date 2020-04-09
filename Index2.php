<?php

require('Controller.php');

if (isset($_GET['action'])) 
{
	if ($_GET['action'] == 'creation') 
	{
		
	}
	elseif ($_GET['action'] == 'validation') 
	{
		 
		Validation ();
		// vider la variable unset ($_POST);
		
	}
	elseif ($_GET['action'] == 'classe')
	{
		
	}
	elseif ($_GET['action'] == 'deconnexion')
	{
	
	}
}
else 
{
	creation (); 
}