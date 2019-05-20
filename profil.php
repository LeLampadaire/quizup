<?php session_start() ; 
	require_once('baseDeDonnee.php');
	
	/***********************************SUPPRESSION PARTIE NON TERMINÉE***********************/
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

	if(isset($_GET['idprofil']) && !empty($_GET['idprofil'])) 
	{
		$idprofil = $_GET['idprofil'];
	}
	else
	{
		header('Location: 404.php');
	}

	$connected = 0;
	if(isset($_SESSION['idprofil']) && $idprofil == $_SESSION['idprofil'])
	{
		$connected = 1;
	}
	
	$donnee_profil = mysqli_query($bdd, 'SELECT * FROM profil WHERE idProfil = '.$idprofil.';');
	$donnee_profil = mysqli_fetch_array($donnee_profil, MYSQLI_ASSOC);
	$pseudo = $donnee_profil['nomProfil'];
	$photo_profil = $donnee_profil['photoProfil'];
	$cover = $donnee_profil['photoFacade'];
	$ville = $donnee_profil['villeOrigine'];
	$langue = $donnee_profil['langue'];
	$bio = $donnee_profil['bio'];
	$prive = $donnee_profil['profilPrive'];
	$diamants = $donnee_profil['diamants'];
	$id_titre = $donnee_profil['idTitre'];
	$id_pays = $donnee_profil['idPays'];
	$id_region = $donnee_profil['idRegion'];

	//Recup du pays, de la region et du titre
	$titre = mysqli_query($bdd, 'SELECT libelleTitre FROM titre WHERE idTitre = '.$id_titre.';');
	if($titre != NULL)
	{
		$titre = mysqli_fetch_array($titre, MYSQLI_ASSOC);
		$titre = $titre['libelleTitre'];
	}
	
	$pays = mysqli_query($bdd, 'SELECT libellePays, drapeauPays FROM pays WHERE idPays = '.$id_pays.';');
	$pays = mysqli_fetch_array($pays, MYSQLI_ASSOC);

	$region = mysqli_query($bdd, 'SELECT * FROM region WHERE idRegion = '.$id_region.';');
	
	if($region != NULL)
	{
		$region = mysqli_fetch_array($region, MYSQLI_ASSOC);
		$drapeauregion = $region['drapeauRegion'];
		$region = $region['libelleRegion'];
	}

	$exist_profil = mysqli_query($bdd, 'SELECT * FROM usersession WHERE `idProfil` = '.$_GET['idprofil'].';');
	$exist_profil = mysqli_fetch_assoc($exist_profil);

	$followed = mysqli_query($bdd, 'SELECT * FROM `s_abonner` WHERE `idProfil` = '.$_SESSION['idprofil'].' AND `idProfil_1` = '.$_GET['idprofil'].' ;');
	$followed = mysqli_fetch_assoc($followed);
	if($followed == NULL)
	{
		$followed = 0;
	}
	else
	{
		$followed = 1;
	}

	$block = mysqli_query($bdd, 'SELECT * FROM `bloquer` WHERE `idProfil` = '.$_SESSION['idprofil'].' AND `idProfil_1` = '.$_GET['idprofil'].';');
	$block = mysqli_fetch_assoc($block);
	if($block == NULL)
	{
		$block = 0;
	}
	else
	{
		$block = 1;
	}

	$blocked = mysqli_query($bdd, 'SELECT * FROM `bloquer` WHERE `idProfil` = '.$_GET['idprofil'].' AND `idProfil_1` = '.$_SESSION['idprofil'].';');
	$blocked = mysqli_fetch_assoc($blocked);
	if($blocked == NULL)
	{
		$blocked = 0;
	}
	else
	{
		$blocked = 1;
	}

?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>
<head>
	<title><?php echo $nomSite; ?> - Profil</title>
	<meta charset="utf-8">
  <?php require_once('styles.php'); 
  		if(!empty($photo_profil))
  		{
  			echo "<style>";
  				echo '.card-header{background-image: url("'.$cover.'");background-size: cover;}';
  			echo "</style>";
  		}
  ?>

</head>
<body class="bg-secondary">
	<div>
		
    <!-- HEADER -->
		<?php 
		$profil_class = "active";
		require_once('header.php'); ?>


		<!-- Contenu principale -->
		<section class="container text-center mt-5 text-white principale">
			<?php 
					if ($exist_profil == NULL) 
					{
						echo '<div class="card text-white bg-danger mb-3 ">';
						echo '<h4>Ce profil est désactivé</h4>';
						echo '</div>';
					}
					
					if($blocked != NULL)
					{
						$prive = 1;
					}
			?>
			
			<div class="card text-center bg-dark">
				<div class="card-header">
					<?php if($connected == 0 && $exist_profil != NULL && $prive != 1 && !empty($_SESSION))
					{
						if($followed == 1)
						{
							echo' <a href="desabonner.php?idprofil='.$_GET['idprofil'].'"><button class="btn btn-outline-light float-right">Se désabonner</button></a>';
						}
						else
						{
							echo' <a href="abonner.php?idprofil='.$_GET['idprofil'].'"><button class="btn btn-outline-light float-right">Suivre</button></a>';
						}
						
						if($block == 0)
						{
							echo'<a href="bloquer.php?idprofil='.$_GET['idprofil'].'"><button class="btn btn-outline-light float-left">Bloquer</button></a>';
						}
						else
						{
							echo'<a href="debloquer.php?idprofil='.$_GET['idprofil'].'"><button class="btn btn-outline-light float-left">Débloquer</button></a>';
						}
						
					}elseif ($connected == 0 && $exist_profil != NULL && !empty($_SESSION)) 
					{
						if($block == 0)
						{
							echo'<a href="bloquer.php?idprofil='.$_GET['idprofil'].'"><button class="btn btn-outline-light float-left">Bloquer</button></a>';
						}
						else
						{
							echo'<a href="debloquer.php?idprofil='.$_GET['idprofil'].'"><button class="btn btn-outline-light float-left">Débloquer</button></a>';
						}
					} ?>
					<br><br><h3>Profil de <i><?php echo utf8_encode($pseudo); ?></i></h3>

					<?php if(!empty($photo_profil))
					{
						echo '<img class"rounded border border-warning" width="200px" height="200px" alt="Image de profil" src="'.$photo_profil.'">';
					} ?><br><br>
					<div style="background-color: rgba(0, 0, 0, 0.7); margin-bottom: -30px; padding: 10px;" class="rounded">
					<?php if($connected == 1)
					{
						/*MODIFIER LE PROFIL*/
						echo '<a href="modification.php"><button class="btn btn-outline-light">Modifier mes informations</button></a>  ';
						/*MES ABONNEMENTS*/
						echo '<a href="abonnements.php?idprofil='.$_GET['idprofil'].'"><button class="btn btn-outline-light">Abonnements</button></a> ';
						/*MES BLOCAGES*/
						echo '<a href="blocages.php?idprofil='.$_GET['idprofil'].'"><button class="btn btn-outline-light">Blocages</button></a>';
					} ?>
				<a href=<?php echo '"historique.php?idprofil='.$idprofil.'"'; ?>><button class="btn btn-outline-light">Historique des parties</button></a>

				<?php echo '<a href="profil_choix_theme.php?idprofil='.$idprofil.'">';?><button class="btn btn-outline-light">Classement personnel</button></a></div><br>
				</div>
			<?php if (($connected != 1 && $prive == 0) || ($connected == 1)) 
			{?>
				
			<div class="card-body">
				
				<h3><i>"<?php if($titre == NULL){echo "Aucun titre";}else{ echo $titre;} ?>"</i></h3>
				<span class="badge badge-pill badge-info"><?php echo $diamants; ?> Diamants</span><br><br>
				<p><?php 
					echo ' <img style="width:20px;" class="rounded-circle" src="'.$pays['drapeauPays'].'" alt="Drapeau" > ';
					echo utf8_encode($pays['libellePays']);
					echo ' <img style="width:20px;" class="rounded-circle" src="'.$pays['drapeauPays'].'" alt="Drapeau" >'; ?></p>
				<p>
				<?php
					if($region != NULL)
					{
						if(!empty($drapeauregion)){
						 echo ' <img style="width:20px;" class="rounded-circle" src="'.$drapeauregion.'" alt="Drapeau" > ';}
						
						echo ''.utf8_encode($region).'';
						if(!empty($drapeauregion)){
						echo ' <img style="width:20px;" class="rounded-circle" src="'.$drapeauregion.'" alt="Drapeau" > ';}
					} ?>
				</p>
				<?php 
					if($ville != NULL)
					{
						echo '<p>Originaire de '.utf8_encode($ville).'</p>';
					}?>

		<?php if($bio != NULL)
		{?>
				<h3>Biographie :</h3>
		        	<?php echo utf8_encode($bio); ?><br><br>
		<?php }
		?>
			<div class="card-footer">
				<?php if($connected == 1)
				{?>
					<!-- Button trigger modal -->
						<button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#exampleModalScrollable">
						  Supprimer le compte
						</button>

						<!-- Modal -->
						<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
						  <div class="modal-dialog modal-dialog-scrollable" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						          <span aria-hidden="true">&times;</span>
						        </button>
						      </div>
						      <div class="modal-body text-danger">
						        <p>Êtes-vous sûr de vouloir supprimer le compte ?</p>
						      </div>
						      <div class="modal-footer">
						      	<?php echo '<a href="supprimer.php?idprofil='.$idprofil.'">';?><button class="btn btn-secondary">Oui je veux supprimer mon compte</button></a>
						        <button type="button" class="btn btn-secondary" data-dismiss="modal">Retour</button>
						      </div>
						    </div>
						  </div>
						</div>
						
		  		<?php } }elseif($prive == 1 && $connected != 1)
		  				 {
		  				 	echo '<div class="card-body">';
		  				 		echo "<h3>Ce profil est privé</h3>";
		  				 	echo '</div>';
		  				 }?>
			</div>
			</div>
		</section>
		<!-- Footer -->
    <?php require_once('footer.php'); 
   		mysqli_close($bdd);
   	?>
</body>
</html>