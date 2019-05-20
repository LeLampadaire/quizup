<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php
      require_once('baseDeDonnee.php');
      require_once('configuration.php');

      $questionSupp = mysqli_query($bdd, 'SELECT idQuestion FROM integrer Where idQuestion = '.$_GET['idquestion'].';');
      $questionSupp = mysqli_fetch_array($questionSupp, MYSQLI_ASSOC);

      if($questionSupp != NULL){
        $erreur = 1;
        header('Location: liste_question.php?idtheme='.$_GET[idtheme].'&erreur='.$erreur.'');
      }else{
        mysqli_query($bdd, 'DELETE FROM `question` WHERE `idQuestion` IN ('.$_GET['idquestion'].');');
        header('Location: liste_question.php?idtheme='.$_GET[idtheme].'');
      }
    ?>

<head>
  <title><?php echo $nomSite; ?> - Supprimer une question</title>
  <meta charset="utf-8">
  <?php require_once('styles.php'); ?>
</head>

</html>