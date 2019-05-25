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
		<section class="container mt-5 text-white principale">
			<div class="card bg-dark">
				<div class="card-header text-center">
					<h2>Accueil</h2>
				</div>
				<div class="card-body">
					<h4 class="text-center">Bienvenue sur QUIZUP !</h4>
					
					<br>
					<p>Site crée par : </p>

					<ol>
						<li>Thomas Beck -> Sprint Bleu</li>
						<li>Ahn Arnaud -> Sprint Rose</li>
						<li>Rausin Julien -> Sprint Vert</li>
					</ol>

					<p>Sprint orange réalisé par Thomas Beck, Ahn Arnaud et Rausin Julien !</p>
				</div>
				<div class="card-footer">
					
				</div>
			</div>
		</section>
		<!-- Footer -->
    <?php require_once('footer.php'); ?>
</body>
</html>