<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php require_once('configuration.php'); 
    require_once('baseDeDonnee.php'); 
    $categorie = mysqli_query($bdd, 'SELECT categorie.idCategorie, categorie.libelleCategorie FROM categorie ORDER BY libelleCategorie ASC;');

    $categorie_class = "active";
    ?>

<head>
	<title><?php echo $nomSite; ?> - Catégorie</title>
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
          <h2>Catégorie</h2>
        </div>
        <div class="card-body">
          <?php 
            if(isset($_GET['erreur']) && $_GET['erreur'] == 1){
              echo '<div class="alert alert-danger" role="alert">';
              echo 'Cette catégorie ne peut pas être supprimé car il possède au-moins une question !';
              echo '</div>';
            }
           ?>
          <?php
              $count = 0;
              while($recup = mysqli_fetch_array($categorie, MYSQLI_ASSOC)){
                if($count != 0){
                  echo '<hr>';
                }
                echo '<a href="menu_categorie.php?idcategorie='.$recup['idCategorie'].'"><button class="btn btn-dark">'.$recup['libelleCategorie'].'</button></a>';
                $count++;
              }
            ?>

        </div>

        <div class="card-footer">
          <a href="index.php"><button class="btn btn-dark float-left">Accueil</button></a>
          <a href="ajout_categorie.php"><button class="btn btn-dark float-right">Ajouter une catégorie</button></a>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once('footer.php'); 
          mysqli_close($bdd);
    ?>
</body>
</html>