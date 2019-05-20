<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
  	<?php
      require_once('baseDeDonnee.php');
      require_once('configuration.php');

      if(isset($_GET) && !empty($_GET)){
        $ajouterquestion_idTheme = $_GET['idcategorie'];
      }

      $supprimer_categorie = mysqli_query($bdd, 'SELECT COUNT(`idTheme`) as nbtheme FROM theme INNER JOIN categorie ON (theme.`idCategorie` = categorie.`idCategorie`) WHERE categorie.`idCategorie` = '.$_GET['idcategorie'].';');
      $supprimer_categorie = mysqli_fetch_array($supprimer_categorie, MYSQLI_ASSOC);
      $supprimer_categorie = $supprimer_categorie['nbtheme'];
      var_dump($supprimer_categorie);

      if($supprimer_categorie != 0){
        $erreur = 1;
        header('Location: categorie.php?erreur='.$erreur.'');
      }else{
        mysqli_query($bdd, 'DELETE FROM `categorie` WHERE `idCategorie` IN ('.$_GET['idcategorie'].');');
        header('Location: categorie.php');
      }
    ?>

<head>
  <title><?php echo $nomSite; ?> - Supprimer une catégorie</title>
  <meta charset="utf-8">
  <?php require_once('styles.php'); ?>
</head>

</html>