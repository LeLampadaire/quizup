<?php session_start(); 
	require_once('baseDeDonnee.php'); 
	/*CREATION DU CLASSEMENT GÉNÉRAL*/
	$gen1 = mysqli_query($bdd, 'SELECT profil.idProfil, profil.nomProfil, SUM(repondre.points) AS nb_point FROM repondre, profil WHERE repondre.idProfil = profil.idProfil GROUP BY profil.idProfil, profil.nomProfil;');
	$i=0;
	$gen1_tab = array();
	while ($recup = mysqli_fetch_array($gen1, MYSQLI_ASSOC))
	{
		$gen1_tab[$i] = $recup;	
		$nb_partie = mysqli_query($bdd, 'SELECT count(idPartie) nb_part FROM participer WHERE idProfil = '.$gen1_tab[$i]['idProfil'].';');
		$nb_partie = mysqli_fetch_array($nb_partie, MYSQLI_ASSOC);
		$nb_partie = $nb_partie['nb_part'];
		$gen1_tab[$i]['nb_part'] = $nb_partie;
		$i++;
	}

	/*CREATION DU CLASSEMENT DU MOIS PRÉCÉDENT*/
	$gen2 = mysqli_query($bdd, 'SELECT profil.idProfil ,profil.nomProfil, SUM(repondre.points) AS nb_point FROM profil, participer, partie, repondre WHERE profil.idProfil = participer.idProfil AND participer.idPartie = partie.idPartie AND partie.idPartie = repondre.idPartie AND EXTRACT(MONTH FROM partie.timestampPartie) = EXTRACT(MONTH FROM CURRENT_DATE)-1 AND EXTRACT(YEAR FROM partie.timestampPartie) = EXTRACT(YEAR FROM CURRENT_DATE) GROUP BY participer.idProfil;');
	$i=0;
	$gen2_tab = array();
	while ($recup = mysqli_fetch_array($gen2, MYSQLI_ASSOC))
	{
		$gen2_tab[$i] = $recup;
		$nb_partie = mysqli_query($bdd, 'SELECT count(participer.idPartie) nb_part FROM participer, partie WHERE partie.idPartie = participer.idPartie AND participer.idProfil = '.$gen2_tab[$i]['idProfil'].' AND EXTRACT(MONTH FROM partie.timestampPartie) = EXTRACT(MONTH FROM CURRENT_DATE)-1 AND EXTRACT(YEAR FROM partie.timestampPartie) = EXTRACT(YEAR FROM CURRENT_DATE);');//J'ETAIS LA//CALCULER LE NOMBRE DE PARTIE DANS LE MOIS PRECEDENT
		$nb_partie = mysqli_fetch_array($nb_partie, MYSQLI_ASSOC);
		$nb_partie = $nb_partie['nb_part'];
		$gen2_tab[$i]['nb_part'] = $nb_partie;
		$i++;
	}

	/*CREATION DU CLASSEMENT DU MOIS COURANT*/
	$gen3 = mysqli_query($bdd, 'SELECT profil.idProfil ,profil.nomProfil, SUM(repondre.points) AS nb_point FROM profil, participer, partie, repondre WHERE profil.idProfil = participer.idProfil AND participer.idPartie = partie.idPartie AND partie.idPartie = repondre.idPartie AND EXTRACT(MONTH FROM partie.timestampPartie) = EXTRACT(MONTH FROM CURRENT_DATE) AND EXTRACT(YEAR FROM partie.timestampPartie) = EXTRACT(YEAR FROM CURRENT_DATE) GROUP BY participer.idProfil;');
	$i=0;
	$gen3_tab = array();
	while ($recup = mysqli_fetch_array($gen3, MYSQLI_ASSOC))
	{
		$gen3_tab[$i] = $recup;
		$nb_partie = mysqli_query($bdd, 'SELECT count(participer.idPartie) nb_part FROM participer, partie WHERE partie.idPartie = participer.idPartie AND participer.idProfil = '.$gen3_tab[$i]['idProfil'].' AND EXTRACT(MONTH FROM partie.timestampPartie) = EXTRACT(MONTH FROM CURRENT_DATE) AND EXTRACT(YEAR FROM partie.timestampPartie) = EXTRACT(YEAR FROM CURRENT_DATE);');//J'ETAIS LA//CALCULER LE NOMBRE DE PARTIE DANS LE MOIS PRECEDENT
		$nb_partie = mysqli_fetch_array($nb_partie, MYSQLI_ASSOC);
		$nb_partie = $nb_partie['nb_part'];
		$gen3_tab[$i]['nb_part'] = $nb_partie;
		$i++;
	}
	$date = (int)date("m");
	$annee = (int)date("Y");
	$mois = array('1' => "Janvier" ,'2' => "Février",'3' => "Mars",'4' => "Avril",'5' => "Mai" ,'6' => "Juin",'7' => "Juillet" ,'8' => "Août",'9' => "Septembre",'10' => "Octobre",'11' => "Novembre",'12' => "Décembre");
?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>

<head>
	<title><?php echo $nomSite; ?> - Classements</title>
	<meta charset="utf-8">
  <?php require_once('styles.php'); ?>
  <style type="text/css">
  	.classement, .classement:hover{color:white;}
  </style>
</head>
<body class="bg-secondary">
	<!-- HEADER -->
    <?php require_once('header.php'); ?>

    <!-- Contenu principale -->
    <section class="container text-center mt-5 text-white principale">
        <div class="card text-center bg-dark">
			<div class="card-header">
				<h2>Classement de l'ensemble des joueurs :</h2>
			</div>
			<div class="card-body">
					<nav>
					  <div class="nav nav-tabs" id="nav-tab" role="tablist">
					  	<a  class="classement nav-item nav-link" id="nav-gen-1" data-toggle="tab" href="#nav-gen1" role="tab" aria-controls="nav-home" aria-selected="false">Général</a>
					    <a class="classement nav-item nav-link" id="nav-gen-2" data-toggle="tab" href="#nav-gen2" role="tab" aria-controls="nav-home" aria-selected="false">Général - <?php echo $mois[$date-1]; echo' '.$annee.''; ?></a>
					    <a class="classement nav-item nav-link" id="nav-gen-3" data-toggle="tab" href="#nav-gen3" role="tab" aria-controls="nav-profile" aria-selected="false">Général - <?php echo $mois[$date]; echo' '.$annee.'' ?></a>
					  </div>
					</nav>
					<div class="tab-content" id="nav-tabContent">
					  <div class="tab-pane fade show active" id="nav-gen1" role="tabpanel" aria-labelledby="nav-home-tab">
					  	<table class="table table-striped table-dark">
							  <thead>
							    <tr>
							      <th scope="col">#</th>
							      <th scope="col">Pseudo</th>
							      <th scope="col">Nombre de parties</th>
							      <th scope="col">Total des points</th>
							    </tr>
							  </thead>
							  <tbody>
									<?php 
									$i=0;
										while($i<=10) 
										{
											if(isset($gen1_tab[$i]) && !empty($gen1_tab[$i]))
											{
												echo "<tr>";
													echo '<td>'.($i+1).'</td>';
													echo '<td>'.$gen1_tab[$i]['nomProfil'].'</td>'; 
													echo '<td>'.$gen1_tab[$i]['nb_part'].'</td>'; 
													echo '<td>'.$gen1_tab[$i]['nb_point'].'</td>';
												echo "</tr>";
											}	
											$i++;
										}
									?>
							  </tbody>
						</table>
					  </div>
					  <div class="tab-pane fade" id="nav-gen2" role="tabpanel" aria-labelledby="nav-home-tab">
					  	<table class="table table-striped table-dark">
							  <thead>
							    <tr>
							      <th scope="col">#</th>
							      <th scope="col">Pseudo</th>
							      <th scope="col">Nombre de parties</th>
							      <th scope="col">Total des points</th>
							    </tr>
							  </thead>
							  <tbody>
									<?php 
									$i=0;
										while($i<=10) 
										{
											if(isset($gen2_tab[$i]) && !empty($gen2_tab[$i]))
											{
												echo "<tr>";
													echo '<td>'.($i+1).'</td>';
													echo '<td>'.$gen2_tab[$i]['nomProfil'].'</td>'; 
													echo '<td>'.$gen2_tab[$i]['nb_part'].'</td>'; 
													echo '<td>'.$gen2_tab[$i]['nb_point'].'</td>';
												echo "</tr>";
											}	
											$i++;
										}
									?>
							  </tbody>
						</table>
					  </div>
					  <div class="tab-pane fade" id="nav-gen3" role="tabpanel" aria-labelledby="nav-home-tab">
					  	<table class="table table-striped table-dark">
							  <thead>
							    <tr>
							      <th scope="col">#</th>
							      <th scope="col">Pseudo</th>
							      <th scope="col">Nombre de parties</th>
							      <th scope="col">Total des points</th>
							    </tr>
							  </thead>
							  <tbody>
									<?php 
									$i=0;
										while($i<=10) 
										{
											if(isset($gen3_tab[$i]) && !empty($gen3_tab[$i]))
											{
												echo "<tr>";
													echo '<td>'.($i+1).'</td>';
													echo '<td>'.$gen3_tab[$i]['nomProfil'].'</td>'; 
													echo '<td>'.$gen3_tab[$i]['nb_part'].'</td>'; 
													echo '<td>'.$gen3_tab[$i]['nb_point'].'</td>';
												echo "</tr>";
											}	
											$i++;
										}
									?>
							  </tbody>
						</table>
					  </div>
					</div>
			</div>
		</div>
    </section>

    <!-- Footer -->
    <?php require_once('footer.php'); ?>
</body>
</html>