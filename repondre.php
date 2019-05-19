<?php session_start();
	$idprofil = $_SESSION['idprofil'];
	$idpartie = $_SESSION['idpartie'];
	$idquestion = $_POST['idquestion'];
	$bonus = $_SESSION['bonus'];
	
	$repondre = $_POST['reponse'];
	$t1 = $_POST['t1'];
	$t2 = time();
	$t = $t2 - $t1;
	$i = 2;
	$point = 11;
	while ($i <= 11) 
	{
		$point--;
		if($t <= $i)
		{
			$i = 12;
		}
		$i++;
	}
	$point = $point*$bonus;
	if((int)$repondre != 1)
	{
		$point = 0;
	}
	require_once('baseDeDonnee.php');

	mysqli_query($bdd, 'INSERT INTO `repondre` (`idProfil`, `idPartie`, `idQuestion`, `reponse`, `points`) VALUES ('.$idprofil.', '.$idpartie.', '.$idquestion.', '.$repondre.', '.$point.');');

	/*Mise à jour du compteur*/

	$compteur = mysqli_query($bdd, 'SELECT COUNT(DISTINCT idQuestion) AS compteur FROM repondre WHERE idPartie ='.$idpartie.';');
	$compteur = mysqli_fetch_array($compteur, MYSQLI_ASSOC);
	$compteur = $compteur['compteur'];
	//Vérification du nombre de question restante
	mysqli_close($bdd);
	if($compteur == 7)
	{
		header('Location: resultat.php');
	}
	else
	{
		
		header('Location: partie.php');
	}

	
	

?>