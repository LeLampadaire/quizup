<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php require_once('configuration.php'); 
    require_once('baseDeDonnee.php'); 
    $theme = mysqli_query($bdd, 'SELECT theme.idTheme, theme.libelleTheme FROM theme ORDER BY libelleTheme ASC;');

    $mes_themes = mysqli_query($bdd, 'SELECT theme.idTheme, theme.libelleTheme FROM theme WHERE idProfil = '.$_SESSION['idprofil'].' ORDER BY libelleTheme ASC;');

    $theme_class = "active";
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
            if(isset($_GET['erreur']) && $_GET['erreur'] == 1){
              echo '<div class="alert alert-danger" role="alert">';
              echo 'Ce thème ne peut pas être supprimé car il possède au-moins une question !';
              echo '</div>';
            }
           ?>
          <?php
              $count = 0;
              while($recup = mysqli_fetch_array($theme, MYSQLI_ASSOC)){
                if($count != 0){
                  echo '<hr>';
                }
                echo '<a href="menu_themes.php?idtheme='.$recup['idTheme'].'"><button class="btn btn-dark">'.$recup['libelleTheme'].'</button></a>';
                $count++;
              }
            ?>

        </div>

        <div class="card-footer">
          <a href="index.php"><button class="btn btn-dark float-left">Accueil</button></a>
          <a href="ajout_themes.php"><button class="btn btn-dark float-right">Ajouter un thème</button></a>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once('footer.php'); 
          mysqli_close($bdd);
    ?>
</body>
</html>