<?php

function connect(){
	try{
		$bdd = new PDO('mysql:host=localhost;dbname=global;charset=utf8','root','', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
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

function modifColonne($nameTab, $nameColumn, $id, $value){
	$bdd = connect();
    $modif = "UPDATE $nameTab SET $nameColumn = $value WHERE IdEleve = $id";
    $bdd->exec($modif);
}

function relation($idProf, $nameClasse, $x, $y, $nbrEleve){
	$bdd = connect();
    $add_relation = $bdd->prepare("INSERT INTO profclasse(idProf, nomClasse, nbrLigne, nbrColonne, nbrEleve) VALUES (?,?,?,?,?)");
    $add_relation-> execute(array($idProf, $nameClasse, $x, $y, $nbrEleve));
}

function allClasse($idProf){
	$bdd = connect();
	$query = "SELECT nomclasse FROM profclasse WHERE idProf = $idProf";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$NbreData = $bdd_select->rowCount();
	$rowAll = $bdd_select->fetchAll();

	return $rowAll;
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
			$ligne = utf8_encode (fgets ($fp,4096));
			$liste = explode(";", $ligne);
			
			$liste [0] = (isset ($liste [0])) ? $liste [0] : NULL;
			$liste [1] = (isset ($liste [1])) ? $liste [1] : NULL;

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

function newClasse($nameTab, $idProf, $x, $y, $nbrEleve){
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
		relation($idProf, $nameTab, $x, $y, $nbrEleve);
		addColumn($nameTab, 'Bavardage');
		addColumn($nameTab, 'Participation');
}

function infoClasse($nameClasse, $idProf)
{
	$bdd = connect();
	$query = "SELECT nbrLigne, nbrColonne, nbrEleve FROM profclasse WHERE IdProf = $idProf AND nomClasse = '".$nameClasse."'";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$rowAll = $bdd_select->fetchAll();

	foreach ($rowAll as $row) {
		return $row;
	}
}

function infoStud ($x, $y, $nomClasse){
	$pdo = connect();
	$prepare = "SELECT * FROM $nomClasse WHERE x = $x AND y = $y";
	$result = $pdo->prepare($prepare);
	$result->execute();
	$NbreData = $result->fetchAll();
	return $NbreData;
}

function deleteClasse($nameClasse, $idProf){
	$bdd = connect();
	$query2 = "DELETE FROM profclasse WHERE idProf = $idProf AND nomClasse = '".$nameClasse."'";
	$bdd->exec($query2);
	$query = "DROP TABLE $nameClasse";
	$bdd->exec($query);
	

	
}

function modifClasse($nameClasse, $idProf, $x, $y){
	$bdd = connect();
    $modif = "UPDATE profclasse SET nbrLigne = $x, nbrColonne = $y  WHERE IdProf = $idProf AND nomClasse = '".$nameClasse."'";
    $bdd->exec($modif);
}

function modifValeur($nameClasse, $nomColonne, $idEleve, $point){
	$bdd = connect();
	if ($point <= 0)) {
	    $modif = "UPDATE $nameClasse SET $nomColonne = $point WHERE idEleve = $idEleve";
		$bdd->exec($modif);
	}
}

function modCommentaire($nameClasse, $idEleve, $message){
	$bdd = connect();

    $prepare = 'UPDATE ' . $nameClasse . ' SET commentaire = :message WHERE idEleve = :idEleve';
    $result = $bdd->prepare($prepare);
    $result->execute(array('idEleve' => $idEleve, 'message' => $message));
}

function nomColonne($nomClasse){
	$bdd = connect();
	
    $prepare = "SHOW COLUMNS FROM $nomClasse";
	$result = $bdd->prepare($prepare);
	$result->execute();
	$rowAll = $result->fetchAll();

	$champs = array();

	foreach ($rowAll as $row) {
		$champs[] = $row["Field"];
	}
	array_splice($champs, 0, 6);
	return $champs;
}

function recupCompt($nomClasse, $nomColonne, $idEleve){
	$bdd = connect();
	$query = "SELECT $nomColonne FROM $nomClasse WHERE IdEleve = $idEleve";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$rowAll = $bdd_select->fetchAll();

	foreach ($rowAll as $row) {
		return $row;
	}
}

?>