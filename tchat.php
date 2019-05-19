<?php 
	session_start();
?>

<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>
    <?php require_once('baseDeDonnee.php'); ?>
<head>
	<title><?php echo $nomSite; ?> - Tchat</title>
	<meta charset="utf-8">
  <?php require_once('styles.php'); ?>
</head>

<?php 
	$Pseudo = $_SESSION['nomprofil'];
	$idPseudo = $_SESSION['idprofil'];

	if($idPseudo == NULL){
		header('Location: 404.php');
	}else{
		$recherche_on = 0;
	}

	if(isset($_POST['text-message']) && !empty($_POST['text-message'])){
		$message = $_POST['text-message'];
		$recepteur = $_POST['recepteur'];
		$resultat = mysqli_query($bdd,'INSERT INTO chat_msg(idChatMsg,timestampMsg,contenu,lu,idProfil_recepteur,idProfil_emetteur) VALUES(NULL, CURRENT_TIMESTAMP(), "'.$message.'", 0, '.$recepteur.', '.$idPseudo.');');
	}

	if(isset($_POST['text-message-new']) && !empty($_POST['text-message-new'])){
		$message = $_POST['text-message-new'];
		$recepteur = $_POST['id-new-message'];
		$resultat = mysqli_query($bdd,'INSERT INTO chat_msg(idChatMsg,timestampMsg,contenu,lu,idProfil_recepteur,idProfil_emetteur) VALUES(NULL, CURRENT_TIMESTAMP(), "'.$message.'", 0, '.$recepteur.', '.$idPseudo.');');
	}

	// Vérifier si il a fait une recherche et regarder si ça corresponds à l'id d'une personne dans le tchat déjà utilisé sinon afficher la page de tchat avec la personne à qui on veut parler 
	if(isset($_POST['membres-list-choice']) && !empty($_POST['membres-list-choice'])){
		$recherche_membre = $_POST['membres-list-choice']; // Récupère le nom de la personne entré dans la recherche
		$requete_recherche = mysqli_query($bdd, 'SELECT idProfil FROM profil where nomProfil ="'.$recherche_membre.'";');
		$requete_ok =  mysqli_fetch_array($requete_recherche, MYSQLI_ASSOC); // Recupère l'id du joueur entré dans la recherche
		$recherche_on = 1;
	}
?>

<body class="bg-secondary">
	<div>
		
    <!-- HEADER -->
    <?php require_once('header.php'); ?>

		<!-- Contenu principale -->
		<section class="container text-center mt-5 text-white principale">
			

			<div class="card text-center bg-dark">
				<div class="card-header">
					<h2>Tchat</h2>
				</div>

				<div class="card-body">

					<div class="row">

						<div class="col-4">

							<div class="row">
								<div class="col-12">

									<form action="tchat.php" method="POST"> <!-- Nouvelle discussion -->
										<div class="input-group mb-3" style="width: 100%">
											<input list="membres-list" id="membres-list-choice" name="membres-list-choice">
											<datalist id="membres-list">
												<?php 
									 				if($recherche_on == 1){
														echo '<option value="'.$recherche_membre.'">'.$recherche_membre.'</option>';
													}

													$listmembre = mysqli_query($bdd,'SELECT idProfil, nomProfil FROM profil ORDER BY nomProfil');

													foreach($listmembre as $value){
														if($Pseudo != $value['nomProfil']){
															echo '<option value="'.$value['nomProfil'].'">'.$value['nomProfil'].'</option>';
														}
													}
												?>
											</datalist>
											<div class="input-group-append">
												<input class="btn btn-primary" type="submit" value="Créez !">
											</div>
										</div>
									</form>

								</div>
							</div>

							<div class="card bg-secondary" style="max-height: 400px;">

								<div class="card-body">

									<div class="row">
										<div class="col-12">
											<div class="list-group" id="list-tab" role="tablist">

											<?php 
												
												$listetchat = mysqli_query($bdd,'SELECT DISTINCT idProfil, nomProfil FROM profil, chat_msg WHERE (profil.idProfil = chat_msg.idProfil_recepteur OR profil.idProfil = chat_msg.idProfil_emetteur) AND ('.$idPseudo.' = chat_msg.idProfil_recepteur OR '.$idPseudo.' = chat_msg.idProfil_emetteur) ORDER BY chat_msg.idChatMsg DESC');

												foreach($listetchat as $recherche){
													if($recherche_on == 1 && $recherche_membre == $recherche['nomProfil']){
														$recherche_on = 0;
													}
												}
								
												if($recherche_on == 1){
													echo '<a class="list-group-item list-group-item-action" id="list-'.$recherche_membre.'-list" data-toggle="list" href="#list-'.$recherche_membre.'" role="tab" aria-controls="'.$recherche_membre.'">'.$recherche_membre.'</a>';
												}

												foreach($listetchat as $donnees){
													if($donnees['nomProfil'] != $Pseudo){
														echo '<a class="list-group-item list-group-item-action" id="list-'.$donnees['nomProfil'].'-list" data-toggle="list" href="#list-'.$donnees['nomProfil'].'" role="tab" aria-controls="'.$donnees['nomProfil'].'">'.$donnees['nomProfil'].'';
														$compteur = mysqli_query($bdd,'SELECT COUNT(idChatMsg)-SUM(lu) as Nbr FROM `chat_msg` WHERE idProfil_recepteur = '.$idPseudo.' AND idProfil_emetteur = '.$donnees['idProfil'].';');
														$cpt = mysqli_fetch_array($compteur, MYSQLI_ASSOC);
														if($cpt['Nbr'] != 0){
															echo '   <span class="badge badge-pill badge-success">'.$cpt['Nbr'].'</span>';
														}
														echo '</a>';
													}
												}

											?>

											</div>
										</div>
									</div>

								</div>
							</div>
						</div>
						
						<div class="col-8">

							<div class="card bg-secondary">

								<div id="FixScrollBottom" class="card-body tab-content text-dark" style="max-height: 415px; min-height: 415px; overflow: auto;">
											
									<?php if($recherche_on == 1){ ?>

											<div class="tab-pane fade" id="list-<?php echo $recherche_membre; ?>" role="tabpanel" aria-labelledby="list-<?php echo $recherche_membre; ?>-list" style="padding-bottom: 40px;">

												<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 80px;">
													<h2 class="text-center">Nouvelle discussion avec <?php echo $recherche_membre; ?></h2>
												</div>

												<div style="position: absolute; bottom: 0; width: 90%;;">
													<form action="tchat.php" method="POST">
														<div class="input-group mb-3">
															<input type="text" class="form-control" placeholder="Votre message ..." aria-label="Votre message ..." aria-describedby="button-envoyez" name="text-message-new">
															<input type="hidden" name="recepteur" value="<?php echo $recherche_membre; ?>">
															<div class="input-group-append">
																<input class="btn btn-primary" type="submit" value="Envoyez !">
															</div>
															<input type="hidden" name="id-new-message" value="<?php echo $requete_ok['idProfil']; ?>">
														</div>
													</form>
												</div>

											</div>

									<?php	} ?>

									<?php 
										$listtchat = mysqli_query($bdd,'SELECT DISTINCT idProfil, nomProfil, idProfil_recepteur FROM profil, chat_msg WHERE profil.idProfil = chat_msg.idProfil_recepteur');
												
										foreach($listtchat as $tchat_donnees){
										$tchat_do = $tchat_donnees['idProfil_recepteur'];
									?>

										<div class="tab-pane fade" id="list-<?php echo $tchat_donnees['nomProfil']; ?>" role="tabpanel" aria-labelledby="list-<?php echo $tchat_donnees['nomProfil']; ?>-list" style="padding-bottom: 40px;">
											
											<?php 
											$messages = mysqli_query($bdd,'SELECT DATE_FORMAT(timestampMsg, "%d/%m/%y > %Hh%i") as dateMsg, idProfil_recepteur, idProfil_emetteur, contenu FROM chat_msg WHERE ('.$idPseudo.' = chat_msg.idProfil_emetteur AND '.$tchat_do.' = chat_msg.idProfil_recepteur) OR ('.$tchat_do.' = chat_msg.idProfil_emetteur AND '.$idPseudo.' = chat_msg.idProfil_recepteur)');

											foreach($messages as $donnees){ 
											$image = mysqli_query($bdd,'SELECT idProfil, photoProfil FROM profil WHERE idProfil = '.$donnees['idProfil_emetteur'].'');
											$imagess = mysqli_fetch_array($image, MYSQLI_ASSOC);

											$MsgLu = mysqli_query($bdd,'UPDATE chat_msg SET lu = 1 WHERE lu = 0 AND idProfil_recepteur = '.$idPseudo.' AND idProfil_emetteur = '.$imagess['idProfil'].';');
											?>
											
												<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 80px;">

													<?php if($donnees['idProfil_emetteur'] == $idPseudo){ ?>
														<div class="toast" style="position: absolute; top: 0; right: 0;" data-autohide="false"> <!--DROITE-->
															<div class="toast-header">
																<img src="<?php echo $imagess['photoProfil']; ?>" class="rounded mr-2" alt="Logo" height="20px">
																<strong class="mr-auto"><?php echo $Pseudo; ?></strong>
																<small><?php echo $donnees['dateMsg']; ?></small>
															</div>
															<div class="toast-body">
																	<?php echo utf8_encode($donnees['contenu']); ?>
															</div>
														</div>
													<?php }else{ ?>
														<div class="toast" style="position: absolute; top: 0; left: 0;" data-autohide="false"> <!--GAUCHE-->
															<div class="toast-header">
																<img src="<?php echo $imagess['photoProfil']; ?>" class="rounded mr-2" alt="Logo" height="20px">
																<strong class="mr-auto"><?php echo $tchat_donnees['nomProfil']; ?></strong>
																<small><?php echo $donnees['dateMsg']; ?></small>
															</div>
															<div class="toast-body">
																	<?php echo utf8_encode($donnees['contenu']); ?>
															</div>
														</div>
													<?php } 
													
													?>

												</div>
											<?php } ?>
										
											<div style="position: absolute; bottom: 0; width: 90%;;">
												<form action="tchat.php" method="POST">
													<div class="input-group mb-3">
														<input type="text" class="form-control" placeholder="Votre message ..." aria-label="Votre message ..." aria-describedby="button-envoyez" name="text-message">
														<input type="hidden" name="recepteur" value="<?php echo $tchat_donnees['idProfil']; ?>">
														<div class="input-group-append">
															<input class="btn btn-primary" type="submit" value="Envoyez !">
														</div>
													</div>
												</form>
											</div>

										</div>

									<?php } ?>

								</div>

							</div>

						</div>

					</div>

				</div>

			</div>

		</section>
		<!-- Footer -->
		<?php 
			require_once('footer.php'); 
			mysqli_close($bdd);
		?>

<script>
	//Affichage des Toasts
	$('.toast').toast('show');

</script>

</body>
</html>

