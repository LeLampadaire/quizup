<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php
      require_once('baseDeDonnee.php');
      require_once('configuration.php');

      if(isset($_GET) && !empty($_GET)){
        $ajouterquestion_idTheme = $_GET['idtheme'];
      }

      $supprimer_question = mysqli_query($bdd, 'SELECT COUNT(`idQuestion`) as nbquest FROM question INNER JOIN theme ON (theme.`idTheme` = question.`idTheme`) WHERE theme.`idTheme` = '.$_GET['idtheme'].';');
      $supprimer_question = mysqli_fetch_array($supprimer_question, MYSQLI_ASSOC);
      $supprimer_question = $supprimer_question['nbquest'];
      var_dump($supprimer_question);

      if($supprimer_question != 0){
        $erreur = 1;
        header('Location: themes.php?erreur='.$erreur.'');
      }else{
        mysqli_query($bdd, 'DELETE FROM `theme` WHERE `idTheme` IN ('.$_GET['idtheme'].');');
        header('Location: themes.php');
      }
    ?>

<head>
  <title><?php echo $nomSite; ?> - Supprimer un thème</title>
  <meta charset="utf-8">
  <?php require_once('styles.php'); ?>
</head>

</html>