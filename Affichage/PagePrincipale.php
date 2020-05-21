<?php

/*initialisation nbr colonne et ligne */
global $NbCol;
global $NbLig;
$NbLig = $tab[0];	
$NbCol = $tab[1];

function createTableau($nameClasse) 
{
	global $NbCol;
	global $NbLig;
	
	$tab = nomColonne($nameClasse);
	
	echo '<table id="main">';
    $y = $NbLig - 1;
    while ($y >= 0) {
        $x = 0;
        echo'<tr class="col">';
        while ($x < $NbCol) {
			$idEleve = null;
			$info = infoStud($x, $y, $nameClasse);
			
			foreach ($info as $row) {
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
						<?php 
						if ($idEleve != null){
							for($i = 0; $i < sizeof($tab); $i++){
								foreach ($info as $row2) {
									 /*nom des catégories dans le box*/
                                    echo'<div class="affiche_categorie ">';
									echo $tab[$i].': '.$row2[$tab[$i]].'</br>';
								    echo'</div>';
								}
							}
							echo '</br>';
							createCommentaire($nameClasse, $x, $y, $info);
						}
						?>
					</div>
				</div>
				<?php if ($idEleve != null){
					?><div class="box-icon"></div><?php
				}
				?>
				
			<div class="box-title">
			<?php
			if ($idEleve != null){
				echo '<span>'.$nom.'</span></br>';
				echo '<span>'.$prenom.'</span></br>';
				echo'<div style="font-size:10px;">';
				
				$tab = nomColonne($nameClasse);
				for ($i = 0; $i < sizeof($tab); $i++){
					createCompteur($nameClasse, $tab[$i], $x, $y, $info);
				}
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


function createCompteur($nameClasse, $nameColumn, $x, $y, $info) {
	echo '<div class="box-compt";">';
	foreach ($info as $row) {
		$nbrInColonne = $row[$nameColumn];
		$idEleve = $row["idEleve"];
		$point = $row[$nameColumn];
	}
    
    $nameplus = 'plus'.$x.'_'.$y.'_'.$nameColumn;
    if (isset($_POST[$nameplus])) {
        modifValeur($nameClasse, $nameColumn, $idEleve, $point+1);
        echo '<script language="Javascript"">
            document.location.href="index2.php?action=classe&classe='.$nameClasse.'"
        </script>';
    }

    $namemoins = 'moins'.$x.'_'.$y.'_'.$nameColumn;
    if (isset($_POST[$namemoins])) {
        modifValeur($nameClasse, $nameColumn, $idEleve, $point-1);
        echo '<script language="Javascript"> 
            document.location.href="index2.php?action=classe&classe='.$nameClasse.'"
        </script>';
    }

	?>
	
	
    <form class="nom_compteur masquer masquer_<?php echo ($nameColumn); ?>" method="POST" action="" style="display:none;">
		<div> <?php echo $nameColumn . ' : ' . $nbrInColonne ?> </div>
        <input class="bouton_compteur" type="submit" name= <?php echo $namemoins; ?> value = "-">
        <input class="bouton_compteur" type="submit" name= <?php echo $nameplus; ?> value = "+">
    </form><br>
	<?php
	echo '</div>';
}

function createCommentaire($nameClasse, $x, $y, $tab){
	foreach ($tab as $row){
		$idEleve = $row["idEleve"];
        $commentaire = $row['commentaire'];
	}

    $nameplus = 'modifier'.$x.'_'.$y;
    if (isset($_POST[$nameplus])){
		$message = $_POST["modifier"];
		modCommentaire($nameClasse, $idEleve, $message);
		echo '<script language="Javascript">
            document.location.href="index2.php?action=classe&classe='.$nameClasse.'"
        </script>';
    }
	
    echo '<div class="text-center, commentaire">';
    echo '<form method="POST" action="">';
    echo '<textarea class="textarea" name="modifier">';
    echo $commentaire;
    echo '</textarea><br>';
    echo '<input class=" bouton_commentaire btn btn-secondary bg-dark" type="submit" name="';
    echo 'modifier'.$x.'_'.$y;
    echo '" value="Modifier"><a class=" bouton_commentaire btn btn-secondary bg-dark" href="JavaScript: location.reload(true);">Fermer</a>
    </form></div><br>';
    
    
}

if(!empty($_POST['addColumn'])){
    $func = 'addColumn';
    $func($nameClasse, $_POST['nameColumn']);
}

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Studyboards</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="Affichage/style/style2.css">
        
        <link rel="apple-touch-icon" sizes="180x180" href="Affichage/style/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="Affichage/style/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="Affichage/style/favicon-16x16.png">
        <link rel="manifest" href="Affichage/style/site.webmanifest">
        <link rel="mask-icon" href="Affichage/style/safari-pinned-tab.svg" color="#5bbad5">
        <link rel="shortcut icon" href="Affichage/style/favicon.ico">
        <meta name="apple-mobile-web-app-title" content="Studyboards">
        <meta name="application-name" content="Studyboards">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-config" content="Affichage/style/browserconfig.xml">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body>
        
        

        <header onclick="box1()">
            <img class="Logo" src="Affichage/style/TitleLogo_WhiteBG.svg"/>
			<button class="Deconnexion" onclick="location.href='index2.php?action=deconnexion'">Déconnexion</button>
			<div class="text-center my-5">
            <div class="btn-group">
            <nav class="navbar navbar-expand-sm navbar-dark bg-dark rounded-pill">
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
			               
			<?php
			
			
			$tab = allClasse($idProf);

			foreach($tab as $row){
			?>         

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="index2.php?action=classe&classe=<?php echo $row[0]?>"> <?php echo $row[0] ?> </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="index2.php?action=modification&classe=<?php echo $row[0]?>">Gestion</a></li>
                        </ul>
                </li>

			<?php } ?>
				<li class="nav-item">
					<?php if(sizeof($tab) <= 5){ ?> 
						<a class="nav-link" href="index2.php?action=creation">+</a>
					<?php } ?>
                </li>
            </ul>
            </div>
            </nav>
            </div>
            </div>
            
            <h1 class="Classe_actuelle">Classe : <?php echo $nameClasse ?> </h1>
            
        </header>
        
        <?php
        createTableau($nameClasse);
        ?>
		
        <div onclick="box1()">
            <img class="Bureau" src="Affichage/style/Bureau.png"/>
		</div>
        
        <div class="text-center bouton_bas py-5">
            <div class="btn-group" role="group" aria-label="Basic example">
				<?php
				$tab = nomColonne($nameClasse);
				$taille = sizeof($tab);
				for ($i = 0; $i < $taille; $i++){
					$nom = $tab[$i];
				?>
					<button type="button" class="btn btn-secondary bg-dark" onclick="FonctionCompteur('<?php echo $nom ?>')"> <?php echo $nom; ?> </button>
				<?php
				}
				
				if ($taille <= 5) { ?>
					<button type="button" class="btn btn-secondary bg-dark" onclick="FonctionAjouter()">Ajouter</button>
				<?php } ?>
            </div>
        </div>
		
        <div class="ajouterPopup">
            <form class ="ajouterType text-center" method="POST" action="">
                <label for="ajouterType">Nouveau type</label><br>
                <input class="input" id="ajouterType" type="text" name="nameColumn"><br>
                <input class="input"  type="submit" name="addColumn" value="Ajouter">
                <button type="button" class="input" onclick="FonctionAjouter()">Fermer</button>
                <br>
            </form>
        </div>

		
        <script>
        function FonctionCompteur(nom) {
			
            var compteur = document.getElementsByClassName("masquer");
			
			var taillemax = compteur.length;
            for (i=0; i< taillemax ; i++) {
				compteur[i].style.display = "none";
            }
			
			var nomClasse = "masquer_"+ nom;
			var compteur1 = document.getElementsByClassName(nomClasse);
			var taillemin = compteur1.length;
			for (i=0; i< taillemin ; i++) {
				compteur1[i].style.display = "inline-block";
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
        
        <script src="Affichage/script.js"></script>
    </body>

</html>