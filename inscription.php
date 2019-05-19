<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
    <?php 
	  require_once('baseDeDonnee.php'); 
		require_once('configuration.php');

		if(!empty($_SESSION)){
			header('Location: 404.php');
		}

		$inscription_pays = mysqli_query($bdd,'SELECT idPays, libellePays FROM pays');
		$inscription_region = mysqli_query($bdd,'SELECT libelleRegion FROM region');//A modifier en fonction du pays sélectionné ?
		$valid_pseudo = "class='form-control'";
		$valid_email = "class='form-control'";
		$valid_mdp = "class='form-control'";
		$valid_ville = "class='form-control'";
		$valid_langue = "class='form-control'";
		$valid_pays = "class='form-control'";
		$valid_region = "class='form-control'";
		$valid_biographie = "";
		
		$pseudo ="";
		$email ="";
		$mdp = "";
		$ville = "";//Problème REGEX, chiffres acceptés
		$Spays = "- Sélectionner un pays -";
		$region = "";
		$biographie = "";
		$prive = "";

		if(isset($_POST) && !empty($_POST))
		{
			$pseudo = $_POST['pseudo'];//Vérifier si le pseudo existe déjà
			$email = $_POST['email'];//Idem mail
			$ville = $_POST['ville'];
			$region = $_POST['region'];
			$biographie = $_POST['biographie'];
			$erreur = 0;
			if(isset($_POST['prive']))
			{
				$prive = "checked";
			}
			if(preg_match($pattern_pseudo, $_POST['pseudo']))
			{
				$valid_pseudo = 'class="form-control is-valid text-success"';
			}
			else
			{
				$valid_pseudo = 'class="form-control is-invalid text-danger';
				$erreur=1;
			}

			if(preg_match($pattern_email, $_POST['email']))
			{
				$valid_email = 'class="form-control is-valid text-success"';
			}
			else
			{
				$valid_email = 'class="form-control is-invalid text-danger';
				$erreur=1;
			}

			if(preg_match($pattern_mdp, $_POST['mdp']))
			{
				$valid_mdp = 'class="form-control is-valid text-success"';
			}
			else
			{
				$valid_mdp = 'class="form-control is-invalid text-danger';
				$erreur=1;
			}
			

			if(empty($ville) || preg_match($pattern_ville, $_POST['ville']))
			{
				$valid_ville = 'class="form-control is-valid text-success"';
			}
			else
			{
				$valid_ville = 'class="form-control is-invalid text-danger';
				$erreur=1;
			}


			if(preg_match($pattern_region, $_POST['region']))
			{
				$valid_region = 'class="form-control is-valid text-success"';
			}
			else
			{
				$valid_region = 'class="form-control is-invalid text-danger';
				$erreur=1;
			}

			//Si il n'y a aucune erreur, insertion dans la BDD et redirection vers le profil
			if($erreur == 0)
			{
				//Recherche de la région dans la bdd
				$region_bdd = mysqli_query($bdd, 'SELECT * FROM `region` WHERE idPays = '.$_POST['pays'].' AND libelleRegion LIKE("'.utf8_decode($_POST['region']).'");');
				$region_bdd = mysqli_fetch_array($region_bdd, MYSQLI_ASSOC);
				
				if($region_bdd == NULL)
				{
					mysqli_query($bdd, 'INSERT INTO `region` (`idRegion`, `libelleRegion`, `drapeauRegion`, `idPays`) VALUES (NULL, "'.utf8_decode($_POST['region']).'", NULL, '.$_POST['pays'].');');	
					$region_bdd = mysqli_query($bdd, 'SELECT * FROM `region` WHERE idPays = '.$_POST['pays'].' AND libelleRegion LIKE("'.utf8_decode($_POST['region']).'");');
					$region_bdd = mysqli_fetch_array($region_bdd, MYSQLI_ASSOC);
				}
				$idregion = $region_bdd['idRegion'];


				//Insertion des données dans la table profil
				mysqli_query($bdd, 'INSERT INTO `profil` (`idProfil`, `nomProfil`, `photoProfil`, `photoFacade`, `villeOrigine`, `langue`, `bio`, `profilPrive`, `diamants`, `idTitre`, `idPays`, `idRegion`) VALUES (NULL, "'.utf8_decode ( $_POST['pseudo']).'", "images/profil/null.jpg", NULL, "'.utf8_decode ( $_POST['ville']).'", "'.utf8_decode ( $_POST['langue']).'", "'.utf8_decode ( $_POST['biographie']).'", 0, 42, NULL, '.$_POST['pays'].', '.$idregion.');');

				$recup_id = mysqli_query($bdd, 'SELECT idProfil FROM `profil` WHERE nomProfil = "'.utf8_decode ($_POST['pseudo']).'";');
				$recup_id = mysqli_fetch_array($recup_id, MYSQLI_ASSOC);
				$recup_id = (int)$recup_id['idProfil'];
				

				$recup_date = date("Y-m-d h:m:s");
				
				//Insertion des données dans la table usersession
				$test  = mysqli_query($bdd, 'INSERT INTO `usersession` (`email`, `password`, `lastActivity`, `joursConsecutifs`, `idProfil`) VALUES ("'.utf8_decode ($_POST['email']).'", "'.md5($_POST['mdp']).'","'.$recup_date.'" , 0, '.$recup_id.');');
				mysqli_close($bdd);
				header('Location: connexion.php');
			}
		}
		
		
    ?>
<head>
	<title><?php echo $nomSite; ?> - Inscription</title>
	<meta charset="utf-8">
  <?php require_once('styles.php'); ?>

</head>
<body class="bg-secondary">
	<div>
		
    <!-- HEADER -->
		<?php 
		$inscription_class = "active";
		require_once('header.php'); ?>


		<!-- Contenu principale -->
		<section class="container text-center mt-5 text-white principale">
			<div class="card text-center bg-dark">
				<div class="card-header">
					<h2>Créez votre compte QUIZUP</h2>
				</div>
			<div class="card-body">
				<br>
			<form action="inscription.php" method="POST">

				<div class="form-group">
					<div class="row justify-content-md-center">
						<div class="col-7 centered">
							<label for="pseudo">Pseudo</label>
							<input <?php echo $valid_pseudo; ?> id="pseudo" type="text" name="pseudo" required value=<?php echo '"'.$pseudo.'"'; ?>>
      						<div class="invalid-feedback">
						    	Le pseudo doit être composé de 3 caractères au minimum.
						    </div>
						    <br>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row justify-content-md-center">
						<div class="col-7 centered">
							<label for="email">E-mail</label>
							<input <?php echo $valid_email; ?> id="email" type="mail" name="email" placeholder="exemple@exemple.com" required value=<?php echo '"'.$email.'"'; ?>>
      						<div class="invalid-feedback">
						    	E-mail invalide.
						    </div>
						    <br>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row justify-content-md-center">
						<div class="col-7 centered">
							<label for="mdp">Mot de passe</label>
							<input <?php echo $valid_mdp; ?> id="mdp" type="password" name="mdp" required value=<?php echo '"'.$mdp.'"'; ?>>
      						<div class="invalid-feedback">
						    	Mot de passe invalide.
						    </div>
						    <br>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row justify-content-md-center">
						<div class="col-7 centered">
							<label for="ville">Ville d'origine</label>
							<input <?php echo $valid_ville; ?> id="ville" type="text" name="ville" value=<?php echo '"'.$ville.'"'; ?>>
							<div class="invalid-feedback">
						    	La ville doit être composée de 3 lettres au minimum.
						    </div>
							<br>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row justify-content-md-center">
						<div class="col-7 centered">
							<label for="langue">Langue</label>
								<select class="form-control"  name="langue" id="langue" required>
									<option value="allemand">Allemand</option>
									<option value="anglais">Anglais</option>
									<option value="espagnol">Espagnol</option>
									<option value="français">Français</option>
									<option value="néerlandais">néerlandais</option>
								</select><br>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row justify-content-md-center">
						<div class="col-7 centered">
							<label for="pays">Pays</label>
								<select class="form-control" id="pays" name="pays" required>
									<?php
										echo '<option value="" class="text-secondary">'.$Spays.'</option>';
										foreach($inscription_pays as $donnee_inscription_pays)
										{
											echo'<option value="'. $donnee_inscription_pays['idPays'] .'">'. utf8_encode($donnee_inscription_pays['libellePays']) .'</option>';
										}
									?>
								</select><br>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row justify-content-md-center">
						<div class="col-7 centered">
							<label for="region">Région</label>
							<input <?php echo $valid_region; ?> id="region"  required list="region_datalist" name="region" value=<?php echo '"'.$region.'"'; ?>>
								<datalist id="region_datalist">
									<?php
										foreach($inscription_region as $donnee_inscription_region)
										{
											echo '<option value="'. utf8_encode($donnee_inscription_region['libelleRegion']) .'">';
										}
									?>
								</datalist>
								<div class="invalid-feedback">
						    	La région doit être composé de 3 lettres au minimum.
						    	</div>
								<br>
						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="row justify-content-md-center">
						<div class="col-7 centered">
							<label for="biographie">Biographie</label>
							<textarea class="form-control" id="biographie" cols="50" rows="5" name="biographie" <?php if(empty($_POST['biographie'])){ echo 'placeholder="-250 caractères maximum-"';} ?> ><?php if(!empty($_POST['biographie'])){ echo $biographie;} ?></textarea><br>
						</div>
					</div>
				</div>
				<div class="row justify-content-md-center">
					<div class="col-7 centered">
				 		<button type="submit" class="btn btn-light">S'inscrire</button>
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer">
				<a href="connexion.php"><button class="btn btn-dark">J'ai déjà un compte</button></a>
			</div>
		</section>
		<!-- Footer -->
    <?php require_once('footer.php'); ?>


</body>
</html>