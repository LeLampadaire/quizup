<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
    <?php
    require_once('configuration.php'); 
    require_once('baseDeDonnee.php'); 
    
    $question = mysqli_query($bdd, 'SELECT question.idQuestion, question.illustration as illustrat, question.libelleQuestion, question.answer, question.distracteur01, question.distracteur02, question.distracteur03 FROM question INNER JOIN theme ON (question.idTheme = theme.idTheme) WHERE '.$_GET['idtheme'].' = question.idTheme;');

    $theme_class = "active";
    if(isset($_GET['erreur']) && $_GET['erreur'] == 1){
        echo '<div class="alert alert-danger" role="alert">';
        echo 'Cette question ne peut pas être supprimée car elle fait partie déjà partie d\'une partie';
        echo '</div>';
    }
    ?>

<head>
	<title><?php echo $nomSite; ?> - Liste des questions</title>
	<meta charset="utf-8">
  	<?php require_once('styles.php'); ?>
</head>
<body class="bg-secondary">
	<!-- HEADER -->
    <?php require_once('header.php'); ?>

    <!-- Contenu principale -->
    <section class="container text-center mt-5 text-white principale">
      <div class="card text-center bg-dark">
        <div class="card-header">
          <h2>Liste des questions</h2>
        </div>
        <div class="card-body">
          <?php 
            if(empty($_SESSION)){
                echo '<div class="alert alert-danger" role="alert">
                        Vous devez être connecté pour pouvoir voir les thèmes !
                      </div>';
            }else{
                $count = 0;
                while($recup = mysqli_fetch_array($question, MYSQLI_ASSOC)){
                  if($count != 0){
                    echo '<hr>';
                  }
                  $nomQuestion = str_replace(" ","_",$recup['libelleQuestion']);
                  $nomQuestion = str_replace(".","_",$nomQuestion);
                  $nomQuestion = str_replace("?","_",$nomQuestion);
                  $nomQuestion = str_replace("'","_",$nomQuestion);
                  $nomQuestion = str_replace("!","_",$nomQuestion);
                  $nomQuestion = str_replace("&","_",$nomQuestion);
                  $nomQuestion = str_replace("@","_",$nomQuestion);
                  $nomQuestion = str_replace("#","_",$nomQuestion);
                  $nomQuestion = str_replace("$","_",$nomQuestion);
                  $nomQuestion = str_replace("%","_",$nomQuestion);
                  $nomQuestion = str_replace("+","_",$nomQuestion);
                  ?>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-light" data-toggle="modal" data-target="#-<?php echo $nomQuestion; ?>-">
                        <?php echo $recup['libelleQuestion']; ?>
                    </button>

                    <!-- Modal -->
                    <div style="color: black;" class="modal fade" id="-<?php echo $nomQuestion; ?>-" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><?php echo $recup['libelleQuestion']; ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php
                                if(!empty($question['illustrat'])){
                                  echo '<img class"rounded border border-warning" width="200px" height="200px" alt="Image de profil" src="'.$question['illustrat'].'">';
                                  echo '<hr>';
                                }
                                echo 'Réponse : ' . $recup['answer'] . '<br>';
                                echo 'Distracteur 1 : ' . $recup['distracteur01'] . '<br>';
                                echo 'Distracteur 2 : ' . $recup['distracteur02'] . '<br>';
                                echo 'Distracteur 3 : ' . $recup['distracteur03'];
                                $idquestiontemp = $recup['idQuestion'];
                            ?>
                        </div>
                        <div class="modal-footer">
                            <?php
                                echo '<a href="mise_a_jour_Question.php?idtheme='.$_GET['idtheme'].'&idquestion='.$idquestiontemp.'"><button type="button" class="btn btn-secondary">Modifier</button></a>';
                                echo '<a href="supprimer_question.php?idtheme='.$_GET['idtheme'].'&idquestion='.$idquestiontemp.'"><button type="button" class="btn btn-secondary">Supprimer</button></a>';
                            ?>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        </div>
                        </div>
                    </div>
                    </div>

                  <?php
                  $count++;
                }
            }
           ?>
        </div>

        <div class="card-footer">
          <?php
            echo '<a href="ajouter_question.php?idtheme='.$_GET['idtheme'].'"><button  class="btn btn-dark float-right">'."Ajouter une question".'</button></a>';
            echo '<a href="menu_themes.php?idtheme='.$_GET['idtheme'].'"><button  class="btn btn-dark float-left">'."Retour".'</button></a>';
          ?>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once('footer.php'); 
          mysqli_close($bdd);
    ?>
</body>
</html>