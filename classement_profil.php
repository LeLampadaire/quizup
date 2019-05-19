<?php session_start(); 
	require_once('baseDeDonnee.php');
	$idprofil = $_GET['idprofil'];
	$user = mysqli_query($bdd, 'SELECT nomProfil, idPays FROM profil WHERE idProfil = '.$idprofil.';');
	$user = mysqli_fetch_array($user, MYSQLI_ASSOC);
	$pays = mysqli_query($bdd, 'SELECT libellePays FROM pays WHERE idPays = '.(int)$user['idPays'].';');
	$pays = mysqli_fetch_array($pays, MYSQLI_ASSOC);
	$pays = $pays['libellePays'];
	$libelle_theme = mysqli_query($bdd, 'SELECT libelleTheme FROM theme WHERE idTheme = '.$_GET['idtheme'].';');
	$libelle_theme = mysqli_fetch_array($libelle_theme, MYSQLI_ASSOC);
	$libelle_theme = $libelle_theme['libelleTheme'];

	/**********************************CLASSEMENT GENERAL*******************************/

	$gen1 = mysqli_query($bdd, 'SELECT profil.idProfil ,profil.nomProfil, SUM(repondre.points) AS nb_point FROM profil, participer, partie, repondre, question WHERE profil.idProfil = participer.idProfil AND participer.idPartie = partie.idPartie AND partie.idPartie = repondre.idPartie AND repondre.idQuestion = question.idQuestion AND question.idTheme = '.$_GET['idtheme'].' GROUP BY profil.idProfil, profil.nomProfil');
	$i=0;
	$gen1_tab = array();
	while ($recup = mysqli_fetch_array($gen1, MYSQLI_ASSOC))
	{
		$gen1_tab[$i] = $recup;	
		$nb_partie = mysqli_query($bdd, 'SELECT idProfil, COUNT(participer.idPartie) AS nb_part FROM participer, partie WHERE participer.idPartie = partie.idPartie AND  idProfil = '.$gen1_tab[$i]['idProfil'].' AND participer.idPartie IN(SELECT integrer.idPartie FROM integrer, question WHERE integrer.idQuestion = question.idQuestion AND question.idTheme = '.$_GET['idtheme'].') GROUP BY idProfil');
		$nb_partie = mysqli_fetch_array($nb_partie, MYSQLI_ASSOC);
		$nb_partie = $nb_partie['nb_part'];
		$gen1_tab[$i]['nb_part'] = $nb_partie;
		$i++;
	}

	/**********************************CLASSEMENT GENERAL DU MOIS PASSÉ*******************************************/

	$gen2 = mysqli_query($bdd, 'SELECT profil.idProfil, profil.nomProfil, SUM(repondre.points) AS nb_point FROM profil, participer, partie, repondre, question WHERE profil.idProfil = participer.idProfil AND participer.idPartie = partie.idPartie AND partie.idPartie = repondre.idPartie AND repondre.idQuestion = question.idQuestion AND repondre.idProfil = repondre.idProfil AND question.idTheme = '.$_GET['idtheme'].' AND EXTRACT(MONTH FROM partie.timestampPartie) = (EXTRACT(MONTH FROM CURRENT_DATE)-1) AND EXTRACT(YEAR FROM partie.timestampPartie) = EXTRACT(YEAR FROM CURRENT_DATE) GROUP BY profil.idProfil, profil.nomProfil;');
	$i = 0;
	while($recup = mysqli_fetch_array($gen2, MYSQLI_ASSOC)) 
	{
		$gen2_tab[$i] = $recup;	
		$nb_partie = mysqli_query($bdd, 'SELECT idProfil, COUNT(participer.idPartie) AS nb_part FROM participer, partie WHERE participer.idPartie = partie.idPartie AND EXTRACT(MONTH FROM partie.timestampPartie) = (EXTRACT(MONTH FROM CURRENT_DATE)-1) AND EXTRACT(YEAR FROM partie.timestampPartie) = EXTRACT(YEAR FROM CURRENT_DATE) AND  idProfil = '.$gen2_tab[$i]['idProfil'].' AND participer.idPartie IN(SELECT integrer.idPartie FROM integrer, question WHERE integrer.idQuestion = question.idQuestion AND question.idTheme = '.$_GET['idtheme'].') GROUP BY idProfil');
		$nb_partie = mysqli_fetch_array($nb_partie, MYSQLI_ASSOC);
		$nb_partie = $nb_partie['nb_part'];
		$gen2_tab[$i]['nb_part'] = $nb_partie;
		$i++;
	}
	/**********************************CLASSEMENT GENERAL DU MOIS COURANT*****************************************/

	$gen3 = mysqli_query($bdd, 'SELECT profil.idProfil, profil.nomProfil, SUM(repondre.points) AS nb_point FROM profil, participer, partie, repondre, question WHERE profil.idProfil = participer.idProfil AND participer.idPartie = partie.idPartie AND partie.idPartie = repondre.idPartie AND repondre.idQuestion = question.idQuestion AND repondre.idProfil = repondre.idProfil AND question.idTheme = '.$_GET['idtheme'].' AND EXTRACT(MONTH FROM partie.timestampPartie) = (EXTRACT(MONTH FROM CURRENT_DATE)) AND EXTRACT(YEAR FROM partie.timestampPartie) = EXTRACT(YEAR FROM CURRENT_DATE) GROUP BY profil.idProfil, profil.nomProfil;');
	$i = 0;
	while($recup = mysqli_fetch_array($gen3, MYSQLI_ASSOC)) 
	{
		$gen3_tab[$i] = $recup;	
		$nb_partie = mysqli_query($bdd, 'SELECT idProfil, COUNT(participer.idPartie) AS nb_part FROM participer, partie WHERE participer.idPartie = partie.idPartie AND EXTRACT(MONTH FROM partie.timestampPartie) = (EXTRACT(MONTH FROM CURRENT_DATE)) AND EXTRACT(YEAR FROM partie.timestampPartie) = EXTRACT(YEAR FROM CURRENT_DATE) AND  idProfil = '.$gen3_tab[$i]['idProfil'].' AND participer.idPartie IN(SELECT integrer.idPartie FROM integrer, question WHERE integrer.idQuestion = question.idQuestion AND question.idTheme = '.$_GET['idtheme'].') GROUP BY idProfil');
		$nb_partie = mysqli_fetch_array($nb_partie, MYSQLI_ASSOC);
		$nb_partie = $nb_partie['nb_part'];
		$gen3_tab[$i]['nb_part'] = $nb_partie;
		$i++;
	}
	/**********************************CLASSEMENT PAR PAYS********************************************************/

	$gen4 = mysqli_query($bdd, 'SELECT profil.idProfil ,profil.nomProfil, SUM(repondre.points) AS nb_point FROM profil, participer, partie, repondre, question WHERE profil.idProfil = participer.idProfil AND participer.idPartie = partie.idPartie AND partie.idPartie = repondre.idPartie AND repondre.idQuestion = question.idQuestion AND question.idTheme = '.$_GET['idtheme'].' AND profil.idPays = '.(int)$user['idPays'].' GROUP BY profil.idProfil, profil.nomProfil');
	$i=0;
	$gen4_tab = array();
	while ($recup = mysqli_fetch_array($gen4, MYSQLI_ASSOC))
	{
		$gen4_tab[$i] = $recup;	
		$nb_partie = mysqli_query($bdd, 'SELECT idProfil, COUNT(participer.idPartie) AS nb_part FROM participer, partie WHERE participer.idPartie = partie.idPartie AND  idProfil = '.$gen1_tab[$i]['idProfil'].' AND participer.idPartie IN(SELECT integrer.idPartie FROM integrer, question WHERE integrer.idQuestion = question.idQuestion AND question.idTheme = '.$_GET['idtheme'].') GROUP BY idProfil');
		$nb_partie = mysqli_fetch_array($nb_partie, MYSQLI_ASSOC);
		$nb_partie = $nb_partie['nb_part'];
		$gen4_tab[$i]['nb_part'] = $nb_partie;
		$i++;
	}
	/**********************************CLASSEMENT PAR PAYS DU MOIS PASSÉ******************************************/

	$gen5 = mysqli_query($bdd, 'SELECT profil.idProfil, profil.nomProfil, SUM(repondre.points) AS nb_point FROM profil, participer, partie, repondre, question WHERE profil.idProfil = participer.idProfil AND participer.idPartie = partie.idPartie AND partie.idPartie = repondre.idPartie AND repondre.idQuestion = question.idQuestion AND repondre.idProfil = repondre.idProfil AND question.idTheme = '.$_GET['idtheme'].' AND EXTRACT(MONTH FROM partie.timestampPartie) = (EXTRACT(MONTH FROM CURRENT_DATE)-1) AND EXTRACT(YEAR FROM partie.timestampPartie) = EXTRACT(YEAR FROM CURRENT_DATE) AND profil.idPays = '.(int)$user['idPays'].' GROUP BY profil.idProfil, profil.nomProfil;');
	$i = 0;
	while($recup = mysqli_fetch_array($gen5, MYSQLI_ASSOC)) 
	{
		$gen5_tab[$i] = $recup;	
		$nb_partie = mysqli_query($bdd, 'SELECT idProfil, COUNT(participer.idPartie) AS nb_part FROM participer, partie WHERE participer.idPartie = partie.idPartie AND EXTRACT(MONTH FROM partie.timestampPartie) = (EXTRACT(MONTH FROM CURRENT_DATE)-1) AND EXTRACT(YEAR FROM partie.timestampPartie) = EXTRACT(YEAR FROM CURRENT_DATE) AND  idProfil = '.$gen5_tab[$i]['idProfil'].' AND participer.idPartie IN(SELECT integrer.idPartie FROM integrer, question WHERE integrer.idQuestion = question.idQuestion AND question.idTheme = '.$_GET['idtheme'].') GROUP BY idProfil');
		$nb_partie = mysqli_fetch_array($nb_partie, MYSQLI_ASSOC);
		$nb_partie = $nb_partie['nb_part'];
		$gen5_tab[$i]['nb_part'] = $nb_partie;
		$i++;
	}
	/**********************************CLASSEMENT PAR PAYS DU MOIS COURANT****************************************/

	$gen6 = mysqli_query($bdd, 'SELECT profil.idProfil, profil.nomProfil, SUM(repondre.points) AS nb_point FROM profil, participer, partie, repondre, question WHERE profil.idProfil = participer.idProfil AND participer.idPartie = partie.idPartie AND partie.idPartie = repondre.idPartie AND repondre.idQuestion = question.idQuestion AND repondre.idProfil = repondre.idProfil AND question.idTheme = '.$_GET['idtheme'].' AND EXTRACT(MONTH FROM partie.timestampPartie) = (EXTRACT(MONTH FROM CURRENT_DATE)) AND EXTRACT(YEAR FROM partie.timestampPartie) = EXTRACT(YEAR FROM CURRENT_DATE) AND profil.idPays = '.(int)$user['idPays'].' GROUP BY profil.idProfil, profil.nomProfil;');
	$i = 0;
	while($recup = mysqli_fetch_array($gen6, MYSQLI_ASSOC)) 
	{
		$gen6_tab[$i] = $recup;	
		$nb_partie = mysqli_query($bdd, 'SELECT idProfil, COUNT(participer.idPartie) AS nb_part FROM participer, partie WHERE participer.idPartie = partie.idPartie AND EXTRACT(MONTH FROM partie.timestampPartie) = (EXTRACT(MONTH FROM CURRENT_DATE)) AND EXTRACT(YEAR FROM partie.timestampPartie) = EXTRACT(YEAR FROM CURRENT_DATE) AND  idProfil = '.$gen6_tab[$i]['idProfil'].' AND participer.idPartie IN(SELECT integrer.idPartie FROM integrer, question WHERE integrer.idQuestion = question.idQuestion AND question.idTheme = '.$_GET['idtheme'].') GROUP BY idProfil');
		$nb_partie = mysqli_fetch_array($nb_partie, MYSQLI_ASSOC);
		$nb_partie = $nb_partie['nb_part'];
		$gen6_tab[$i]['nb_part'] = $nb_partie;
		$i++;
	}
	/*************************************************************************************************************/
	$date = (int)date("m");
	$annee = (int)date("Y");
	$mois = array('1' => "Janvier" ,'2' => "Février",'3' => "Mars",'4' => "Avril",'5' => "Mai" ,'6' => "Juin",'7' => "Juillet" ,'8' => "Août",'9' => "Septembre",'10' => "Octobre",'11' => "Novembre",'12' => "Décembre");
?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>

<head>
	<title><?php echo $nomSite; ?> - Classement de <?php echo $user['nomProfil']; ?></title>
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
				<h2>Classement de <i><?php echo $user['nomProfil']; ?></i> pour le thème "<i><?php echo $libelle_theme; ?></i>"</h2>
			</div>
			<div class="card-body">
				<nav>
					  <div class="nav nav-tabs" id="nav-tab" role="tablist">
					  	<a  class="classement nav-item nav-link" id="nav-gen-1" data-toggle="tab" href="#nav-gen1" role="tab" aria-controls="nav-home" aria-selected="false">Général</a>
					    <a class="classement nav-item nav-link" id="nav-gen-2" data-toggle="tab" href="#nav-gen2" role="tab" aria-controls="nav-home" aria-selected="false">Général - <?php echo $mois[$date-1]; echo' '.$annee.''; ?></a>
					    <a class="classement nav-item nav-link" id="nav-gen-3" data-toggle="tab" href="#nav-gen3" role="tab" aria-controls="nav-profile" aria-selected="false">Général - <?php echo $mois[$date]; echo' '.$annee.''; ?></a>
					    <a class="classement nav-item nav-link" id="nav-pays-1" data-toggle="tab" href="#nav-pays1" role="tab" aria-controls="nav-contact" aria-selected="false"><?php echo utf8_encode($pays); ?></a>
					    <a class="classement nav-item nav-link" id="nav-pays-2" data-toggle="tab" href="#nav-pays2" role="tab" aria-controls="nav-contact" aria-selected="false"><?php echo utf8_encode($pays); ?> - <?php echo $mois[$date-1]; echo' '.$annee.''; ?></a>
					    <a class="classement nav-item nav-link" id="nav-pays-3" data-toggle="tab" href="#nav-pays3" role="tab" aria-controls="nav-contact" aria-selected="false"><?php echo utf8_encode($pays); ?> - <?php echo $mois[$date]; echo' '.$annee.''; ?></a>
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
									$trouve = 0;
										while($i<=10) 
										{
											if(isset($gen1_tab[$i]) && !empty($gen1_tab[$i]))
											{
												if($gen1_tab[$i]['idProfil'] == $_GET['idprofil'])
												{
													$trouve_class = "bg-warning";
													$trouve = 1;
												}
												else
												{
													$trouve_class = "";
												}
												echo '<tr class="'.$trouve_class.'">';
													echo '<td>'.($i+1).'</td>';
													echo '<td>'.$gen1_tab[$i]['nomProfil'].'</td>'; 
													echo '<td>'.$gen1_tab[$i]['nb_part'].'</td>'; 
													echo '<td>'.$gen1_tab[$i]['nb_point'].'</td>';
												echo "</tr>";
											}	
											$i++;
										}
										
										if ($trouve == 0 && isset($gen1_tab) && !empty($gen1_tab)) 
										{
											echo "<tr>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "</tr>";
											$i = 0;
											foreach ($gen1_tab as $value) 
											{
												if($gen1_tab[$i]['idProfil'] == $_GET['idprofil'])
												{
													echo '<tr class="bg-warning">';
														echo '<td>'.($i+1).'</td>';
														echo '<td>'.$gen1_tab[$i]['nomProfil'].'</td>'; 
														echo '<td>'.$gen1_tab[$i]['nb_part'].'</td>'; 
														echo '<td>'.$gen1_tab[$i]['nb_point'].'</td>';
													echo "</tr>";
												}
												$i++;
											} 
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
									$trouve = 0;
										while($i<=10) 
										{
											if(isset($gen2_tab[$i]) && !empty($gen2_tab[$i]))
											{
												if($gen2_tab[$i]['idProfil'] == $_GET['idprofil'])
												{
													$trouve_class = "bg-warning";
													$trouve = 1;
												}
												else
												{
													$trouve_class = "";
												}
												echo '<tr class="'.$trouve_class.'">';
													echo '<td>'.($i+1).'</td>';
													echo '<td>'.$gen2_tab[$i]['nomProfil'].'</td>'; 
													echo '<td>'.$gen2_tab[$i]['nb_part'].'</td>'; 
													echo '<td>'.$gen2_tab[$i]['nb_point'].'</td>';
												echo "</tr>";
											}	
											$i++;
										}

										if ($trouve == 0 && isset($gen2_tab) && !empty($gen2_tab)) 
										{
											echo "<tr>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "</tr>";
											$i = 0;
											foreach ($gen2_tab as $value) 
											{
												if($gen2_tab[$i]['idProfil'] == $_GET['idprofil'])
												{
													echo '<tr class="bg-warning">';
														echo '<td>'.($i+1).'</td>';
														echo '<td>'.$gen2_tab[$i]['nomProfil'].'</td>'; 
														echo '<td>'.$gen2_tab[$i]['nb_part'].'</td>'; 
														echo '<td>'.$gen2_tab[$i]['nb_point'].'</td>';
													echo "</tr>";
												}
												$i++;
											} 
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
									$trouve = 0;
										while($i<=10) 
										{
											if(isset($gen3_tab[$i]) && !empty($gen3_tab[$i]))
											{
												if($gen3_tab[$i]['idProfil'] == $_GET['idprofil'])
												{
													$trouve_class = "bg-warning";
													$trouve = 1;
												}
												else
												{
													$trouve_class = "";
												}
												echo '<tr class="'.$trouve_class.'">';
													echo '<td>'.($i+1).'</td>';
													echo '<td>'.$gen3_tab[$i]['nomProfil'].'</td>'; 
													echo '<td>'.$gen3_tab[$i]['nb_part'].'</td>'; 
													echo '<td>'.$gen3_tab[$i]['nb_point'].'</td>';
												echo "</tr>";
											}	
											$i++;
										}
										if ($trouve == 0 && isset($gen3_tab) && !empty($gen3_tab)) 
										{
											echo "<tr>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "</tr>";
											$i = 0;
											foreach ($gen3_tab as $value) 
											{
												if($gen3_tab[$i]['idProfil'] == $_GET['idprofil'])
												{
													echo '<tr class="bg-warning">';
														echo '<td>'.($i+1).'</td>';
														echo '<td>'.$gen3_tab[$i]['nomProfil'].'</td>'; 
														echo '<td>'.$gen3_tab[$i]['nb_part'].'</td>'; 
														echo '<td>'.$gen3_tab[$i]['nb_point'].'</td>';
													echo "</tr>";
												}
												$i++;
											} 
										}
									?>
							  </tbody>
						</table>
					  </div>
					  <div class="tab-pane fade" id="nav-pays1" role="tabpanel" aria-labelledby="nav-pays1">
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
									$trouve = 0;
										while($i<=10) 
										{
											if(isset($gen4_tab[$i]) && !empty($gen4_tab[$i]))
											{
												if($gen4_tab[$i]['idProfil'] == $_GET['idprofil'])
												{
													$trouve_class = "bg-warning";
													$trouve = 1;
												}
												else
												{
													$trouve_class = "";
												}
												echo '<tr class="'.$trouve_class.'">';
													echo '<td>'.($i+1).'</td>';
													echo '<td>'.$gen4_tab[$i]['nomProfil'].'</td>'; 
													echo '<td>'.$gen4_tab[$i]['nb_part'].'</td>'; 
													echo '<td>'.$gen4_tab[$i]['nb_point'].'</td>';
												echo "</tr>";
											}	
											$i++;
										}
										if ($trouve == 0 && isset($gen4_tab) && !empty($gen4_tab)) 
										{
											echo "<tr>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "</tr>";
											$i = 0;
											foreach ($gen4_tab as $value) 
											{
												if($gen4_tab[$i]['idProfil'] == $_GET['idprofil'])
												{
													echo '<tr class="bg-warning">';
														echo '<td>'.($i+1).'</td>';
														echo '<td>'.$gen4_tab[$i]['nomProfil'].'</td>'; 
														echo '<td>'.$gen4_tab[$i]['nb_part'].'</td>'; 
														echo '<td>'.$gen4_tab[$i]['nb_point'].'</td>';
													echo "</tr>";
												}
												$i++;
											} 
										}
									?>
							  </tbody>
						</table>
					  </div>
					  <div class="tab-pane fade" id="nav-pays2" role="tabpanel" aria-labelledby="nav-pays2">
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
									$trouve = 0;
										while($i<=10) 
										{
											if(isset($gen5_tab[$i]) && !empty($gen5_tab[$i]))
											{
												if($gen5_tab[$i]['idProfil'] == $_GET['idprofil'])
												{
													$trouve_class = "bg-warning";
													$trouve = 1;
												}
												else
												{
													$trouve_class = "";
												}
												echo '<tr class="'.$trouve_class.'">';
													echo '<td>'.($i+1).'</td>';
													echo '<td>'.$gen5_tab[$i]['nomProfil'].'</td>'; 
													echo '<td>'.$gen5_tab[$i]['nb_part'].'</td>'; 
													echo '<td>'.$gen5_tab[$i]['nb_point'].'</td>';
												echo "</tr>";
											}	
											$i++;
										}
										if ($trouve == 0 && isset($gen5_tab) && !empty($gen5_tab)) 
										{
											echo "<tr>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "</tr>";
											$i = 0;
											foreach ($gen5_tab as $value) 
											{
												if($gen5_tab[$i]['idProfil'] == $_GET['idprofil'])
												{
													echo '<tr class="bg-warning">';
														echo '<td>'.($i+1).'</td>';
														echo '<td>'.$gen5_tab[$i]['nomProfil'].'</td>'; 
														echo '<td>'.$gen5_tab[$i]['nb_part'].'</td>'; 
														echo '<td>'.$gen5_tab[$i]['nb_point'].'</td>';
													echo "</tr>";
												}
												$i++;
											} 
										}
									?>
							  </tbody>
						</table>
					  </div>
					  <div class="tab-pane fade" id="nav-pays3" role="tabpanel" aria-labelledby="nav-pays3">
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
									$trouve = 0;
										while($i<=10) 
										{
											if(isset($gen6_tab[$i]) && !empty($gen6_tab[$i]))
											{
												if($gen6_tab[$i]['idProfil'] == $_GET['idprofil'])
												{
													$trouve_class = "bg-warning";
													$trouve = 1;
												}
												else
												{
													$trouve_class = "";
												}
												echo '<tr class="'.$trouve_class.'">';
													echo '<td>'.($i+1).'</td>';
													echo '<td>'.$gen6_tab[$i]['nomProfil'].'</td>'; 
													echo '<td>'.$gen6_tab[$i]['nb_part'].'</td>'; 
													echo '<td>'.$gen6_tab[$i]['nb_point'].'</td>';
												echo "</tr>";
											}	
											$i++;
										}
										if ($trouve == 0 && isset($gen6_tab) && !empty($gen6_tab)) 
										{
											echo "<tr>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "<td>...</td>";
											echo "</tr>";
											$i = 0;
											foreach ($gen6_tab as $value) 
											{
												if($gen6_tab[$i]['idProfil'] == $_GET['idprofil'])
												{
													echo '<tr class="bg-warning">';
														echo '<td>'.($i+1).'</td>';
														echo '<td>'.$gen6_tab[$i]['nomProfil'].'</td>'; 
														echo '<td>'.$gen6_tab[$i]['nb_part'].'</td>'; 
														echo '<td>'.$gen6_tab[$i]['nb_point'].'</td>';
													echo "</tr>";
												}
												$i++;
											} 
										}
									?>
							  </tbody>
						</table>
					  </div>
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