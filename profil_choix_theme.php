<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); 
        require_once('baseDeDonnee.php'); 
    $idprofil = $_GET['idprofil'];
    $theme = mysqli_query($bdd, 'SELECT DISTINCT theme.idTheme, theme.libelleTheme FROM theme, question, integrer, partie, participer WHERE theme.idTheme = question.idTheme AND question.idQuestion = integrer.idQuestion AND integrer.idPartie = partie.idPartie AND partie.idPartie = participer.idPartie AND participer.idProfil = '.$idprofil.' ORDER BY theme.libelleTheme;');
    $pseudo = mysqli_query($bdd, 'SELECT nomProfil FROM profil WHERE idProfil = '.$_GET['idprofil'].';');
    $pseudo = mysqli_fetch_array($pseudo, MYSQLI_ASSOC);
    $partie = mysqli_query($bdd, 'SELECT distinct partie.idPartie, partie.timestampPartie, theme.idTheme, theme.libelleTheme FROM participer, partie, integrer, question, theme WHERE participer.idProfil = '.$_GET['idprofil'].' AND participer.idPartie = partie.idPartie AND partie.idPartie = integrer.idPartie AND integrer.idQuestion = question.idQuestion AND question.idTheme = theme.idTheme;');
    $liste_partie = array();
    $i = 0;
    while($recup = mysqli_fetch_array($partie, MYSQLI_ASSOC))
    {
      $liste_partie[$i] = $recup;
      $i++;
    }

    $i = 0;
    foreach ($liste_partie as $value) 
    {
      $verifPartie = mysqli_query($bdd, 'SELECT COUNT(idQuestion) AS nb_rep FROM repondre WHERE idPartie = '.$value['idPartie'].';');
      $verifPartie = mysqli_fetch_array($verifPartie, MYSQLI_ASSOC);
      
      if ($verifPartie['nb_rep'] != 7) 
      {
        $idPartieSupp = $liste_partie[$i]['idPartie'];
        unset($liste_partie[$i]);
        
        $supp1 = mysqli_query($bdd, 'DELETE FROM repondre WHERE idPartie = '.$idPartieSupp.';');
        $supp2 = mysqli_query($bdd, 'DELETE FROM integrer WHERE idPartie = '.$idPartieSupp.';');
        $supp3 = mysqli_query($bdd, 'DELETE FROM participer WHERE idPartie = '.$idPartieSupp.';');
        $supp4 = mysqli_query($bdd, 'DELETE FROM partie WHERE idPartie = '.$idPartieSupp.';');
      }
      $i++;
    }
    ?>
<head>
	<title><?php echo $nomSite; ?> - Choix Thème</title>
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
          <h2>Classement par thème</h2>
          <p>Choix du thème :</p>
        </div>
        <div class="card-body"> 
            <?php
              $i = 0;
              while($recup = mysqli_fetch_array($theme, MYSQLI_ASSOC)) 
              {
                 echo '<a href="classement_profil.php?idprofil='.$idprofil.'&idtheme='.$recup['idTheme'].'"><button class="btn btn-dark">'.$recup['libelleTheme'].'</button></a><br>';
                 $i++;
              } 
              if($i == 0)
              {
                echo "<p>Ce joueur n'a remporté aucune partie</p>";
              }
            ?>
        </div>
        <div class="card-footer">
          <a href=<?php echo "profil.php?idprofil=".$idprofil."" ?>><button class="btn btn-dark">Revenir au profil</button></a>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once('footer.php'); ?>
</body>
</html>