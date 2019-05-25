<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php
      require_once('baseDeDonnee.php');
      require_once('configuration.php');

      $ancien_themes = mysqli_query($bdd, 'SELECT theme.libelleTheme as ancienLibelle, theme.description as ancienDescription, categorie.libelleCategorie as ancienCategorie FROM theme INNER JOIN categorie ON(theme.idCategorie = categorie.idCategorie)WHERE '.$_GET['idtheme'].' = theme.idTheme;');
      $ancien_themes = mysqli_fetch_array($ancien_themes, MYSQLI_ASSOC);

      $ajoutTheme_categorie = mysqli_query($bdd,'SELECT idCategorie, libelleCategorie FROM categorie');
      $valid_libelle = "class='form-control'";
      $valid_description = "";

      $ajoutTheme_profil = $_SESSION['idprofil'];
      
      $libelle ="";
      $description = "";
      $Scategorie = "- Sélectionner une catégorie -";

      if(isset($_POST) && !empty($_POST)){
        $libelle = $_POST['libelle'];//Vérifier si le thème existe déjà
        $description = $_POST['description'];
        $erreur = 0;

        if(preg_match($pattern_libelle, $_POST['libelle'])){
          $valid_libelle = 'class="form-control is-valid text-success"';
        }else{
          $valid_libelle = 'class="form-control is-invalid text-danger';
          $erreur=1;
        }

        //Si il n'y a aucune erreur, insertion dans la BDD et redirection vers les thèmes
        if($erreur == 0){
          //Insertion des données dans la table theme

          mysqli_query($bdd, 'UPDATE `theme` SET `libelleTheme` = "'.utf8_decode ( $_POST['libelle']).'", `description` = "'.utf8_decode ( $_POST['description']).'", `dateUpdated` = CURRENT_TIMESTAMP(), `idCategorie` = '.$_POST['categorie'].' WHERE theme.idTheme = '.$_GET['idtheme'].';');
          if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0 )
          {
            $extension_upload = strtolower(  substr(  strrchr($_FILES['photo']['name'], '.')  ,1)  );
            if($extension_upload == "jpg" || $extension_upload == "png" || $extension_upload == "jpeg" || $extension_upload == "svg")
            {
              $nomphoto = "images/theme/".$_GET['idtheme'].".{$extension_upload}";
              $move = move_uploaded_file($_FILES['photo']['tmp_name'],$nomphoto);
              mysqli_query($bdd, 'UPDATE `theme` SET `logo` = "'.$nomphoto.'" WHERE theme.idTheme = '.$_GET['idtheme'].';');
            }
          }
          header('Location: themes.php');
        }
      }
    ?>

<head>
  <title><?php echo $nomSite; ?> - Modification d'un thème</title>
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
          <h2>Modifier un thème</h2>
        </div>
      <div class="card-body">
        <br>
      <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
          <div class="row justify-content-md-center">
            <div class="col-7 centered">
              <label for="libelle">Nom du thème</label>
              <input <?php echo $valid_libelle; ?> id="libelle" type="text" name="libelle" required value=<?php echo '"'.$ancien_themes['ancienLibelle'].'"'; ?>>
                  <div class="invalid-feedback">
                  Le nom du thème doit être composé de 3 caractères au minimum.
                </div>
                <br>
            </div>
          </div>
        </div>

        <!-- <input type="file" ========================== -->
        <div class="form-group">
          <div class="row justify-content-md-center">
            <div class="col-7 centered">
              <label for="photo">Illustration</label><br>
              <input id="photo" name="photo" type="file">
                <br>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row justify-content-md-center">
            <div class="col-7 centered">
              <label for="description">Description du thème</label>
              <textarea class="form-control" id="description" cols="50" rows="5" name="description" <?php if(empty($ancien_themes['ancienDescription'])){ echo 'placeholder="-250 caractères maximum-"';} ?> ><?php if(!empty($ancien_themes['ancienDescription'])){ echo $ancien_themes['ancienDescription'];} ?></textarea><br>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row justify-content-md-center">
            <div class="col-7 centered">
              <label for="categorie">Catégorie</label>
                <select class="form-control" id="categorie" name="categorie" required>
                  <?php
                    echo '<option value="" class="text-secondary">'.$ancien_themes['ancienCategorie'].'</option>';
                    foreach($ajoutTheme_categorie as $donnee_ajoutTheme_categorie){
                      echo'<option value="'. utf8_encode($donnee_ajoutTheme_categorie['idCategorie']) .'">'. utf8_encode($donnee_ajoutTheme_categorie['libelleCategorie']) .'</option>';
                    }
                  ?>
                </select><br>
            </div>
          </div>
        </div>

        <div class="row justify-content-md-center">
          <div class="col-7 centered">
            <button type="submit" class="btn btn-light">Modifier votre thème</button>
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