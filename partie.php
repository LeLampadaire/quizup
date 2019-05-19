<?php session_start() ;
	  ?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>
<head>
	<title><?php echo $nomSite; ?> - Partie</title>
	<meta charset="utf-8">
  <?php require_once('styles.php'); 
  require_once('baseDeDonnee.php'); ?>
  <style type="text/css">
		.pointer:hover {cursor: pointer;
						color: black!important;}
		.pointer:active {color: black!important;}
		.pointer:checked {color: black!important;}
	</style> <!-- Trouver comment color == black when hover -->
  <?php
 	$idpartie = $_SESSION['idpartie'];
  	$compteur = mysqli_query($bdd, 'SELECT COUNT(DISTINCT idQuestion) AS compteur FROM repondre WHERE idPartie ='.$idpartie.';');
	$compteur = mysqli_fetch_array($compteur, MYSQLI_ASSOC);
	$compteur = $compteur['compteur'];
	$numero = $compteur+1;
	  
  	  
  	  $ordre_reponse = mysqli_query($bdd,'SELECT ordreReponses FROM integrer WHERE idPartie = '.$idpartie.' AND numero = '.$numero.' ;');
  	  $id_question = mysqli_query($bdd,'SELECT idQuestion FROM integrer WHERE idPartie = '.$idpartie.' AND numero = '.$numero.' ;');
  	  $id_question = mysqli_fetch_array($id_question, MYSQLI_ASSOC);
  	  $id_question = (int)$id_question['idQuestion'];
	  $question = mysqli_query($bdd,'SELECT libelleQuestion FROM question WHERE idQuestion = '.$id_question.';'); 
	  $reponse = mysqli_query($bdd,'SELECT answer FROM question WHERE idQuestion = '.$id_question.';'); 
	  $distract1 = mysqli_query($bdd,'SELECT distracteur01 FROM question WHERE idQuestion = '.$id_question.';');
	  $distract2 = mysqli_query($bdd,'SELECT distracteur02 FROM question WHERE idQuestion = '.$id_question.';');
	  $distract3 = mysqli_query($bdd,'SELECT distracteur03 FROM question WHERE idQuestion = '.$id_question.';');
	  $illustration = mysqli_query($bdd,'SELECT Illustration FROM question WHERE idQuestion = '.$id_question.';');  


	  $donnee_question = mysqli_fetch_array($question, MYSQLI_ASSOC);
	  $donnee_reponse = mysqli_fetch_array($reponse, MYSQLI_ASSOC);
	  $donnee_reponse = $donnee_reponse['answer'];
	  $donnee_distract1 = mysqli_fetch_array($distract1, MYSQLI_ASSOC);
	  $donnee_distract1 = $donnee_distract1['distracteur01'];
	  $donnee_distract2 = mysqli_fetch_array($distract2, MYSQLI_ASSOC);
	  $donnee_distract2 = $donnee_distract2['distracteur02'];
	  $donnee_distract3 = mysqli_fetch_array($distract3, MYSQLI_ASSOC);
	  $donnee_distract3 = $donnee_distract3['distracteur03'];
	  $donnee_illustration = mysqli_fetch_array($illustration, MYSQLI_ASSOC);
	  $ordre_reponse = mysqli_fetch_array($ordre_reponse, MYSQLI_ASSOC);
	  $ordre_reponse =  (int)$ordre_reponse['ordreReponses'];

				$rep2 = '<label class="pointer btn btn-secondary rounded btn-outline-warning text-white">
			    	<input type="radio" name="reponse" value="2" id="option1" autocomplete="off">'.utf8_encode($donnee_distract1).'
				</label><br>';
				


				$rep3 = '<label class="pointer btn btn-secondary rounded btn-outline-warning text-white">
			    	<input type="radio" name="reponse" value="3" id="option2" autocomplete="off">' .utf8_encode($donnee_distract2).'
				</label><br>';
				


				$rep4 = '<label class="pointer btn btn-secondary rounded btn-outline-warning text-white">
					<input type="radio" name="reponse" value="4" id="option3" autocomplete="off">'.utf8_encode($donnee_distract3).'
				</label><br>';
				


				$rep1 = '<label class="pointer btn btn-secondary rounded btn-outline-warning text-white">
					<input type="radio" name="reponse" value="1" id="option4" autocomplete="off">'.utf8_encode($donnee_reponse).'
				</label><br>';
	
  ?>

</head>
<body class="bg-secondary">
	<div>
		
    <!-- HEADER -->
    <?php require_once('header.php'); ?>
		<!-- Contenu principale -->
		<section class="container text-center mt-5 text-white principale">
			<!-- Illustration de la question (peut être NULL) -->
			<?php if($numero > 7)
			{
				header('Location: resultat.php');
			} ?>
			<div class="card text-center bg-dark">
				<div class="card-header">
					<h3>Question <?php echo $numero; ?> sur 7</h3>
			<?php 
			if($donnee_illustration['Illustration'] != NULL)
			{
				echo '<div class="container">';
				echo '<figure class="figure">';
				echo '<img class="figure-img rounded" style="max-width:400px;" src="'.$donnee_illustration['Illustration'].'" alt="Responsive image">';
				echo "</figure>";
				echo "</div>";
			}
			?>
			

			<!-- Question méthode get peut-être ? -->
			
				<?php
					echo '<h3>'.utf8_encode($donnee_question['libelleQuestion']).'</h3>';
  					echo "<br>";
				?>
				</div>
			
			<div class="card-body">
			<form method="POST" action="repondre.php">
				<div class=" btn-group btn-group-toggle btn-group-vertical" data-toggle="buttons">

		<?php
				$t1 = time();

				$ordre4 = $ordre_reponse%10;
				
				$ordre3 = ($ordre_reponse/10)%10;
				
				$ordre2 = ($ordre_reponse/100)%10;
				
				$ordre1 = ($ordre_reponse/1000)%10;

				$ordre = array($ordre1 => $rep1 ,$ordre2 => $rep2 ,$ordre3 => $rep3 ,$ordre4 => $rep4 );
				$i = 1; 
				
				while ($i <= 4) 
				{
					echo $ordre[$i];
					$i++;
				}
		?>
				</div>
				<br>

				<input type="hidden" name="t1" value=<?php echo $t1; ?> >
				<input type="hidden" name="idquestion" value=<?php echo $id_question; ?> >
				<input type="submit" value="Valider" class="btn btn-light">
			</form>
			</div>
			</div>
		</section>
		<!-- Footer -->
    <?php require_once('footer.php'); ?>
</body>
</html>