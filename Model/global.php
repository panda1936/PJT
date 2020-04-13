<?php

function addColumn($bdd, $nameTab, $nameColumn){
    $add_column = "ALTER TABLE $nameTab ADD $nameColumn INT DEFAULT 0";
    $bdd->exec($add_column);
    echo 'Colonne ajoutee';
}

function remColumn($bdd, $nameTab, $nameColumn){
	if (($nameColumn != 'nom')&&($nameColumn != 'prenom')&&($nameColumn != 'idEleve')&&($nameColumn != 'x')&&($nameColumn != 'y')&&($nameColumn != 'commentaire')){
		$rem_col = "ALTER TABLE $nameTab DROP COLUMN $nameColumn";
		$bdd->exec($rem_col);
		echo 'Colonne supprimee';
	}
	else{
		echo 'Tu ne peux pas supprimer cette colonne';
	}
}

function addStudent($bdd, $nameTab, $nom, $prenom){
    $add_stud = $bdd->prepare("INSERT INTO $nameTab(nom, prenom, idEleve, x, y, commentaire) VALUES (?,?,?,?,?,?)");
    $add_stud-> execute(array($nom, $prenom, 0, 0, 0, 'test'));
    echo "Inserer eleve";
}

function remStudent($bdd, $nameTab, $id){
    $rem_stud = "DELETE FROM $nameTab WHERE IdEleve = $id";
    $bdd->exec($rem_stud);
    echo 'Colonne ajoutee';
}

function modifClasse($bdd, $nameTab, $nameColumn, $id, $value){
    $modif = "UPDATE $nameTab SET $nameColumn = $value WHERE IdEleve = $id";
    $bdd->exec($modif);
    echo 'Valeur ajoutee';
}

function relation($bdd, $idProf, $nameClasse){
    $add_relation = $bdd->prepare("INSERT INTO $profClasse(idProf, nomClasse) VALUES (?,?)");
    $add_relation-> execute(array($idProf, $nameClasse));
    echo "Relation prof/classe";
}

function allClasse($bdd, $idProf){
	$query = "SELECT nomclasse FROM profclasse WHERE idProf = $idProf";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$NbreData = $bdd_select->rowCount();
	$rowAll = $bdd_select->fetchAll();

	if ($NbreData != 0) 
	{
		foreach ( $rowAll as $row ) 
		{
			echo $row['nomclasse'];
			echo '<br/>';
		}
	} 
	else {
		echo 'pas de donnée à afficher <br/>';
	}
}


function ajouterClasse ($bdd, $nameClasse ,$file) {
	if ($file)
	{
		$fp = fopen($file, "r");
		
		while (!feof($fp))
		{
			$ligne = fgets ($fp,4096);
			$liste = explode(";", $ligne);
			$table = filter_input(INPUT_POST, 'userfile');
			
			$liste [0] = ( isset ($liste [0]) ) ? $liste [0] : NULL;
			$liste [1] = ( isset ($liste [1]) ) ? $liste [1] : NULL;
			
			$champs1= $liste[0];
			$champs2 = $liste[1];
			
			if ($champs1 != '')
			{
				$sql = ("INSERT INTO $nameClasse(nom, prenom) VALUES ( '$champs1' , '$champs2') ");
				$result = $bdd->query($sql); 
			}
		}
		fclose($fp);
	}
}

function newClasse($bdd, $nameTab, $idProf)
{
	$sql = ("CREATE TABLE " .$nameTab."(
		`nom` VARCHAR(25) NOT NULL,
		`prenom` VARCHAR(25) NOT NULL, 
		`idEleve` INT(25) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`x` INT(40) DEFAULT NULL,
		`y` INT(40) DEFAULT NULL,
		`commentaire` VARCHAR(250))");
		
	$req = $bdd->query($sql);
	
	if ($req === false)
		echo 'ERREUR : ', print_r($bdd->errorInfo());
	else
		echo 'table creer';
		header('Refresh: 0;URL=' . $_SERVER['HTTP_REFERER']);
		relation($bdd, $idProf, $nameTab);
}


try{
    $bdd = new PDO('mysql:host = localhost; dbname = global; charset = utf8','root', '');
}catch(PDOException $e){
	die('Erreur : '.$e->getMessage());
}

/*
if(!empty($_POST['addColumn'])){
	$nameTab = 'classe2';
    $func = 'addColumn';
    $func($bdd, $nameTab, $_POST['nameColumn']);
}

if(!empty($_POST['remColumn'])){
	$nameTab = 'classe2';
    $func = 'remColumn';
    $func($bdd, $nameTab, $_POST['nameColumn']);
}

if(!empty($_POST['addStudent'])){
	$nameTab = 'test';
    $func = 'addStudent';
    $func($bdd, $nameTab, $_POST['firstNameStud'], $_POST['secondNameStud']);
}

if(!empty($_POST['submit'])){
    $func = 'ajouterClasse';
    $func($bdd, 'classe1');
}

if(!empty($_POST['newClasse'])){
    $func = 'newClasse';
    $func($bdd, $_POST['nameTable'], $_POST['idProf']);
}

if(!empty($_POST['retrieve'])){
    $func = 'allClasse';
    $func($bdd, $_POST['idProf']);
}

?>


<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	Colonne a ajouter : <input type="text" name="nameColumn" placeholder="saisir...">
 	<input type="submit" name="addColumn">
</form>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	Colonne a supprimer : <input type="text" name="nameColumn" placeholder="saisir...">
 	<input type="submit" name="remColumn">
</form>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	Ajouter eleve : 
	NOM <input type="text" name="firstNameStud" placeholder="saisir...">
	PRENOM <input type="text" name="secondNameStud" placeholder="saisir...">
 	<input type="submit" name="addStudent">
</form>

<form action="newfile" enctype = "multipart/form-data" method="post">
	<input name = "userfile" type = "file" value = "table" />
	<input name = "submit" type = "submit" />
</form>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	Nouvelle classe : <input type="text" name="nameTable" placeholder="saisir...">
	ID prof : <input type="text" name="idProf" placeholder="saisir...">
 	<input type="submit" name="newClasse">
</form>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	Id prof : <input type="text" name="idProf" placeholder="saisir...">
 	<input type="submit" name="retrieve">
</form>
*/
?>
