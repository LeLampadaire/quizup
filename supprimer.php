<?php 
	session_start();
	if($_SESSION['idprofil'] == $_GET['idprofil'])
	{
		require_once('baseDeDonnee.php');
		mysqli_query($bdd, 'DELETE FROM `usersession` WHERE `idProfil` = '.$_GET['idprofil'].';');
		session_destroy();
		mysqli_close($bdd);
		header('Location: profil.php?idprofil='.$_GET['idprofil'].'');
	}
	else
	{
		header('Location: 404.php');
	}
?>