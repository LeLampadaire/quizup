<?php  
	session_start();
	if(!isset($_SESSION['idprofil']) || !isset($_GET['idprofil']) || empty($_GET['idprofil']) || empty($_SESSION['idprofil']))
	{
		header('Location: 404.php');
	}
	require_once('baseDeDonnee.php');
	mysqli_query($bdd, 'DELETE FROM `bloquer` WHERE `idProfil` = '.$_SESSION['idprofil'].' AND `idProfil_1` = '.$_GET['idprofil'].';');
	mysqli_close($bdd);
	header('Location: blocages.php?idprofil='.$_SESSION['idprofil'].'');

?>