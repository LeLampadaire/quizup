<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
    <?php
      require_once('configuration.php');
      require_once('baseDeDonnee.php');

    $titreTheme = mysqli_query($bdd, 'SELECT titre.idTitre, titre.libelleTitre, titre.niveauRequis FROM titre WHERE '.$_GET['idtheme'].' = titre.idTheme ORDER BY libelleTitre ASC;');

    $theme_class = "active";

    if($_SESSION['idprofil'] == NULL){
      header('Location: 404.php');
    }
    ?>

<head>
	<title><?php echo $nomSite; ?> - Titres</title>
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
          <h2>Titres</h2>
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
              while($recup = mysqli_fetch_array($titreTheme, MYSQLI_ASSOC)){
                if($count != 0){
                  echo '<hr>';
                }
                echo '<a href="menu_titre.php?idtheme='.$recup['idTitre'].'"><button class="btn btn-dark">'.$recup['libelleTitre'].'</button></a>';
                $count++;
              }
            ?>

        </div>

        <div class="card-footer">
          <?php
            echo '<a href="ajouter_titre.php?idtheme='.$_GET['idtheme'].'"><button  class="btn btn-dark float-right">'."Ajouter un titre".'</button></a>';
            echo '<a href="menu_themes.php?idtheme='.$_GET['idtheme'].'"><button  class="btn btn-dark float-right">'."Retour".'</button></a>';
          ?>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once('footer.php'); 
          mysqli_close($bdd);
    ?>
</body>
</html>