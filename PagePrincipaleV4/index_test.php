<?php
require ('./global.php'); 

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


$NbPlace = 0;
$nameTab = "classe";
$nameColumn = "bavardage";
if(!empty($_GET['type'])) {
    $nameColumn = $_GET['type'];
}
initialisation(3, $nameTab);

function initialisation($idProf, $Classe){
	global $NbCol;
	global $NbLig;
	global $NbPlace;
	
	$bdd = connect();
	
	$tab = infoClasse($Classe, $idProf);
	$NbLig = $tab[0];	
	$NbCol = $tab[1];
	$NbPlace = $NbLig * $NbCol;
}


function createTableau($nameTab, $nameColumn) {
    $pdo = connect(); 
	global $NbCol;
	global $NbLig;
	
	echo '<table id="main">';
    $y = $NbLig - 1;
    while ($y >= 0) {
        $x = 0;
        echo'<tr class="col">';
        while ($x < $NbCol) {
			$idEleve = null;
			$tab = infoStud($x, $y, $nameTab);
			
			foreach ($tab as $row) {
				$idEleve = $row[2];
				$commentaire = $row[5];
				$nom = $row[0];
				$prenom = $row[1];
			}
			?>
			
			<?php if ($idEleve != null){
				?> <td class="box" onclick="box(this);">
			<?php }
			else{ ?>
				<td class="box">
			<?php } ?>
				<div class="box-content">
					<div class="box-text pt-4">
						<?php if ($idEleve != null){
							createCommentaire($nameTab, $x, $y, $tab);
						}
						?>
					</div>
				</div>
				<?php if ($idEleve != null){
					?><div class="box-icon"></div><?php
				}
				?><div class="box-title">
			
			<?php
			if ($idEleve != null){
				echo '<span>' . $nom . ' ' . $prenom . '</span><br>';
				echo'<div style="font-size:10px;">';
				createCompteur($nameTab, $nameColumn, $x, $y, $tab);
				echo'</div>';
				echo '</div>';
			}
            echo '</td>';
            $x += 1;
        }
        echo'
            </tr>';
        $y -= 1;
    }
    echo '</table>';
}

function createCompteur($nameTab, $nameColumn, $x, $y, $tab) {
	foreach ($tab as $row) {
		echo '<span class="masquer">'.$nameColumn.': ' . $row[$nameColumn] . '</span><br>';
		$idEleve = $row["idEleve"];
		$point = $row[$nameColumn];
	}
    
    $nameplus = 'plus' . $x . '_' . $y;
    if (isset($_POST[$nameplus])) {
        modifValeur($nameTab, $nameColumn, $idEleve, $point+1);
        echo '<script language="Javascript">
            document.location.href="index.php?type=' . $nameColumn . '"
        </script>';
    }

    $namemoins = 'moins' . $x . '_' . $y;
    if (isset($_POST[$namemoins])) {
        modifValeur($nameTab, $nameColumn, $idEleve, $point-1);
        echo '<script language="Javascript"> 
            document.location.href="index.php?type=' . $nameColumn . '"
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

function createCommentaire($nameTab, $x, $y, $tab) {
	foreach ($tab as $row) {
		$idEleve = $row["idEleve"];
        $commentaire = $row['commentaire'];
	}

    $nameplus = 'modifier'.$x.'_'.$y;
    if (isset($_POST[$nameplus])) {
		$message = $_POST["modifier"];
		modCommentaire($nameTab, $idEleve, $message);
		echo '<script language="Javascript">
            document.location.href="index.php"
        </script>';
    }
	
    echo '<div class="text-center, commentaire">';
    echo '<form method="POST" action="">';
    echo '<textarea name="modifier" rows="5" cols="33">';
    echo $commentaire;
    echo '</textarea><br>';
    echo '<input class="btn btn-secondary bg-dark" type="submit" name="';
    echo 'modifier'.$x.'_'.$y;
    echo '" value="Modifier">
    </form></div><br>';
}

if(!empty($_POST['addColumn'])){
	$nameTab = 'classe';
    $func = 'addColumn';
    $func($nameTab, $_POST['nameColumn']);
}

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Study School</title>
        <link rel="icon" href="icone.png" type="image/x-icon">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/style.css">
    </head>
        
    <body>
        <h1 class="text-center">STUDY SCHOOL</h1>
        
        <header>
			<div class="text-center my-5">
            <div class="btn-group">
            <nav class="navbar navbar-expand-sm navbar-dark bg-dark rounded-pill">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
			
			<?php
			
			/*$tab = allClasse($idProf);*/
			
			$tab = allClasse(3);
			foreach($tab as $row){
			?>
				<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $row[0] ?> </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Gestion</a>
                    </div>
                </li>
			<?php } ?>
				<li class="nav-item">
					<a class="nav-link" href="">+</a>
                </li>
            </ul>
            </div>
            </nav>
            </div>
            </div>
        </header>
        
        <?php
        createTableau($nameTab, $nameColumn);
        ?>
        
        <br><br><br>
        <table id="main">
            <tr class="col text_center">
                <td class="box" style="background: inherit"></td>
                <td class="box" style="background: inherit" onclick="box(this);">
                    <div class="box-content" style="background: orange">
                    </div>
                    <div class="box-icon"></div>
                    <div class="box-title">Bureau</div>
                </td>
                <td class="box" style="background: inherit"></td>
            </tr>   
        </table>
        
        <div class="text-center my-5">
            <div class="btn-group" role="group" aria-label="Basic example">
				<?php
				//$tab = nomColonne('classe');
				$tab = nomColonne($nameClasse);
				for ($i = 0; $i < sizeof($tab); $i++){
				?>
					<button type="button" class="btn btn-secondary bg-dark" onclick="FonctionCompteur()"><?php echo $tab[$i]; ?></button>
				<?php
				}
				?>
                <button type="button" class="btn btn-secondary bg-dark" onclick="FonctionAjouter()">Ajouter</button>
            </div>
        </div>
		
        <div class="ajouterPopup">
            <form class ="masquer ajouterType text-center" method="POST" action="">
                <label for="ajouterType">Nouveau type</label><br>
                <input class="input" id="ajouterType" type="text" name="nameColumn"><br>
                <input class="input"  type="submit" name="addColumn" value="Ajouter"><br>
            </form>
        </div>

		
        <script>
        function FonctionCompteur() {
            var NbElement = <?php echo $NbPlace; ?> * 2;
            var compteur = document.getElementsByClassName("masquer");
            for (i=0; i<NbElement ; i++) {  
				if (compteur[i].style.display === "none") {
					compteur[i].style.display = "inline-block";
				} 
				else {
					compteur[i].style.display = "none";
				}
            }
        }
		
        function FonctionAjouter() {
            var ajouter = document.getElementsByClassName("ajouterPopup");
            if (ajouter[0].style.display === "none") {
				ajouter[0].style.display = "block";
            } 
			else {
                ajouter[0].style.display = "none";
            }
        }
        </script>
        
        <script src="js/script.js"></script>
    </body>
</html>