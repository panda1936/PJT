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
