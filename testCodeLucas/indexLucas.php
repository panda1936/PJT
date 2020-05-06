<?php

if(isset($_GET["classe"]))
{
	$nameClasse = $_GET["classe"];
}
else
{
	$nameClasse = 'classe';
}
/*initialisation nbr colonne et ligne */
$NbCol = 0;
$NbLig = 0;


function connect(){
	try{
		$pdo = new PDO('mysql:host=localhost;dbname=global;charset=utf8','root','', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
	}
	catch(PDOException $e){
		die('Erreur : '.$e->getMessage());
	}
	return $pdo;
}


function initialisation($idProf, $Classe){
	global $NbCol;
	global $NbLig;
	$bdd = connect();
	$query = "SELECT * FROM profclasse WHERE IdProf = '".$idProf."' AND nomClasse = '".$Classe."' ";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$NbreData = $bdd_select->fetchAll();
	
	foreach ($NbreData as $row) {
		$NbLig = $row['nbrLigne'];
		$NbCol = $row['nbrColonne'];
	}
}
	
	
	
function navClasse($idProf){
	global $nameClasse;
	$bdd = connect();
	
	$query = "SELECT * FROM profclasse WHERE IdProf = '".$idProf."'";
	$bdd_select = $bdd->prepare($query);
	$bdd_select->execute();
	$NbreData = $bdd_select->fetchAll();
	
	?>
	<div class="wrap">
        <span class="decor"></span>
        <nav>
			<ul class="primary">
	<?php
	
	foreach ($NbreData as $row) {
	?>
				<li>
					<a href='./index.php?classe=<?php echo $row['nomClasse'];?>'> <?php echo $row['nomClasse'] ?> </a>
						<ul class="sub">
							<li><a href="">Gestion</a></li>
						</ul>
				</li>
	<?php
	}
	
	// le bouton pour ajouter une classe
		?>
				<li>
					<a href=""> + </a>
				</li>
			</ul>
        </nav>
    </div>
	<?php
}

function affichage($idProf, $nameTab) {
	initialisation($idProf, $nameTab);
	createTableau($nameTab);
	afficheBureau();
}


function createTableau($nameTab) {
	global $NbCol;
	global $NbLig;
	
	$pdo = connect();
    echo '<table>';
    $y = 0;
    while ($y < $NbCol) {
        $x = 0;
        echo'<tr>';
        while ($x < $NbLig) 
		{
            $nom = "";
            ?>
            <td>
                <div id="main">
                    <div class="col">
                        <div class="box" onclick="box(this);">
                            <div class="box-content"> </div>
                            <div class="box-icon"></div>
                                <div class="box-title">
			
			<?php
            $prepare = "SELECT * FROM $nameTab WHERE x = $x AND y = $y";
            $result = $pdo->prepare($prepare);
            $result->execute();
            $idEleve = "";
            $rowAll = $result->fetchAll();
			
			foreach($rowAll as $row){
                echo "<span>" . $row['nom'] ." ". $row['prenom'] . "</span><br>";
                $idEleve = $row['idEleve'];
            }
			
			
			if(!empty($_GET['type'])) {
				$nameColumn = $_GET['type'];
				createCompteur($nameTab, $nameColumn, $x, $y);
			}
            ?>
								</div>
                        </div>
                    </div>
                </div>
            </td>
			<?php
            $x += 1;
        }?>
        </tr>
		<?php $y += 1;
    }
    ?> </table> <?php
}

function compteur($nameTab, $nameColumn, $idEleve, $point) {
	//$point = 1 ou -1
	$pdo = connect();
	$prepare2 = "UPDATE $nameTab SET $nameColumn = $point WHERE idEleve = $idEleve";
	$pdo->exec($prepare2);
}

function createCompteur($nameTab, $nameColumn, $x, $y) {
	$pdo = connect();
	
    $prepare = "SELECT * FROM $nameTab WHERE x = $x AND y = $y";
    $result = $pdo->prepare($prepare);
    $result->execute();
	$rowAll = $result->fetchAll();
	$idEleve = "";
	
	foreach ($rowAll as $row) {
        echo '<span class="masquer">'.$nameColumn.' : '.$row[$nameColumn].'</span><br>';
        $idEleve = $row['idEleve'];
		$poid = $row[$nameColumn];
    }
    
    $nameplus = 'plus'.$x.'_'.$y;
    if (isset($_POST[$nameplus])) {
        compteur($nameTab, $nameColumn, $idEleve, $poid+1);
        echo '<script language="Javascript">
            document.location.href="index.php?type='.$nameColumn.' $"
        </script>';
    }

    $namemoins = 'moins'.$x.'_'.$y;
    if (isset($_POST[$namemoins])) {
        compteur($nameTab, $nameColumn, $idEleve, $poid-1);
        echo '<script language="Javascript"> 
            document.location.href="index.php?type='.$nameColumn.'"
        </script>';
    }

    ?>
    <form class ="masquer" method="POST" action="">
        <input type="submit" name= <?php echo 'moins'.$x.'_'.$y; ?> value = "-">
        <input type="submit" name= <?php echo 'plus'.$x.'_'.$y;?> value = "+">
    </form><br>
	</br>
<?php 
}

function afficheBureau(){
	?>
	<table>
		<tr>
			<td>
				<div id="main">
					<div class="col">
						<div class="box" onclick="box(this);">
							<div class="box-content" style="background: orange">
								<p class="box-text"> text </p>
							</div>
							<div class="box-icon"></div>
							<div class="box-title">Bureau</div>
						</div>
					</div>
				</div>			  
			</td>
		</tr>   
	</table>
	<?php
}

function boutonCompteur(){
	?>
    <!--Bouton-->
    <button onclick="FonctionBavardage()">Bavardage</button>
    <button onclick="FonctionAjouter()">Ajouter</button> 

	<script>
	function FonctionBavardage() {
		var NbElement = <?php echo $NbPlace ?> * 2;
		var compteur = document.getElementsByClassName("masquer");
		for (i=0; i<NbElement ; i++) {  
		if (compteur[i].style.display === "none") {
			compteur[i].style.display = "inline-block";
		  } else {
			compteur[i].style.display = "none";
		  }
		}
	}
	
	function FonctionAjouter() {
		var ajouter = document.getElementsByClassName("ajouterPopup");
		if (ajouter[0].style.display === "none") {
			ajouter[0].style.display = "block";
		} else {
			ajouter[0].style.display = "none";
		}
	}
</script>

<script src="js/principale.js"></script>

<?php
}

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Hosptimal</title>
        <link rel="icon" href="icone.png" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="css/principale.css">
        
        <h1 class="titre">HOSPTIMAL</h1>
    </head>
        
    
    <body>
        <header>
			<?php    
				navClasse(3);; // création de la barre de navigation entre les classe d'un professeur
			?>
        </header>
                  
            
        <!--Tableau-->

        <?php
		$idProf = 3;
		affichage($idProf, $nameClasse); // Fonction qui affiche les élèves le professeur
		boutonCompteur(); // Affiche les boutons pour ajouter les catégories et pour choisir le compteur visible
        ?>
		
	
    </body>
    <footer>
	</footer>
    <div class="ajouterPopup">
        <form class ="masquer ajouterType" method="POST" action="">
            <label for="ajouter">Nouveau type</label><br>
            <input class="input" id="ajouterType" type="text" name="ajouterType"><br>
            <input class="input"  type="submit" name="ajouter" value="Ajouter"><br>
        </form>
    </div>
</html>