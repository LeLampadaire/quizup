<?php session_start() ; 
?>
<!DOCTYPE html>
<html lang="fr">
		<?php require_once('configuration.php'); 
					$index = "active";
		?>
<head>
	<title><?php echo $nomSite; ?> - Accueil</title>
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
					<h2>Accueil</h2>
				</div>
				<div class="card-body">
					<p>Bienvenue sur QUIZUP</p>
				</div>
				<div class="card-footer">
					
				</div>
			</div>
		</section>
		<!-- Footer -->
    <?php require_once('footer.php'); ?>
</body>
</html>