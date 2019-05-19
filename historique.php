<?php session_start() ; 

	require_once('baseDeDonnee.php'); 
	$pseudo = mysqli_query($bdd, 'SELECT nomProfil FROM profil WHERE idProfil = '.$_GET['idprofil'].';');
	$pseudo = mysqli_fetch_array($pseudo, MYSQLI_ASSOC);
	$partie = mysqli_query($bdd, 'SELECT distinct partie.idPartie, partie.timestampPartie, theme.idTheme, theme.libelleTheme FROM participer, partie, integrer, question, theme WHERE participer.idProfil = '.$_GET['idprofil'].' AND participer.idPartie = partie.idPartie AND partie.idPartie = integrer.idPartie AND integrer.idQuestion = question.idQuestion AND question.idTheme = theme.idTheme ORDER BY partie.timestampPartie DESC;');
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
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>
<head>
	<title><?php echo $nomSite; ?> - Historique de <?php echo utf8_encode($pseudo['nomProfil']); ?></title>
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
					<h2>Historique de <?php echo utf8_encode($pseudo['nomProfil']); ?></h2>
				</div>
				<div class="card-body">
					<?php
						foreach ($liste_partie as $value) 
						{
							echo '<a href="resultat.php?idpartie='.$value['idPartie'].'&idprofil='.$_GET['idprofil'].'"><button class="btn btn-dark">Partie jouée le '.$value['timestampPartie'].' sur le thème "'.$value['libelleTheme'].'"</button></a><br><br>';
						}
					 ?>
					
				</div>
				<div class="card-footer">
					<?php echo '<a href="profil.php?idprofil='.$_GET['idprofil'].'">';?><button type="button" class="btn btn-dark">Revenir au profil</button></a>
				</div>
			</div>
		</section>
		<!-- Footer -->
    <?php require_once('footer.php'); ?>
</body>
</html>