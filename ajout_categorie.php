<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php
      require_once('baseDeDonnee.php');
      require_once('configuration.php');

      if(isset($_GET) && !empty($_GET)){
        $ajoutercategorie_idCategorie = $_GET['idcategorie'];
      }

      $valid_libelle = "class='form-control'";
      
      $libelle = "";

      if(isset($_POST) && !empty($_POST)){
        $libelle = $_POST['libelle'];//Vérifier si la question existe déjà
        $erreur = 0;

        if(preg_match($pattern_libelle, $_POST['libelle'])){
          $valid_libelle = 'class="form-control is-valid text-success"';
        }else{
          $valid_libelle = 'class="form-control is-invalid text-danger';
          $erreur=1;
        }

        //Si il n'y a aucune erreur, insertion dans la BDD et redirection vers les thèmes
        if($erreur == 0){
          //Insertion des données dans la table question

          mysqli_query($bdd, 'INSERT INTO categorie (idCategorie, libelleCategorie) VALUES (NULL, "'.$libelle.'")');

          header('Location: categorie.php');
        }
      }
    ?>

<head>
  <title><?php echo $nomSite; ?> - Ajout d'une catégorie</title>
  <meta charset="utf-8">
  <?php require_once('styles.php'); ?>
</head>

<body class="bg-secondary">
  <div>
    <!-- HEADER -->
    <?php require_once('header.php'); ?>

    <!-- Contenu principale -->
    <section class="container text-center mt-5 text-white principale">
      <div class="card text-center bg-dark">
        <div class="card-header">
          <h2>Ajouter une nouvelle catégorie</h2>
        </div>
      <div class="card-body">
        <br>
      <form method="POST">

        <div class="form-group">
          <div class="row justify-content-md-center">
            <div class="col-7 centered">
              <label for="libelle">Catégorie</label>
              <input <?php echo $valid_libelle; ?> id="libelle" type="text" name="libelle" required value=<?php echo '"'.$libelle.'"'; ?>>
                  <div class="invalid-feedback">
                  Le nom de la catégorie doit être composé de 3 caractères au minimum.
                </div>
                <br>
            </div>
          </div>
        </div>

        <div class="row justify-content-md-center">
          <div class="col-7 centered">
            <button type="submit" class="btn btn-light">Ajouter votre catégorie</button>
          </div>
        </div>
      </form>
    </div>


    </section>
    <!-- Footer -->
    <?php
      require_once('footer.php');
      mysqli_close($bdd);
    ?>


</body>
</html>