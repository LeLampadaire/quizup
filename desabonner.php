<?php  
	session_start();
	if(!isset($_SESSION['idprofil']) || !isset($_GET['idprofil']) || empty($_GET['idprofil']) || empty($_SESSION['idprofil']))
	{
		header('Location: 404.php');
	}
	require_once('baseDeDonnee.php');
	mysqli_query($bdd, 'DELETE FROM `s_abonner` WHERE `idProfil` = '.$_SESSION['idprofil'].' AND `idProfil_1` = '.$_GET['idprofil'].';');
	mysqli_close($bdd);
	header('Location: profil.php?idprofil='.$_GET['idprofil'].'');

?>