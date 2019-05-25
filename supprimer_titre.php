<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php
      require_once('baseDeDonnee.php');
      require_once('configuration.php');

      if(isset($_GET) && !empty($_GET)){
        $ajouterquestion_idTheme = $_GET['idtitre'];
      }

      $supprimer_categorie = mysqli_query($bdd, 'SELECT COUNT(suivre.`idProfil`) as nbProfil FROM titre INNER JOIN theme ON (titre.`idTheme` = theme.`idTheme`) INNER JOIN suivre ON (theme.`idProfil` = suivre.`idProfil`) WHERE titre.`idTitre` = '.$_GET['idtitre'].';');
      $supprimer_categorie = mysqli_fetch_array($supprimer_categorie, MYSQLI_ASSOC);
      $supprimer_categorie = $supprimer_categorie['nbProfil'];
      var_dump($supprimer_categorie);

      if($supprimer_categorie != 0){
        $erreur = 1;
        header('Location: titre.php?erreur='.$erreur.'');
      }else{
        mysqli_query($bdd, 'DELETE FROM `titre` WHERE `idTitre` IN ('.$_GET['idtitre'].');');
        header('Location: titre.php');
      }
    ?>

<head>
  <title><?php echo $nomSite; ?> - Supprimer un titre</title>
  <meta charset="utf-8">
  <?php require_once('styles.php'); ?>
</head>

</html>