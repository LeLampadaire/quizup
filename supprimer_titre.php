<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php
      require_once('baseDeDonnee.php');
      require_once('configuration.php');

      if(isset($_GET) && !empty($_GET)){
        $ajouterquestion_idTheme = $_GET['idtitre'];
      }

      $supprimer_titre = mysqli_query($bdd, 'SELECT COUNT(`idProfil`) as nbProfil FROM titre INNER JOIN remporter ON (titre.`idTitre` = remporter.`idTitre`) WHERE titre.`idTitre` = '.$_GET['idtitre'].';');
      $supprimer_titre = mysqli_fetch_array($supprimer_titre, MYSQLI_ASSOC);
      $supprimer_titre = $supprimer_titre['nbProfil'];
      var_dump($supprimer_titre);

      if($supprimer_titre != 0){
        $erreur = 1;
        header('Location: titre.php?erreur='.$erreur.'');
      }else{
        mysqli_query($bdd, 'DELETE FROM `titre` WHERE `idTitre` IN ('.$_GET['idtitre'].');');
        header('Location: themes.php');
      }
    ?>

<head>
  <title><?php echo $nomSite; ?> - Supprimer un titre</title>
  <meta charset="utf-8">
  <?php require_once('styles.php'); ?>
</head>

</html>