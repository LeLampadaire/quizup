<?php  
	session_start();
	if(!isset($_SESSION['idprofil']) || !isset($_GET['idtheme']) || empty($_GET['idtheme']) || empty($_SESSION['idprofil'])){
		header('Location: 404.php');
	}
	require_once('baseDeDonnee.php');
	mysqli_query($bdd, 'DELETE FROM `suivre` WHERE `idProfil` = '.$_SESSION['idprofil'].' AND `idTheme` = '.$_GET['idtheme'].';');
	mysqli_close($bdd);
	header('Location: menu_themes.php?idtheme='.$_GET['idtheme'].'');
?>