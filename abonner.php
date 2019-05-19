<?php  
	session_start();
	if(!isset($_SESSION['idprofil']) || !isset($_GET['idprofil']) || empty($_GET['idprofil']) || empty($_SESSION['idprofil']))
	{
		header('Location: 404.php');
	}
	else
	{
		if($_SESSION['idprofil'] == $_GET['idprofil'])
		{
			header('Location: 404.php');
		}
	}
	require_once('baseDeDonnee.php');
	mysqli_query($bdd, 'INSERT INTO `s_abonner`(`idProfil`, `idProfil_1`) VALUES ('.$_SESSION['idprofil'].','.$_GET['idprofil'].')');
	mysqli_close($bdd);
	header('Location: profil.php?idprofil='.$_GET['idprofil'].'');
?>