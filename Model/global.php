<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php

function connect(){
	try{
		$bdd = new PDO('mysql:host=localhost;dbname=global;charset=utf8','root','');
	}
	catch(PDOException $e){
		die('Erreur : '.$e->getMessage());
	}
	return $bdd;
}

function addColumn($nameTab, $nameColumn){
	$bdd = connect();
    $add_column = "ALTER TABLE $nameTab ADD $nameColumn INT DEFAULT 0";
    $bdd->exec($add_column);
}

function remColumn($nameTab, $nameColumn){
	$bdd = connect();
	if (($nameColumn != 'nom')&&($nameColumn != 'prenom')&&($nameColumn != 'idEleve')&&($nameColumn != 'x')&&($nameColumn != 'y')&&($nameColumn != 'commentaire')){
		$rem_col = "ALTER TABLE $nameTab DROP COLUMN $nameColumn";
		$bdd->exec($rem_col);
	}
}

function addStudent($nameTab, $nom, $prenom){
	$bdd = connect();
    $add_stud = $bdd->prepare("INSERT INTO $nameTab(nom, prenom, idEleve, x, y, commentaire) VALUES (?,?,?,?,?,?)");
    $add_stud-> execute(array($nom, $prenom, 0, 0, 0, 'test'));
}

function addProf($pseudo, $mdp, $mail){
	$bdd = connect();
    $add_prof = $bdd->prepare("INSERT INTO profs(idProf, pseudo, mdp, mail) VALUES (?,?,?,?)");
    $add_prof-> execute(array(0, $pseudo, $mdp, $mail));
}

function remStudent($nameTab, $id){
	$bdd = connect();
    $rem_stud = "DELETE FROM $nameTab WHERE IdEleve = $id";
    $bdd->exec($rem_stud);
}

function modifClasse($nameTab, $nameColumn, $id, $value){
	$bdd = connect();
    $modif = "UPDATE $nameTab SET $nameColumn = $value WHERE IdEleve = $id";
    $bdd->exec($modif);
}

function relation($idProf, $nameClasse){
	$bdd = connect();
    $add_relation = $bdd->prepare("INSERT INTO profclasse(idProf, nomClasse) VALUES (?,?)");
    $add_relation-> execute(array($idProf, $nameClasse));
}

function allClasse($idProf){
	$bdd = connect();
	$query = "SELECT nomclasse FROM profclasse WHERE idProf = $idProf";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$NbreData = $bdd_select->rowCount();
	$rowAll = $bdd_select->fetchAll();

	return $rowAll;
	return $rowAll ;
}

function triAlea($nomClasse, $nbColonne, $nbLigne){
	$bdd = connect();
	$x = 0;
	$y = 0;
	$query = "SELECT * FROM $nomClasse ORDER BY RAND()";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$NbreData = $bdd_select->rowCount();
	$rowAll = $bdd_select->fetchAll();

	if ($NbreData != 0) {
		foreach ( $rowAll as $row ) {
				$id = $row['idEleve'];
				$modif = "UPDATE $nomClasse SET x = $x, y = $y WHERE IdEleve = $id";
				$bdd->exec($modif);

				if($x < $nbColonne){
					$x++;
				} else {
					$x = 0;
					$y++;
				}
		}
	}
	else {
		echo 'pas de donnée à afficher <br/>';
	}
}

function triAlpha($nomClasse, $nbColonne, $nbLigne){
	$bdd = connect();
	$x = 0;
	$y = 0;
	$query = "SELECT * FROM $nomClasse ORDER BY nom";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$NbreData = $bdd_select->rowCount();
	$rowAll = $bdd_select->fetchAll();

	if ($NbreData != 0) {
		foreach ( $rowAll as $row ) {
				$id = $row['idEleve'];
				$modif = "UPDATE $nomClasse SET x = $x, y = $y WHERE IdEleve = $id";
				$bdd->exec($modif);

				if($x < $nbColonne){
					$x++;
				} else {
					$x = 0;
					$y++;
				}
		}
	}
	else {
		echo 'pas de donnée à afficher <br/>';
	}
}

function securNom($nameTab){
	$bdd = connect();
	
	$query = "SELECT * FROM profclasse";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$NbreData = $bdd_select->rowCount();
	$rowAll = $bdd_select->fetchAll();

	foreach ($rowAll as $row) {
		$nom = $row['nomClasse'];
		if ($nameTab == $nom){
			return false;
		}
	}
	return true;
}

function securIdent($mail, $motDePasse){
	$bdd = connect();
	$query = "SELECT * FROM profs";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$NbreData = $bdd_select->rowCount();
	$rowAll = $bdd_select->fetchAll();

	foreach ($rowAll as $row) {
		$mailTab = $row['mail'];
		$mdpTab = $row['mdp'];
		if (($mail == $mailTab) && ($motDePasse == $mdpTab)){
			return $row['idProf'];
		}
	}
	return -1;
}

function recupPseudo ($idProf){
	$bdd = connect();
	$query = "SELECT * FROM profs";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$NbreData = $bdd_select->rowCount();
	$rowAll = $bdd_select->fetchAll();

	foreach ($rowAll as $row) {
		$idProfTab = $row['idProf'];
		if ($idProf == $idProfTab){
			return $row['pseudo'];
		}
	}
	return "";
}


function securMail($mail){
	$bdd = connect();
	$query = "SELECT * FROM profs";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$NbreData = $bdd_select->rowCount();
	$rowAll = $bdd_select->fetchAll();

	foreach ($rowAll as $row) {
		$mailTab = $row['mail'];
		if ($mail == $mailTab){
			return false;
		}
	}
	return true;
}


function ajouterClasse ($nameClasse, $file){
	$bdd = connect();
	if ($file)
	{
		$fp = fopen($file, "r");
		
		while (!feof($fp))
		{
			$ligne = fgets ($fp,4096);
			$liste = explode(";", $ligne);
			
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




function newClasse($nameTab, $idProf){
	$bdd = connect();
	$sql = ("CREATE TABLE " .$nameTab."(
		`nom` VARCHAR(25) NOT NULL,
		`prenom` VARCHAR(25) NOT NULL, 
		`idEleve` INT(25) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`x` INT(40) DEFAULT 0,
		`y` INT(40) DEFAULT 0,
		`commentaire` VARCHAR(250))");
		
	$req = $bdd->query($sql);
	
	if ($req === false)
		echo 'ERREUR : ', print_r($bdd->errorInfo());
	else
		relation($idProf, $nameTab);
}

/*
if(!empty($_POST['addColumn'])){
	$nameTab = 'classe';
    $func = 'addColumn';
    $func($nameTab, $_POST['nameColumn']);
}

if(!empty($_POST['remColumn'])){
	$nameTab = 'classe2';
    $func = 'remColumn';
    $func($nameTab, $_POST['nameColumn']);
}

if(!empty($_POST['addStudent'])){
	$nameTab = 'classe3';
    $func = 'addStudent';
    $func($nameTab, $_POST['firstNameStud'], $_POST['secondNameStud']);
}

if(!empty($_POST['submit'])){
    $func = 'ajouterClasse';
    $func('classe1');
}

if(!empty($_POST['newClasse'])){
    $func = 'newClasse';
    $func($_POST['nameTable'], $_POST['idProf']);
}

if(!empty($_POST['retrieve'])){
    $func = 'allClasse';
    $func($_POST['idProf']);
}

if(!empty($_POST['triAlea'])){
    $func = 'triAlea';
    $func($_POST['nameTable'], '2', '3');
}

if(!empty($_POST['triAlpha'])){
    $func = 'triAlpha';
    $func($_POST['nameTable'], '2', '3');
}

if(!empty($_POST['addProf'])){
    $func = 'addProf';
    $func($_POST['pseudo'], $_POST['mdp'], $_POST['mail']);
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

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	Tri eleve aleatoire: <input type="text" name="nameTable" placeholder="saisir...">
 	<input type="submit" name="triAlea">
</form>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	Tri eleve alphabétique: <input type="text" name="nameTable" placeholder="saisir...">
 	<input type="submit" name="triAlpha">
</form>

<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	Ajouter prof : 
	Pseudo <input type="text" name="pseudo" placeholder="saisir...">
	mdp <input type="text" name="mdp" placeholder="saisir...">
	mail <input type="text" name="mail" placeholder="saisir...">
 	<input type="submit" name="addProf">
</form>
*/
?>