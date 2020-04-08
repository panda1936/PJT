<?php

function addColumn($bdd, $nameColumn){
    $sql = "ALTER TABLE IdEleve ADD $nameColumn INT DEFAULT 0";
    $bdd->exec($sql);
    echo 'Colonne ajoutee';
}

function remColumn($bdd, $nameColumn){
    $rem_col = "ALTER TABLE IdEleve DROP COLUMN $nameColumn";
    $bdd->exec($rem_col);
    echo 'Colonne supprimee';  
}

function addStudent($bdd, $nom, $prenom){
    $requete = $bdd->prepare("INSERT INTO Classe(IdEleve, nom, prenom) VALUES (?,?,?)");
    $requete-> execute(array(0, $nom, $prenom));
    echo "Inserer eleve";
}

function remStudent($bdd, $id){
    $rem_stud = "DELETE FROM Classe WHERE IdEleve = $id";
    $bdd->exec($rem_stud);
    echo 'Colonne ajoutee';
}

function modifClasse($bdd, $nameColumn, $id, $value){
    $modif = "UPDATE Classe SET $nameColumn = $value WHERE IdEleve = $id";
    $bdd->exec($modif);
    echo 'Valeur ajoutee';
}

function ajouterClasse () 
{
	extract(filter_input_array(INPUT_POST));
	$fichier = $_FILES["userfile"]["name"];
	if ($fichier)
	{
		$fp = fopen($_FILES["userfile"]["name"], "r");
		
		while (!feof($fp))
		{
			$ligne = fgets ($fp,4096);
			$liste = explode(";", $ligne);
			$table = filter_input(INPUT_POST, 'userfile');
			
			$liste [0] = ( isset ($liste [0]) ) ? $liste [0] : NULL;
			$liste [1] = ( isset ($liste [1]) ) ? $liste [1] : NULL;
			$liste [2] = ( isset ($liste [2]) ) ? $liste [2] : NULL;
			
			$champs1= $liste[0];
			$champs2 = $liste[1];
			$champs3 = $liste[2];
			
			if ($champs1 != '')
			{
				try
				{
					echo 'test'; 
					$bdd = new PDO('mysql:host=localhost;dbname=eleve;charset=utf8', 'root', '');
				}
				catch (Exception $e)
				{
				        die('Erreur : ' . $e->getMessage());
				}
				
				$sql = ("INSERT INTO student (id,nom, prenom, classe, retard ) VALUES (0, '$champs1' , '$champs2', '$champs3', 0) ");
				$result = $bdd->query($sql); 
	
			}
			
		}
		fclose($fp);
	}
}

function newClasse ()
{
	if (isset($_POST['prenom']))
	{
		$nom= $_POST['prenom'];
		$sql = ("CREATE TABLE " .$nom."(
	    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	    `nom` TEXT NOT NULL
	    )");
		
		$req = $bdd->query($sql);
	
		if ($req === false)
			echo 'ERREUR : ', print_r($bdd->errorInfo());
		else
			echo 'table creer';
			header('Refresh: 0;URL=' . $_SERVER['HTTP_REFERER']);
	}
}



try{
    $bdd = new PDO('mysql:host=localhost; dbname=Classe; charset=utf8','root', '');
}catch(PDOException $e){
    die('Erreur : '.$e->getMessage());
}


if(!empty($_POST['envoi'])){ // si formulaire soumis
    $func = 'addColumn';
    $func($bdd, $_POST['pseudo']);
}

?>


<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	Pseudo : <input type="text" name="pseudo" placeholder="saisir...">
 	<input type="submit" name="envoi">
</form>
