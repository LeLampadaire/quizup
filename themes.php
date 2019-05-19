<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php require_once('configuration.php'); 
    require_once('baseDeDonnee.php'); 
    $theme = mysqli_query($bdd, 'SELECT theme.idTheme, theme.libelleTheme FROM question, theme WHERE question.idTheme = theme.idTheme AND idProfil <> '.$_SESSION['idprofil'].' GROUP BY theme.idTheme, theme.libelleTheme HAVING COUNT(question.idQuestion) >= 7 ORDER BY libelleTheme ASC;');

    $mes_themes = mysqli_query($bdd, 'SELECT theme.idTheme, theme.libelleTheme FROM theme WHERE idProfil = '.$_SESSION['idprofil'].' ORDER BY libelleTheme ASC;');

    ?>

<head>
	<title><?php echo $nomSite; ?> - Thèmes</title>
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
          <h2>Thèmes</h2>
        </div>
        <div class="card-body">
          <?php 
            if(!empty($_GET) && $_GET['erreur'] == 1){
              echo '<div class="alert alert-danger" role="alert">
                      Vous devez être connecté pour pouvoir lancer une partie !
                    </div>';
            }
           ?>
          <?php
              $count = 0;
              while($recup = mysqli_fetch_array($theme, MYSQLI_ASSOC)){
                if($count != 0){
                  echo '<hr>';
                }

                echo '<button type="button" class="btn btn-dark" disabled>'.$recup['libelleTheme'].'</button>';
                echo '<a href="suivre.php?idtheme='.$recup['idTheme'].'"><button class="btn btn-dark float-right">'."Suivre".'</button></a>';
                echo '<a href="creation_partie.php?idtheme='.$recup['idTheme'].'"><button class="btn btn-dark float-right">'."Play".'</button></a>';
                $count++;
              }
            ?>

        </div>

        <div class="card-header">
          <h2>Mes thèmes</h2>
        </div>
        <div class="card-body">

          <?php 
            if(!empty($_GET) && $_GET['erreur'] == 1){
              echo '<div class="alert alert-danger" role="alert">
                      Vous devez être connecté pour pouvoir lancer une partie !
                    </div>';
            }
           ?>
          <?php
          $count = 0;
              while($recup_mesThemes = mysqli_fetch_array($mes_themes, MYSQLI_ASSOC)){
                if($count != 0){
                  echo '<hr>';
                }

                echo '<button type="button" class="btn btn-dark" disabled>'.$recup_mesThemes['libelleTheme'].'</button>';
                echo '<a href="suivre.php?idtheme='.$recup_mesThemes['idTheme'].'"><button class="btn btn-dark float-right">'."Suivre".'</button></a>';
                echo '<a href="creation_partie.php?idtheme='.$recup_mesThemes['idTheme'].'"><button class="btn btn-dark float-right">'."Play".'</button></a>';
                echo '<a href="mise_a_jour_Themes.php?idtheme='.$recup_mesThemes['idTheme'].'"><button  class="btn btn-dark float-right">'."Modifier".'</button></a>';
                echo '<a href="ajouter_question.php?idtheme='.$recup_mesThemes['idTheme'].'"><button  class="btn btn-dark float-right">'."Ajouter une question".'</button></a>';

                $count++;
              }
          ?>


        </div>

        <div class="card-footer">
          <a href="ajout_themes.php"><button class="btn btn-dark">Ajouter un theme</button></a>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once('footer.php'); 
          mysqli_close($bdd);
    ?>
</body>
</html>