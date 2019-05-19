<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
    <?php
    require_once('configuration.php'); 
    require_once('baseDeDonnee.php');

    $ancien_categorie = mysqli_query($bdd, 'SELECT categorie.libelleCategorie as ancienLibelle FROM categorie WHERE '.$_GET['idcategorie'].' = categorie.idCategorie;');
    $ancien_categorie = mysqli_fetch_array($ancien_categorie, MYSQLI_ASSOC);

    $theme_class = "active";
    ?>

<head>
	<title><?php echo $nomSite .' - '. $ancien_categorie['ancienLibelle']; ?></title>
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
          <h2><?php echo $ancien_categorie['ancienLibelle']; ?></h2>
        </div>
        <div class="card-body">
          <?php
            if(empty($_SESSION)){
              echo '<div class="alert alert-danger" role="alert">
                      Vous devez être connecté pour pouvoir voir les catégorie !
                    </div>';
            }else{
                echo '<a href="mise_a_jour_categorie.php?idcategorie='.$_GET['idcategorie'].'"><button  class="btn btn-outline-light">'."Modifier".'</button></a>';
                echo '<a href="supprimer_categorie.php?idtheme='.$_GET['idcategorie'].'"><button  class="btn btn-outline-light">'."Supprimer cette catégorie".'</button></a><br><br>';
            }
            ?>
        </div>

        <div class="card-footer">
          <?php
            echo '<a href="ajouter_categorie.php"><button  class="btn btn-dark float-right">'."Ajouter une catégorie".'</button></a>';
          ?>
          <a href="categorie.php"><button class="btn btn-dark float-left">Retour</button></a>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once('footer.php'); 
          mysqli_close($bdd);
    ?>
</body>
</html>