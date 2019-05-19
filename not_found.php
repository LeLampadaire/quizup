<?php session_start() ; 
?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>
<head>
	<title><?php echo $nomSite; ?> - Recherche</title>
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
					<h2>Résultats de la recherche :</h2>
				</div>
				<div class="card-body">
					<h2>Aucun profil ne correspond à la recherche</h2>
				</div>
				<div class="card-footer">
					<a href="index.php"><button class="btn btn-dark">Revenir à l'accueil</button></a>
				</div>
			</div>
		</section>
		<!-- Footer -->
    <?php require_once('footer.php'); ?>
</body>
</html>