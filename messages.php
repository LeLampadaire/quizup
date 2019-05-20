<?php 
	session_start();
	require_once('baseDeDonnee.php');

	$Pseudo = $_SESSION['nomprofil'];
    $idPseudo = $_SESSION['idprofil'];
    
?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>
<head>
	<title><?php echo $nomSite; ?> - Messages</title>
	<meta charset="utf-8">
  <?php require_once('styles.php'); ?>
</head>

<body class="bg-secondary">

    <!-- HEADER -->
    <?php require_once('header.php'); ?>

    <section class="container text-center mt-5 text-white principale">

            <div class="card text-center bg-dark" style="height: 1000px; color: black;">

                <!-- ENVOYE D'UN NOUVEAU COMMENTAIRE -->



                <!-- AFFICHAGE DES COMMENTAIRES -->
                <?php $recupmessage = mysqli_query($bdd, 'SELECT profil.idProfil, nomProfil, photoProfil, contenuMessage, DATE_FORMAT(timestampMessage, "%d/%m/%y > %Hh%i") as dateMsg, idMessage_1 FROM message INNER JOIN profil ON(message.idProfil = profil.idProfil) WHERE idTheme = 4 ORDER BY idMessage ASC;');
                
                foreach($recupmessage as $donnees){ 
                    
                    
                    if($donnees['idMessage_1'] == NULL){ ?>
                        <br>
                        <div aria-live="polite" aria-atomic="true" class="d-flex align-items-center" style="position: relative; left: 10px;padding-bottom: 10px;">
                            <div class="toast" role="alert" aria-live="assertive" data-autohide="false" style="position: relative; z-index: 1;min-height: 80px; min-width: 500px;">
                                <div class="toast-header">
                                    <img src="<?php echo $donnees['photoProfil']; ?>" class="rounded mr-2" alt="Logo" height="20px">
                                    <a href="profil.php?idprofil=<?php echo $donnees['idProfil']; ?>" alt="<?php echo $donnees['nomProfil']; ?>"><strong class="mr-auto"><?php echo $donnees['nomProfil']; ?></strong></a>
                                    <small><?php echo $donnees['dateMsg']; ?></small>
                                    
                                    <div class="btn-group">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                        <div class="dropdown-menu" style="position: absolute; z-index: 100;">
                                            <a class="dropdown-item" href="#">Commentez</a>
                                            <a class="dropdown-item" href="#">Supprimez</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="toast-body">
                                    <?php echo $donnees['contenuMessage']; ?>
                                </div>
                            </div>
                        </div>
                        
                    <?php
                        $nomTempo = $donnees['nomProfil'];
                    
                        }else{ ?>                   
                        <div aria-live="polite" aria-atomic="true" class="d-flex align-items-center" style="position: relative; left: 40px;padding-bottom: 10px;" style="min-height: 200px; width: 100%;">
                            <div class="toast" role="alert" aria-live="assertive" data-autohide="false">
                                <div class="toast-header">
                                    <img src="<?php echo $donnees['photoProfil']; ?>" class="rounded mr-2" alt="Logo" height="20px">
                                    <a href="profil.php?idprofil=<?php echo $donnees['idProfil']; ?>" alt="<?php echo $donnees['nomProfil']; ?>"><strong class="mr-auto"><?php echo $donnees['nomProfil']; ?></strong></a>
                                    <small><?php echo $donnees['dateMsg']; ?></small>
                                </div>
                                <div class="toast-header"><small><?php echo "A répondu à ".$nomTempo." :"; ?></small></div>
                                <div class="toast-body">
                                    <?php echo $donnees['contenuMessage']; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>


                <?php } ?>

            </div>

    </section>


    <!-- Footer -->
    <?php 
        require_once('footer.php'); 
        mysqli_close($bdd);
    ?>

<script>
	//Affichage des Toasts
	$('.toast').toast('show');

</script>

</body>
</html>