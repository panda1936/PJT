<?php
$pdo = new PDO('mysql:host=localhost;dbname=global', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
$NbLig = 7;  
$NbCol = 7;    
$NbPlace = $NbCol * $NbLig;
$nameTab = "classe";
$nameColumn = "bavardage";
if(!empty($_GET['type'])) {
    $nameColumn = $_GET['type'];
}

function createTableau($pdo, $nameTab, $nameColumn, $NbCol, $NbLig) {
    echo '<table id="main">';
    $y = 1;
    while ($y <= $NbCol) {
        $x = 1;
        echo'
            <tr class="col">';
        while ($x <= $NbLig) {
            $prepare = 'SELECT * FROM ' . $nameTab . ' WHERE x = :x AND y = :y';
            $result = $pdo->prepare($prepare);
            $result->execute(array(
                'x' => $x,
                'y' => $y
            ));
            $idEleve = "";
            $commentaire = "";
            $nom = "";
            $prenom = "";
            $nom = "";
            
            while($users = $result->fetch(PDO::FETCH_ASSOC)){
                $idEleve = $users["idEleve"];
                $commentaire = $users["commentaire"];
                $nom = $users["nom"];
                $prenom = $users["prenom"];
            }
            
            echo'
                <td class="box" onclick="box(this);">
                    <div class="box-content">
                        <div class="box-text pt-4">';
            
            createCommentaire($pdo, $nameTab, "commentaire", $x, $y);
            
            echo'
                        </div>
                    </div>
                    <div class="box-icon"></div>
                    <div class="box-title">';
            
            echo '<span>' . $nom . ' ' . $prenom . '</span><br>';
            
            echo'<div style="font-size:10px;">';
            createCompteur($pdo, $nameTab, $nameColumn, $x, $y);
            echo'</div>';
            
            echo '</div>
                </td>';
            
            $x = $x + 1;
        }
        echo'
            </tr>';
        $y = $y + 1;
    }
    echo '</table>';
}

function createCompteur($pdo, $nameTab, $nameColumn, $x, $y) {
    $prepare = 'SELECT * FROM ' . $nameTab . ' WHERE x = :x AND y = :y';
    $result = $pdo->prepare($prepare);
    $result->execute(array(
        'x' => $x,
        'y' => $y
    ));
    $idEleve = "";
    while($users = $result->fetch(PDO::FETCH_ASSOC)){
        echo '<span class="masquer">' . $nameColumn . ' : ' . $users[$nameColumn] . '</span><br>';
        $idEleve = $users["idEleve"];
    }
    
    $nameplus = 'plus' . $x . '_' . $y;
    if (isset($_POST[$nameplus])) {
        compteur($pdo, $nameTab, $nameColumn, $idEleve, 1);
        echo '<script language="Javascript">
            document.location.href="index.php?type=' . $nameColumn . '"
        </script>';
    }

    $namemoins = 'moins' . $x . '_' . $y;
    if (isset($_POST[$namemoins])) {
        compteur($pdo, $nameTab, $nameColumn, $idEleve, -1);
        echo '<script language="Javascript"> 
            document.location.href="index.php?type=' . $nameColumn . '"
        </script>';
    }

    echo '
    <form class ="masquer" method="POST" action="">
        <input type="submit" name="';
    echo 'moins' . $x . '_' . $y;
    echo '" value="-">
        <input type="submit" name="';
    echo 'plus' . $x . '_' . $y;
    echo '" value="+">
    </form><br>';
}

function compteur($pdo, $nameTab, $nameColumn, $idEleve, $point) {
    // $point = 1 ou -1
    
    $prepare = 'SELECT * FROM ' . $nameTab . ' WHERE idEleve = :idEleve';
    $result = $pdo->prepare($prepare);
    $result->execute(array(
        'idEleve' => $idEleve
    ));
    while($users = $result->fetch(PDO::FETCH_ASSOC)){
        $elevePoint = $users[$nameColumn];
    }
    
    $prepare2 = 'UPDATE ' . $nameTab . ' SET ' . $nameColumn . ' = :' . $nameColumn . ' WHERE idEleve = :idEleve';
    $result2 = $pdo->prepare($prepare2);
    $result2->execute(array(
        'idEleve' => $idEleve,
        $nameColumn => $elevePoint + $point
    ));
}

function createCommentaire($pdo, $nameTab, $nameColumn, $x, $y) {
    $prepare = 'SELECT * FROM ' . $nameTab . ' WHERE x = :x AND y = :y';
    $result = $pdo->prepare($prepare);
    $result->execute(array(
        'x' => $x,
        'y' => $y
    ));
    $commentaire = "";
    $idEleve = "";
    
    while($users = $result->fetch(PDO::FETCH_ASSOC)){
        $idEleve = $users["idEleve"];
        $commentaire = $users[$nameColumn];
    }
    
    $nameplus = 'modifier' . $x . '_' . $y;
    if (isset($_POST[$nameplus])) {
        modifierCommentaire($pdo, $nameTab, $nameColumn, $idEleve, $_POST["modifier"]);
        echo '<script language="Javascript">
            document.location.href="index.php"
        </script>';
    }
    echo '<div class="text-center">';
    echo '<form method="POST" action="">';
    echo '<textarea name="modifier" rows="5" cols="33">';
    echo $commentaire;
    echo '</textarea><br>';
    echo '<input class="btn btn-secondary bg-dark" type="submit" name="';
    echo 'modifier' . $x . '_' . $y;
    echo '" value="Modifier">
    </form></div><br>';
}

function modifierCommentaire($pdo, $nameTab, $nameColumn, $idEleve, $commentaire) {
    $prepare = 'SELECT * FROM ' . $nameTab . ' WHERE idEleve = :idEleve';
    $result = $pdo->prepare($prepare);
    $result->execute(array(
        'idEleve' => $idEleve
    ));
    while($users = $result->fetch(PDO::FETCH_ASSOC)){
        $elevePoint = $users[$nameColumn];
    }
    
    $prepare2 = 'UPDATE ' . $nameTab . ' SET ' . $nameColumn . ' = :' . $nameColumn . ' WHERE idEleve = :idEleve';
    $result2 = $pdo->prepare($prepare2);
    $result2->execute(array(
        'idEleve' => $idEleve,
        $nameColumn => $commentaire
    ));
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Hosptimal</title>
        <link rel="icon" href="icone.png" type="image/x-icon">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="css/style.css">
    </head>
        
    <body>
        <h1 class="text-center">HOSPTIMAL</h1>
        
        <header>
            <?php    
            $NbClasse = 3; // créatino de classe (on défini le nombre de classe sur nvabar)
            $i = 0;
            $c = 0;
            echo'<div class="text-center my-5">
            <div class="btn-group">
            <nav class="navbar navbar-expand-sm navbar-dark bg-dark rounded-pill">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">';

            while ( $i < $NbClasse) {
                $i ++;
                $c ++;

                echo'
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Classe ' .$c. '</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#">Gestion</a>
                    </div>
                </li>';
            }
            // le bouton pour ajouter une classe
            echo'   
                    <li class="nav-item">
                        <a class="nav-link" href="">+</a>
                    </li>
            </ul>
            </div>
            </nav>
            </div>
            </div>';
            ?>
        </header>
        
        <?php
        createTableau($pdo, $nameTab, $nameColumn, $NbCol, $NbLig);
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
                <button type="button" class="btn btn-secondary bg-dark" onclick="FonctionBavardage()">Bavardage</button>
                <button type="button" class="btn btn-secondary bg-dark" onclick="FonctionAjouter()">Ajouter</button>
            </div>
        </div>
        <div class="ajouterPopup">
            <form class ="masquer ajouterType text-center" method="POST" action="">
                <label for="ajouterType">Nouveau type</label><br>
                <input class="input" id="ajouterType" type="text" name="ajouterType"><br>
                <input class="input"  type="submit" name="ajouter" value="Ajouter"><br>
            </form>
        </div>
        <!--div class="modifierPopup">
            <form class ="masquer modifierType text-center" method="POST" action="">
                <label for="modifierType">Modifier commentaire</label><br>
                <textarea class="input" id="modifierType" type="text" name="modifierType" rows="5" cols="33"></textarea><br>
                <input class="input"  type="submit" name="modifier" value="Modifier"><br>
            </form>
        </div-->
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
        /*function FonctionModifier() {
            var ajouter = document.getElementsByClassName("modifierPopup");
            if (ajouter[0].style.display === "none") {
                ajouter[0].style.display = "block";
            } else {
                ajouter[0].style.display = "none";
            }
        }*/
        </script>
        
        <script src="js/script.js"></script>
    </body>
</html>