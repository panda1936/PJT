<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Hosptimal</title>
        <link rel="icon" href="images/icone.png" type="image/x-icon">
        <link rel="stylesheet" href="css/principale.css">
        
        <h1 class="titre">HOSPTIMAL</h1>
        </head>
        
    
    <body>
        <header>
        
        <?php    
        $NbClasse = 2; // créatino de classe (on féfini le nombre de classe sur nvabar)
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
        
        <table>
        <?php
            $pdo = new PDO('mysql:host=localhost;dbname=global', 'root', 'root', array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
            
            $idEleve = 0;
            $bavardage = 0;
            $nb = 0;
            $y = 0;
            $NbCol = 3;
            $NbLig =3;
            $NbPlace = $NbCol * $NbLig;
            while ($y < $NbCol) {
                $x = 0;
                echo'<tr>';
                while ($x < $NbLig) {
                    $nom = "";
                    echo' 
                    <td> 
                        <div id="main">
                            <div class="col">
                                <div class="box" onclick="box(this);">
                                    <div class="box-content">
                                        <p class="box-text">text</p>
                                    </div>
                                    <div class="box-icon"></div>
                                    <div class="box-title">';
                    
                    $nb = $nb + 1;
                    
                    $result = $pdo->prepare('SELECT * FROM classe WHERE (idEleve) = (:idEleve)');
                    $result->execute(array(
                        'idEleve' => $nb // ICI NUMERO ID ELEVE
                    ));
                    while($users = $result->fetch(PDO::FETCH_ASSOC)){
                        echo $users["nom"] . '<br><span class="masquer">Bavardage : ' . $users["bavardage"] . '</span>';
                        $bavardage = $users["bavardage"];
                        $idEleve = $users["idEleve"];
                    }
                    
                    $nameplus = 'plus' . $nb;
                    if (isset($_POST[$nameplus])) {
                        $result2 = $pdo->prepare('UPDATE classe SET bavardage = :bavardage WHERE idEleve = :idEleve');
                        $result2->execute(array(
                            'idEleve' => $idEleve,
                            'bavardage' => $bavardage + 1
                        ));
                        echo '<script language="Javascript"> 
                        document.location.href="Principale1.php"
                        </script>';
                    }
                    
                    $namemoins = 'moins' . $nb;
                    if (isset($_POST[$namemoins])) {
                        $result2 = $pdo->prepare('UPDATE classe SET bavardage = :bavardage WHERE idEleve = :idEleve');
                        $result2->execute(array(
                            'idEleve' => $idEleve,
                            'bavardage' => $bavardage - 1
                        ));
                        echo '<script language="Javascript"> 
                        document.location.href="Principale1.php"
                        </script>';
                        //header('Location: Principale1.php');
                    }
                    
                    echo '
                    <form class ="masquer" method="POST" action="">
                        <input type="submit" name="';
                    echo 'moins' . $nb;
                    echo '" value="-">
                        <input type="submit" name="';
                    echo 'plus' . $nb;
                    echo '" value="+">
                    </form>';
                    
                    echo '</div>
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
        ?>
        </table>
        
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
    <button onclick="maFonction()">Bavardage</button>    
        
    <script>
    function maFonction() {
        var NbElement = <?php echo $NbPlace ?> * 2;
        var compteur = document.getElementsByClassName("masquer");
        for (i=0; i<NbElement ; i++) {  
        if (compteur[i].style.display === "none") {
            compteur[i].style.display = "block";
          } else {
            compteur[i].style.display = "none";
          }
        }
    }
    </script>

                

        
        <script src="js/principale.js"></script>
    </body>
    <footer>
    </footer>
</html>