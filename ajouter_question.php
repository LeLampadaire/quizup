<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php
      require_once('baseDeDonnee.php');
      require_once('configuration.php');

      if(isset($_GET) && !empty($_GET)){
        $ajouterquestion_idTheme = $_GET['idtheme'];
      }

      $valid_libelle = "class='form-control'";
      $valid_answer = "class='form-control'";
      $valid_dist1 = "class='form-control'";
      $valid_dist2 = "class='form-control'";
      $valid_dist3 = "class='form-control'";
      
      $libelle = "";
      $answer = "";
      $dist1 = "";
      $dist2 = "";
      $dist3 = "";

      if(isset($_POST) && !empty($_POST)){
        $libelle = $_POST['libelle'];//Vérifier si la question existe déjà
        $answer = $_POST['answer'];
        $dist1 = $_POST['dist1'];
        $dist2 = $_POST['dist2'];
        $dist3 = $_POST['dist3'];
        $erreur = 0;

        if(preg_match($pattern_libelle, $_POST['libelle'])){
          $valid_libelle = 'class="form-control is-valid text-success"';
        }else{
          $valid_libelle = 'class="form-control is-invalid text-danger';
          $erreur=1;
        }

        if(preg_match($pattern_verif, $_POST['answer'])){
          $valid_answer = 'class="form-control is-valid text-success"';
        }else{
          $valid_answer = 'class="form-control is-invalid text-danger';
          $erreur=1;
        }

        if(preg_match($pattern_verif, $_POST['dist1'])){
          $valid_dist1 = 'class="form-control is-valid text-success"';
        }else{
          $valid_dist1 = 'class="form-control is-invalid text-danger';
          $erreur=1;
        }

        if(preg_match($pattern_verif, $_POST['dist2'])){
          $valid_dist2 = 'class="form-control is-valid text-success"';
        }else{
          $valid_dist2 = 'class="form-control is-invalid text-danger';
          $erreur=1;
        }

        if(preg_match($pattern_verif, $_POST['dist3'])){
          $valid_dist3 = 'class="form-control is-valid text-success"';
        }else{
          $valid_dist3 = 'class="form-control is-invalid text-danger';
          $erreur=1;
        }

        //Si il n'y a aucune erreur, insertion dans la BDD et redirection vers les thèmes
        if($erreur == 0){
          //Insertion des données dans la table question

          mysqli_query($bdd, 'INSERT INTO question (idQuestion, Illustration, libelleQuestion, answer, distracteur01, distracteur02, distracteur03, idTheme) VALUES (NULL, NULL, "'.$libelle.'", "'.$answer.'", "'.$dist1.'", "'.$dist2.'", "'.$dist3.'", '.$ajouterquestion_idTheme.')');
          mysqli_query($bdd, 'UPDATE `theme` SET `dateUpdated`= CURRENT_TIMESTAMP() WHERE `idTheme` = '.$ajouterquestion_idTheme.'');
          $idquestion = mysqli_query($bdd, 'SELECT idQuestion FROM question WHERE libelleQuestion = "'.$libelle.'";');
          $idquestion = mysqli_fetch_assoc($idquestion);
          $idquestion = (int)$idquestion['idQuestion'];

          if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0 )
          {
            $extension_upload = strtolower(  substr(  strrchr($_FILES['photo']['name'], '.')  ,1)  );
            if($extension_upload == "jpg" || $extension_upload == "png" || $extension_upload == "jpeg" || $extension_upload == "svg")
            {
              echo"TESTESTEST";
              $nomphoto = "images/question/".$idquestion.".{$extension_upload}";
              $move = move_uploaded_file($_FILES['photo']['tmp_name'],$nomphoto);
              $insert = mysqli_query($bdd, 'UPDATE `question` SET `illustration` = "'.$nomphoto.'" WHERE question.idQuestion = '.$idquestion.';');
              var_dump($insert);
            }
          }
          //header('Location: themes.php');
        }
      }
    ?>

<head>
  <title><?php echo $nomSite; ?> - Ajout d'une question</title>
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
          <h2>Ajouter une nouvelle question</h2>
        </div>
      <div class="card-body">
        <br>
      <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
          <div class="row justify-content-md-center">
            <div class="col-7 centered">
              <label for="libelle">Question</label>
              <input <?php echo $valid_libelle; ?> id="libelle" type="text" name="libelle" required value=<?php echo '"'.$libelle.'"'; ?>>
                  <div class="invalid-feedback">
                  Le nom de la question doit être composé de 3 caractères au minimum.
                </div>
                <br>
            </div>
          </div>
        </div>

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
              <label for="answer">Réponse</label>
              <input <?php echo $valid_answer; ?> id="answer" type="text" name="answer" required value=<?php echo '"'.$answer.'"'; ?>>
                <br>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row justify-content-md-center">
            <div class="col-7 centered">
              <label for="dist1">Premier distracteur</label>
              <input <?php echo $valid_dist1; ?> id="dist1" type="text" name="dist1" required value=<?php echo '"'.$dist1.'"'; ?>>
                <br>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row justify-content-md-center">
            <div class="col-7 centered">
              <label for="dist2">Deuxième distracteur</label>
              <input <?php echo $valid_dist2; ?> id="dist2" type="text" name="dist2" required value=<?php echo '"'.$dist2.'"'; ?>>
                <br>
            </div>
          </div>
        </div>

        <div class="form-group">
          <div class="row justify-content-md-center">
            <div class="col-7 centered">
              <label for="dist3">Troisième distracteur</label>
              <input <?php echo $valid_dist3; ?> id="dist3" type="text" name="dist3" required value=<?php echo '"'.$dist3.'"'; ?>>
                <br>
            </div>
          </div>
        </div>

        <div class="row justify-content-md-center">
          <div class="col-7 centered">
            <button type="submit" class="btn btn-light">Ajouter votre question</button>
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