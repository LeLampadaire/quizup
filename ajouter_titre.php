<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php
      require_once('baseDeDonnee.php');
      require_once('configuration.php');
    
      //Vérification qu'il est connecté pour ajouter un titre
      if($_SESSION['idprofil'] == NULL){
        header('Location: 404.php');
      }

      if(isset($_GET) && !empty($_GET)){
        $ajoutertitre_idTheme = $_GET['idtheme'];
      }

      $valid_libelle = "class='form-control'";
      $valid_answer = "class='form-control'";
      
      $libelle = "";
      $answer = "";

      if(isset($_POST) && !empty($_POST)){
        $libelle = $_POST['libelle'];//Vérifier si la question existe déjà
        $answer = $_POST['answer'];
        $erreur = 0;

        if(preg_match($pattern_libelle, $_POST['libelle'])){
          $valid_libelle = 'class="form-control is-valid text-success"';
        }else{
          $valid_libelle = 'class="form-control is-invalid text-danger';
          $erreur=1;
        }

        if(preg_match($pattern_libelle, $_POST['answer'])){
          $valid_answer = 'class="form-control is-valid text-success"';
        }else{
          $valid_answer = 'class="form-control is-invalid text-danger';
          $erreur=1;
        }

        //Si il n'y a aucune erreur, insertion dans la BDD et redirection vers les thèmes
        if($erreur == 0){
          //Insertion des données dans la table question

          mysqli_query($bdd, 'INSERT INTO titre (idTitre, libelleTitre, niveauRequis, idTheme) VALUES (NULL, "'.$libelle.'", "'.$answer.'", '.$ajoutertitre_idTheme.')');

          header('Location: themes.php');
        }
      }
    ?>

<head>
  <title><?php echo $nomSite; ?> - Ajout d'un titre</title>
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
          <h2>Ajouter un nouveau titre</h2>
        </div>
      <div class="card-body">
        <br>
      <form method="POST">

        <div class="form-group">
          <div class="row justify-content-md-center">
            <div class="col-7 centered">
              <label for="libelle">Libellé du titre</label>
              <input <?php echo $valid_libelle; ?> id="libelle" type="text" name="libelle" required value=<?php echo '"'.$libelle.'"'; ?>>
                  <div class="invalid-feedback">
                  Le nom du titre doit être composé de 3 caractères au minimum.
                </div>
                <br>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row justify-content-md-center">
            <div class="col-7 centered">
              <label for="answer">Niveau requis</label>
              <input <?php echo $valid_answer; ?> id="answer" type="text" name="answer" required value=<?php echo '"'.$answer.'"'; ?>>
                <br>
            </div>
          </div>
        </div>

        <div class="row justify-content-md-center">
          <div class="col-7 centered">
            <button type="submit" class="btn btn-light">Ajouter votre titre</button>
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