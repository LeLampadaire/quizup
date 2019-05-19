<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php 
    require_once('configuration.php'); 
    require_once('baseDeDonnee.php'); 
    $theme = mysqli_query($bdd, 'SELECT theme.idTheme, theme.libelleTheme FROM question, theme WHERE question.idTheme = theme.idTheme GROUP BY theme.idTheme, theme.libelleTheme HAVING COUNT(question.idQuestion) >= 7 ORDER BY libelleTheme ASC;');
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
            if(!empty($_GET) && $_GET['erreur'] == 1)
            {
              echo '<div class="alert alert-danger" role="alert">
                      Vous devez être connecté pour pouvoir lancer une partie !
                    </div>';
            }
           ?>
          <?php
              while($recup = mysqli_fetch_array($theme, MYSQLI_ASSOC)) 
              {
                 echo '<a href="creation_partie.php?idtheme='.$recup['idTheme'].'"><button class="btn btn-dark">'.$recup['libelleTheme'].'</button></a><br>';
              } 
            ?>
        </div>
        <div class="card-footer">
          <a href="#"><button class="btn btn-dark">ajouter un theme</button></a>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once('footer.php'); ?>
</body>
</html>