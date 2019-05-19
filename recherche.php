<?php session_start() ; 
	require_once('baseDeDonnee.php');
	$pseudo = $_GET['pseudo'];
	$idprofil = mysqli_query($bdd, 'SELECT idProfil FROM profil WHERE nomProfil = "'.utf8_decode($pseudo).'";');
	$idprofil = mysqli_fetch_array($idprofil, MYSQLI_ASSOC);
	$idprofil = (int)$idprofil['idProfil'];
	mysqli_close($bdd);
	if($idprofil != NULL && $idprofil != FALSE)
	{
		header('Location: profil.php?idprofil='.$idprofil.'');
	}
	else
	{
		header('Location: not_found.php');
	}
	
?>
