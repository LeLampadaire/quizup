<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
    <?php
    require_once('configuration.php'); 
    require_once('baseDeDonnee.php');

    $ancien_themes = mysqli_query($bdd, 'SELECT theme.libelleTheme as ancienLibelle, theme.description as ancienDescription, categorie.libelleCategorie as ancienCategorie, theme.idProfil as ThemeidProfil FROM theme INNER JOIN categorie ON(theme.idCategorie = categorie.idCategorie)WHERE '.$_GET['idtheme'].' = theme.idTheme;');
    $ancien_themes = mysqli_fetch_array($ancien_themes, MYSQLI_ASSOC);

    $theme_class = "active";
    ?>

<head>
	<title><?php echo $nomSite .' - '. $ancien_themes['ancienLibelle']; ?></title>
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
          <h2><?php echo $ancien_themes['ancienLibelle']; ?></h2>
        </div>
        <div class="card-body">
          <?php
            if(empty($_SESSION)){
              echo '<div class="alert alert-danger" role="alert">
                      Vous devez être connecté pour pouvoir voir les thèmes !
                    </div>';
            }else{
              if($ancien_themes['ThemeidProfil'] <> $_SESSION['idprofil']){
                echo '<a href="creation_partie.php?idtheme='.$_GET['idtheme'].'"><button class="btn btn-outline-light">'."Play".'</button></a>';
                echo '<a href="suivre.php?idtheme='.$_GET['idtheme'].'"><button class="btn btn-outline-light">'."Suivre".'</button></a><br><br>';
              }else{
                echo '<a href="creation_partie.php?idtheme='.$_GET['idtheme'].'"><button class="btn btn-outline-light">'."Play".'</button></a>';
                echo '<a href="suivre.php?idtheme='.$_GET['idtheme'].'"><button class="btn btn-outline-light">'."Suivre".'</button></a>';
                echo '<a href="mise_a_jour_Themes.php?idtheme='.$_GET['idtheme'].'"><button  class="btn btn-outline-light">'."Modifier".'</button></a>';
                
                echo '<a href="supprimer_themes.php?idtheme='.$_GET['idtheme'].'"><button  class="btn btn-outline-light">'."Supprimer ce thème".'</button></a><br><br>';
              }

              if(empty($ancien_themes['ancienDescription'])){
                echo "Aucune description";
              }else{
                echo '<h3>'."Description :".'</h3>';
                echo $ancien_themes['ancienDescription'];
              }
            }
            ?>
        </div>

        <div class="card-footer">
          <?php
            echo '<a href="liste_question.php?idtheme='.$_GET['idtheme'].'"><button  class="btn btn-dark float-right">'."Liste des questions".'</button></a>';
          ?>
          <a href="themes.php"><button class="btn btn-dark float-left">Retour</button></a>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once('footer.php'); 
          mysqli_close($bdd);
    ?>
</body>
</html>