<!-- Tête de page  dans nav fixed-top-->
<?php 
		$bdd = mysqli_connect("185.157.246.42:3306", "quizup", "Ex6v1z6~", "quizup");
	
		if(isset($_SESSION['idprofil']) && !empty($_SESSION['idprofil']))
		{

			/*Je vérifie si la dernière activité de l'utilisateur a eu lieu aujourd'hui*/
			$last_today = mysqli_query($bdd, 'SELECT * FROM `usersession` WHERE CAST(lastActivity AS DATE) = CURRENT_DATE AND usersession.idProfil = '.$_SESSION['idprofil'].';');
			if($last_today != NULL){$last_today = mysqli_fetch_assoc($last_today);}

			/*Sinon, je vérifie si elle a eu lieu le jour précédent, si OUI j'incrémente le nombre de jours consécutif*/
			$last_yesterday = mysqli_query($bdd, 'SELECT * FROM `usersession` WHERE usersession.idProfil = '.$_SESSION['idprofil'].' AND CURRENT_DATE = (SELECT CAST(DATE_ADD(lastActivity, INTERVAL 1 DAY) AS DATE) FROM usersession WHERE usersession.idProfil = '.$_SESSION['idprofil'].');');
			if($last_yesterday != NULL){$last_yesterday = mysqli_fetch_assoc($last_yesterday);}

			//Récup du nb de jours consécutif
			$jours = mysqli_query($bdd, 'SELECT usersession.joursConsecutifs FROM `usersession` WHERE usersession.idProfil = '.$_SESSION['idprofil'].';');
			if($jours != NULL){$jours = mysqli_fetch_assoc($jours);}
			$jours = $jours['joursConsecutifs'];

			if($last_today != NULL)
			{
				//Dernière fois qu'il s'est connecté c'était aujourd'hui
				//Juste introduire le nouveau timestamp dans la bdd mais ne pas toucher au compteur de jours
				mysqli_query($bdd, 'UPDATE `usersession` SET `lastActivity`= CURRENT_TIMESTAMP,`joursConsecutifs`= '.$jours.' WHERE usersession.idProfil = '.$_SESSION['idprofil'].';');
			}
			else
			{
				if($last_yesterday != NULL)
				{
					//C'était hier
					//Introduire nouveau timestamp et incrémenter joursconséc
					$jours++;
					mysqli_query($bdd, 'UPDATE `usersession` SET `lastActivity`= CURRENT_TIMESTAMP,`joursConsecutifs`= '.$jours.' WHERE usersession.idProfil = '.$_SESSION['idprofil'].';');
				}
				else
				{
					//C'était il y a plus longtemps
					//Introduire le nouveau timestamp remettre jours à 0
					mysqli_query($bdd, 'UPDATE `usersession` SET `lastActivity`= CURRENT_TIMESTAMP,`joursConsecutifs`= 1 WHERE usersession.idProfil = '.$_SESSION['idprofil'].';');
					$jours = 1;
				}
			}
			$_SESSION['jours'] = $jours;
		}
	
	if(!isset($index))
	{
		$index = "";
	}
	if(!isset($theme_class))
	{
		$theme_class = "";
	}
	if(!isset($categorie_class))
	{
		$categorie_class = "";
	}
	if(!isset($classement_class))
	{
		$classement_class = "";
	}
	if(!isset($profil_class))
	{
		$profil_class = "";
	}
	if(!isset($tchat_class))
	{
		$tchat_class = "";
	}
	if(!isset($connexion_class))
	{
		$connexion_class = "";
	}
	if(!isset($inscription_class))
	{
		$inscription_class = "";
	}
?>
		<header>
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
				<a class="navbar-brand" href="index.php">
					<img src="images/logo-header.png" style="max-width: 45px;">QuizUp</a>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    			<span class="navbar-toggler-icon"></span>
  				</button>

  				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item <?php echo $index ?>">
							<a class="nav-link" href="index.php">Accueil</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php echo $theme_class ?>" href="themes.php">Thèmes</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php echo $categorie_class ?>" href="categorie.php">Catégorie</a>
						</li>
						<li class="nav-item">
							<a class="nav-link <?php echo $classement_class ?>" href="classement.php">Classement</a>
						</li>
					</ul>
					<ul class="navbar-nav ">
						<form class="form-inline" method="GET" action="recherche.php">
							<input class="form-control mr-sm-2" type="search" placeholder="Rechercher un profil" aria-label="Search" name="pseudo">
							<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
						</form>
						<li class="nav-item">
							<?php 
								if(!empty($_SESSION['idprofil']))
								{
									$message = mysqli_query($bdd, 'SELECT COUNT(idChatMsg) AS Msg FROM chat_msg WHERE idProfil_recepteur = '.$_SESSION['idprofil'].' AND lu = 0;');
									$message = mysqli_fetch_array($message, MYSQLI_ASSOC);
									echo '	<li>
												<a class="nav-link '.$profil_class.'" href="profil.php?idprofil='.$_SESSION['idprofil'].'">Mon profil</a>
											</li>';
									if($message['Msg'] != 0){
										echo '<li><a class="nav-link '.$tchat_class.'" href="tchat.php">Tchat <span class="badge badge-success">'.$message['Msg'].'</span></a></li>';
									}else{
										echo '<li><a class="nav-link '.$tchat_class.'" href="tchat.php">Tchat</a></li>';
									}
									echo '	<li class="nav-item">
												<a class="nav-link" href="deconnexion.php">Deconnexion</a>
											</li>';
								}
								else
								{
									echo '	<li>
												<a class="nav-link '.$connexion_class.'" href="connexion.php">Connexion</a>
											</li>
											<li class="nav-item">
												<a class="nav-link '.$inscription_class.'" href="inscription.php">Inscription</a>
											</li>';
								}

						 	?>
					</ul>
				</div>
			</nav>
		</header>