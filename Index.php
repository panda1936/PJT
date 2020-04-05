<?php

require('Controller.php');

if (isset($_GET['action'])) 
{
	if ($_GET['action'] == 'creation') 
	{
		
	}
	elseif ($_GET['action'] == 'modification') 
	{
		
	}
	elseif ($_GET['action'] == 'classe')
	{
		
	}
}
else 
{
	require ('/Affichage/creation_classe.html');
}