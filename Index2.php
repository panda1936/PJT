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
	
	}
}
else 
{
	creation ();
	
}