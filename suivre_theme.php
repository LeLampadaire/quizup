<?php session_start() ; 
?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php');
    	require_once('baseDeDonnee.php');
    	$liste = mysqli_query($bdd, 'SELECT theme.idTheme, theme.libelleTheme as libelle FROM `theme`, profil, suivre WHERE theme.idTheme = suivre.idTheme AND suivre.idProfil = profil.idProfil AND profil.idProfil = '.$_SESSION['idprofil'].';');
    	mysqli_close($bdd);
    ?>
<head>
	<title><?php echo $nomSite; ?> - Mes thèmes suivis</title>
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
					<h2>Liste des thèmes suivis</h2>
				</div>
				<div class="card-body clearfix">
					<?php
					$i = 0;
						while ($recup = mysqli_fetch_assoc($liste)) {
							$i++;
						 	echo '<a href="menu_themes.php?idtheme='.$recup['idTheme'].'"><button type="button" class="btn btn-outline-light">'.utf8_encode($recup['libelle']).'</button></a>
						 	<br><br>';
						} 
						if ($i == 0) {
							echo "<h3>Vous ne suivez aucun thème</h3>";
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