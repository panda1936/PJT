<?php 

$NbCol = 7;  
$NbLig = 6;    
$NbPlace = $NbCol * $NbLig;
$nameTab = "classe";
$nameColumn = "bavardage";
if(!empty($_GET['type'])) {
    $nameColumn = $_GET['type'];
}

function createTableau($pdo, $nameTab, $nameColumn, $NbCol, $NbLig) {
    echo '<table>';
    $y = 1;
    while ($y <= $NbCol) {
        $x = 1;
        echo'<tr>';
        while ($x <= $NbLig) {
            $nom = "";
            echo'
            <td>
                <div id="main">
                    <div class="col">
                        <div class="box" onclick="box(this);">
                            <div class="box-content">
                            </div>
                            <div class="box-icon"></div>
                                <div class="box-title">
                                    ';
            
            $prepare = 'SELECT * FROM ' . $nameTab . ' WHERE x = :x AND y = :y';
            $result = $pdo->prepare($prepare);
            $result->execute(array(
                'x' => $x,
                'y' => $y
            ));
            $idEleve = "";
            while($users = $result->fetch(PDO::FETCH_ASSOC)){
                echo '<span>' . $users["nom"] . ' ' . $users["prenom"] . '</span><br>';
                $idEleve = $users["idEleve"];
            }
            
            createCompteur($pdo, $nameTab, $nameColumn, $x, $y);
            createCompteur($pdo, $nameTab, 'bavardage', $x, $y);
            
            echo '
                            </div>
                        </div>
                    </div>
                </div>
            </td>';
            $x = $x + 1;
        }
        echo'</tr>';
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

?>

<style>
    .ajouterPopup {
        display: none;
        position: fixed;
        top: 0;
        margin: 100px 25%;
        width: 50%;
        height: 50vh;
        background: #3f3f3f;
        border-radius: 10px;
        color: white;
        font-weight: 700;
    }
    .ajouterType {
        margin: 100px 0px;
    }
    .input {
        border: none;
        border-radius: 5px;
        padding: 12px;
        margin: 10px auto;
        color: white;
        font-weight: 700;
        background: #3f3f3f;
        box-shadow:  5px 5px 10px #363636, -5px -5px 10px #484848;
    }
    .input:hover {
        background: #414141;
        box-shadow:  5px 5px 10px #484848, -5px -5px 10px #363636;
    }
</style>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Hosptimal</title>
        <link rel="icon" href="icone.png" type="image/x-icon">
        <link rel="stylesheet" href="css/principale.css">
        
        <h1 class="titre">HOSPTIMAL</h1>
        </head>
        
    
    <body>
        <header>
        
            
        <?php    
        $NbClasse = 1; // créatino de classe (on défini le nombre de classe sur nvabar)
        $i = 0;
        $c = 0;
        echo'<div class="wrap">
        <span class="decor"></span>
        <nav>
        <ul class="primary">';
            
            
        while ( $i < $NbClasse)
        {
        $i ++;
        $c ++;
                             
        echo'
        
            <li>
              <a href="">Classe' .$c. '</a>
              <ul class="sub">
                <li><a href="">Gestion</a></li>
              </ul>
            </li>';
        }
        // le bouton pour ajouter une classe
        echo'   
            <li>
              <a href="">+</a>
              <ul class="sub">
              </ul>  
            </li>
          </ul>
        </nav>
        </div>';  

        ?>
            
        </header>
                  
            
        <!--Tableau-->
        <?php
        
        $pdo = new PDO('mysql:host=localhost;dbname=global', 'root', '', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        
        createTableau($pdo, $nameTab, $nameColumn, $NbCol, $NbLig);
        
        ?>
        
        <table>
            <tr>
                <td>
                    <div id="main">
                      <div class="col">
                        <div class="box" onclick="box(this);">
                          <div class="box-content" style="background: orange">
                            <p class="box-text">
                              text
                            </p>
                          </div>
                          <div class="box-icon"></div>
                          <div class="box-title">Bureau</div>
                        </div>
                    </div>
                    </div>
                          
                </td>
            </tr>   
        </table>

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