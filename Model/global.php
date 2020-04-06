<?php

function addColumn($bdd, $nameColumn){
    $sql = "ALTER TABLE student ADD $nameColumn INT DEFAULT 0";
    $bdd->exec($sql);
    echo 'Colonne ajoutee';
}

function remColumn($bdd, $nameColumn){
    $rem_col = "ALTER TABLE student DROP COLUMN $nameColumn";
    $bdd->exec($rem_col);
    echo 'Colonne supprimee';  
}

function addStudent($bdd, $nom, $prenom){
    $requete = $bdd->prepare("INSERT INTO student(id, nom, prenom) VALUES (?,?,?)");
    $requete-> execute(array(0, $nom, $prenom));
    echo "Inserer eleve";
}

function remStudent($bdd, $idStudent){
    $rem_stud = "DELETE FROM student WHERE id = $idStudent";
    $bdd->exec($rem_stud);
    echo 'Colonne ajoutee';
}

function modif($bdd, $nameTable, $nameColumn, $idStud, $value){
    $modif = "UPDATE $nameTable SET $nameColumn = $value WHERE id = $idStud";
    $bdd->exec($modif);
    echo 'Valeur ajoutee';
}

try{
    $bdd = new PDO('mysql:host=localhost; dbname=eleve; charset=utf8','root', '');
}catch(PDOException $e){
    die('Erreur : '.$e->getMessage());
}

//$func = 'remColumn';
//$func($bdd, 'Berche');

if(!empty($_POST['envoi'])){ // si formulaire soumis
    $func = 'addColumn';
    $func($bdd, $_POST['pseudo']);
}

?>


<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
	Pseudo : <input type="text" name="pseudo" placeholder="saisir...">
 	<input type="submit" name="envoi">
</form>
