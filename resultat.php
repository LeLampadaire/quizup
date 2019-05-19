<?php 
	session_start() ;
	if(!isset($_GET['idpartie']) || empty($_GET['idpartie']))
    {
      $idprofil = $_SESSION['idprofil'];
      $idpartie = $_SESSION['idpartie'];
    }
    else
    {
      $idprofil = $_GET['idprofil'];
      $idpartie = $_GET['idpartie'];
    }
 ?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>
<head>
	<title><?php echo $nomSite; ?> - Résultats</title>
	<meta charset="utf-8">
  <?php 
  require_once('styles.php'); 
  require_once('baseDeDonnee.php'); 
  
  $question = mysqli_query($bdd,'SELECT question.idQuestion AS id, question.libelleQuestion AS libelle, integrer.numero AS num FROM integrer, question WHERE integrer.idQuestion = question.idQuestion AND integrer.idPartie = '.$idpartie.' ORDER BY numero ASC;');
  $i = 0;
  while ($recup = mysqli_fetch_array($question, MYSQLI_ASSOC)) 
  {
  	$liste_question[$i]['idquestion'] = $recup['id'];
  	$liste_question[$i]['libellequestion'] = $recup['libelle'];
  	$liste_question[$i]['numero'] = (int)$recup['num'];
  	$i++;	
  }




  $reponse = mysqli_query($bdd, 'SELECT repondre.reponse AS num FROM repondre, integrer WHERE integrer.idQuestion = repondre.idQuestion AND integrer.idPartie = repondre.idPartie AND repondre.idProfil = '.$idprofil.' AND repondre.idPartie = '.$idpartie.' ORDER BY integrer.numero;');
  $i = 0;
  while ($recup = mysqli_fetch_array($reponse, MYSQLI_ASSOC)) 
  {
  	$liste_reponse[$i]['num'] = $recup['num'];
  	$i++;	
  }
  



  $theme = mysqli_query($bdd,'SELECT DISTINCT theme.libelleTheme AS theme, theme.idTheme FROM theme, question, repondre WHERE repondre.idQuestion = question.idQuestion AND question.idTheme = theme.idTheme AND repondre.idProfil = '.$idprofil.' AND repondre.idPartie = '.$idpartie.';');
  $theme = mysqli_fetch_array($theme, MYSQLI_ASSOC);
  $id_theme = $theme['idTheme'];
  $theme = $theme['theme'];




  $niveau = mysqli_query($bdd,'SELECT SUM(repondre.points) AS points FROM question, repondre WHERE question.idQuestion = repondre.idQuestion AND question.idTheme = '.$id_theme.' AND repondre.idProfil = '.$idprofil.';');
  $niveau = mysqli_fetch_array($niveau, MYSQLI_ASSOC);
  $niveau = (int)$niveau['points'];
  $niveau = floor(($niveau + 70)/270);  




  $points = mysqli_query($bdd,'SELECT SUM(repondre.points) AS points FROM repondre WHERE repondre.idProfil = '.$idprofil.' AND repondre.idPartie = '.$idpartie.';');
  $points = mysqli_fetch_array($points, MYSQLI_ASSOC);
  $points = (int)$points['points'];
  



  $liste_titre  = array();
  $titres = mysqli_query($bdd,'SELECT titre.libelleTitre AS titre, titre.niveauRequis AS niv, titre.idTitre FROM titre WHERE titre.idTheme = '.$id_theme.' ORDER BY niveauRequis;');
  $i = 0;
  while ($recup = mysqli_fetch_array($titres, MYSQLI_ASSOC)) 
  {
  	$liste_titre[$i]['idtitre'] = $recup['idTitre'];
  	$liste_titre[$i]['libelletitre'] = $recup['titre'];
  	$liste_titre[$i]['niveau'] = $recup['niv'];
  	$i++;	
  }
  $nb_titre = $i;

 

  $deja_remporte = mysqli_query($bdd,'SELECT remporter.idTitre FROM remporter WHERE remporter.idProfil = '.$idprofil.';');
  $liste_deja_remporte = array();
  $i = 0;
  while ($recup = mysqli_fetch_array($deja_remporte, MYSQLI_ASSOC)) 
  {
  	$liste_deja_remporte[$i] = $recup['idTitre'];
  	$i++;
  }
  
 
  ?>

</head>
<body class="bg-secondary">
	<div>
		
    <!-- HEADER -->
    <?php require_once('header.php'); ?>
		<!-- Contenu principale -->
		<section class="container text-center mt-5 text-white principale">
			<!-- Illustration de la question (peut être NULL) -->
			<div class="card text-center bg-dark">
				<div class="card-header">
					<h2>Résultats :</h2>
				</div>
				<div class="card-body">
					<!-- TOTAL DES POINTS, NIVEAUX ACTUEL ET TITRE REMPORTÉ -->
					<h3>Points remportés lors de la partie : <?php echo $points; ?></h3><br>
					<h3>Niveau actuel dans le thème "<?php echo $theme; ?>" : <?php echo $niveau; ?></h3><br>
					<?php 
						$i = 0;
						while ($i < $nb_titre) 
						{
					 	 	if($niveau >= $liste_titre[$i]['niveau'] AND !in_array($liste_titre[$i]['idtitre'], $liste_deja_remporte) )
						  	{
						  		$id_titre = $liste_titre[$i]['idtitre'];
						  		echo "<p>vous avez remportés le titre de '".utf8_encode($liste_titre[$i]['libelletitre'])."'</p><br>";
						  		$test = mysqli_query($bdd, 'INSERT INTO `remporter` (`idProfil`, `idTitre`) VALUES ('.$idprofil.', '.$id_titre.');');
						  	}
						  	$i++;
						}
					?>
					
					<h3>Questions :</h3>
					<div class="list-group">
						<a <?php if($liste_reponse[0]['num'] == 1){echo 'class="list-group-item list-group-item-action list-group-item-success" ';}else{echo 'class="list-group-item list-group-item-action list-group-item-danger" ';}  ?> <?php echo 'href="detail_question.php?numero=1&idprofil='.$idprofil.'&idpartie='.$idpartie.'';?>"><?php echo utf8_encode($liste_question[0]['libellequestion']); ?></a>
						<a <?php if($liste_reponse[1]['num'] == 1){echo 'class="list-group-item list-group-item-action list-group-item-success" ';}else{echo 'class="list-group-item list-group-item-action list-group-item-danger" ';}  ?> <?php echo 'href="detail_question.php?numero=2&idprofil='.$idprofil.'&idpartie='.$idpartie.'';?>"><?php echo utf8_encode($liste_question[1]['libellequestion']); ?></a>
						<a <?php if($liste_reponse[2]['num'] == 1){echo 'class="list-group-item list-group-item-action list-group-item-success" ';}else{echo 'class="list-group-item list-group-item-action list-group-item-danger" ';}  ?> <?php echo 'href="detail_question.php?numero=3&idprofil='.$idprofil.'&idpartie='.$idpartie.'';?>"><?php echo utf8_encode($liste_question[2]['libellequestion']); ?></a>
						<a <?php if($liste_reponse[3]['num'] == 1){echo 'class="list-group-item list-group-item-action list-group-item-success" ';}else{echo 'class="list-group-item list-group-item-action list-group-item-danger" ';}  ?> <?php echo 'href="detail_question.php?numero=4&idprofil='.$idprofil.'&idpartie='.$idpartie.'';?>"><?php echo utf8_encode($liste_question[3]['libellequestion']); ?></a>
						<a <?php if($liste_reponse[4]['num'] == 1){echo 'class="list-group-item list-group-item-action list-group-item-success" ';}else{echo 'class="list-group-item list-group-item-action list-group-item-danger" ';}  ?> <?php echo 'href="detail_question.php?numero=5&idprofil='.$idprofil.'&idpartie='.$idpartie.'';?>"><?php echo utf8_encode($liste_question[4]['libellequestion']); ?></a>
						<a <?php if($liste_reponse[5]['num'] == 1){echo 'class="list-group-item list-group-item-action list-group-item-success" ';}else{echo 'class="list-group-item list-group-item-action list-group-item-danger" ';}  ?> <?php echo 'href="detail_question.php?numero=6&idprofil='.$idprofil.'&idpartie='.$idpartie.'';?>"><?php echo utf8_encode($liste_question[5]['libellequestion']); ?></a>
						<a <?php if($liste_reponse[6]['num'] == 1){echo 'class="list-group-item list-group-item-action list-group-item-success" ';}else{echo 'class="list-group-item list-group-item-action list-group-item-danger" ';}  ?> <?php echo 'href="detail_question.php?numero=7&idprofil='.$idprofil.'&idpartie='.$idpartie.'';?>"><?php echo utf8_encode($liste_question[6]['libellequestion']); ?></a>
					</div>
				</div>
				<div class="card-footer">
					<a href="themes.php"><button type="button" class="btn btn-dark">Revenir à la liste de thèmes</button></a>
          <?php echo '<a href="profil.php?idprofil='.$idprofil.'">';?><button type="button" class="btn btn-dark">Revenir au profil</button></a>
				</div>
			</div>
		</section>
		<!-- Footer -->
		<?php require_once('footer.php'); 
					mysqli_close($bdd);
		?>
</body>
</html>