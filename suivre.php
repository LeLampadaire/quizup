<?php  
	session_start();
	if(!isset($_SESSION['idprofil']) || !isset($_GET['idtheme']) || empty($_GET['idtheme']) || empty($_SESSION['idprofil'])){
		header('Location: 404.php');
	}
	require_once('baseDeDonnee.php');
	mysqli_query($bdd, 'INSERT INTO `suivre`(`idProfil`, `idTheme`) VALUES ('.$_SESSION['idprofil'].','.$_GET['idtheme'].')');
	mysqli_close($bdd);
	header('Location: menu_themes.php?idtheme='.$_GET['idtheme'].'');
?>