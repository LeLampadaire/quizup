<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>
<head>
	<title><?php echo $nomSite; ?> - Connexion</title>
	<meta charset="utf-8">
  <?php require_once('styles.php'); 
  	require_once('baseDeDonnee.php'); 
  	$error = 0;
  	if(!empty($_POST))
  	{
  		$mdp = md5($_POST['mdp']);
  		$email = $_POST['email'];
  		$ret = mysqli_query($bdd, 'SELECT usersession.idProfil, usersession.lastActivity, usersession.joursConsecutifs, profil.nomProfil FROM usersession, profil WHERE usersession.idProfil = profil.idProfil AND (email LIKE ("'.utf8_decode($email).'") OR profil.nomProfil LIKE("'.utf8_decode($email).'")) AND password LIKE ("'.$mdp.'");');
  		$ret = mysqli_fetch_array($ret, MYSQLI_ASSOC);
  		mysqli_close($bdd);
  		if($ret == NULL)
  		{
            $error = 1;
  		}
  		else
  		{
  			$error = 0;
  			session_unset();
				$_SESSION['idprofil'] = $ret['idProfil'];
				$_SESSION['nomprofil'] = $ret['nomProfil'];
  			header('Location: index.php');
  		}
  	}
  	else
  	{

  	}
  ?>

</head>
<body class="bg-secondary">
	<div>
		
    <!-- HEADER -->
		<?php 
		$connexion_class = "active";
		require_once('header.php'); ?>


		<!-- Contenu principale -->
		<section class="container text-center mt-5 text-white principale">
			
			<div class="card text-center bg-dark">
				<div class="card-header">
					<h3>J'ai déjà un compte QUIZUP</h3>
				</div>
			
			<div class="card-body">
				<form method="POST">
					<?php
						if ($error == 1) 
						{
							echo '<div class="alert alert-danger" role="alert">
		                      Identifiant ou mot de passe incorrect.
		                    </div>';	
						}	
							  
					?>
					<label>Nom de compte ou email</label><br>
					<input required type="text" name="email"><br>
					<label>Mot de passe</label><br>
					<input required type="password" name="mdp"><br>
					<input type="checkbox" name="rester_co"><label>Rester connecté</label><br><br>
					 <button type="submit" class="btn btn-light">Se connecter</button>
				</form>
			</div>
			<div class="card-footer">
				<a href="recup.php"><button class="btn btn-dark">Impossible de se connecter ?</button></a><br>
				<a href="inscription.php"><button class="btn btn-dark">Créer un compte ?</button></a>
			</div>
			</div>
		</section>
		<!-- Footer -->
    <?php require_once('footer.php'); ?>
</body>
</html>