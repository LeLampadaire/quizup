<?php 
	session_start();
	require_once('baseDeDonnee.php');

	$Pseudo = $_SESSION['nomprofil'];
    $idPseudo = $_SESSION['idprofil'];

    $modification = 0;

    // A CHANGER 
    $idTheme = 15;
    
    if(!empty($_POST['new-message'])){
        $message = $_POST['new-message'];
        mysqli_query($bdd, 'INSERT INTO message(idMessage, timestampMessage, contenuMessage, idMessage_1, idTheme, idProfil) VALUES (NULL,CURRENT_TIMESTAMP(),"'.$message.'", NULL, '.$idTheme.', '.$idPseudo.');');
    }

    if(!empty($_POST['modifier'])){
        $idMessage_modif = $_POST['modifier'];
        var_dump($idMessage_modif);
        $modification = 1;
    }

    if(!empty($_POST['modification-text'])){
        $modification_text = $_POST['modification-text'];
        $id_modification_text = $_POST['id-modification-text'];
        $test = mysqli_query($bdd, 'UPDATE message SET contenuMessage = "'.$modification_text.'" WHERE  idMessage = '.$id_modification_text.';');
    }

    if(!empty($_POST['supprimer'])){
        $idMessage = (int)$_POST['supprimer'];
        mysqli_query($bdd, 'DELETE FROM message WHERE idMessage = '.$idMessage.';');
        
    }

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

            <div class="card text-center bg-dark" style="height: auto; color: black;">

                <!-- ENVOYE D'UN NOUVEAU COMMENTAIRE -->
                <form action="" method="POST">
                    <div class="input-group mb-12">
                        <input type="text" class="form-control" placeholder="Votre message ..." aria-label="Votre message ..." aria-describedby="button-envoyez" name="new-message">
                        <div class="input-group-append">
                            <input class="btn btn-primary" type="submit" value="Envoyez !">
                        </div>
                    </div>
                </form>

                <!-- AFFICHAGE DES COMMENTAIRES -->
                <?php $recupmessage = mysqli_query($bdd, 'SELECT profil.idProfil, idMessage, nomProfil, contenuMessage, DATE_FORMAT(timestampMessage, "%d/%m/%y > %Hh%i") as dateMsg, photoProfil FROM profil INNER JOIN message ON(profil.idProfil = message.idProfil) WHERE idMessage_1 IS NULL AND idTheme = '.$idTheme.' ORDER BY timestampMessage DESC;');

                foreach($recupmessage as $recup){ ?>
            
                    <br>
                    <div aria-live="polite" aria-atomic="true" class="d-flex align-items-center" style="position: relative;left: 10px;padding-bottom: 10px;">
                        <div class="toast" role="alert" aria-live="assertive" data-autohide="false">
                            <div class="toast-header" >
                                <img src="<?php echo $recup['photoProfil']; ?>" class="rounded mr-2" alt="Logo" height="20px">
                                <a href="profil.php?idprofil=<?php echo $recup['idProfil']; ?>" alt="<?php echo $recup['nomProfil']; ?>"><strong class="mr-auto"><?php echo $recup['nomProfil']; ?></strong></a>
                                <small style="padding-left: 10px;"><?php echo $recup['dateMsg']; ?></small>
                                
                                <div style="padding-left: 10px;">
                                    
                                    <?php $testdereponse = mysqli_query($bdd,'SELECT idMessage FROM message WHERE idMessage_1 = '.$recup['idMessage'].';');
                                    $testdereponse = mysqli_fetch_array($testdereponse, MYSQLI_ASSOC);

                                    echo '<form action="" method="POST">';
                                        echo '<button type="button" class="btn btn-primary btn-sm"><img src="images/repondre.png" alt="<-" width="15px"></button>';
                                    if($recup['idProfil'] == $idPseudo AND $testdereponse == NULL){ 
                                        echo '<button type="submit" class="btn btn-primary btn-sm" name="modifier" value="'.$recup['idMessage'].'"><img src="images/modifier.png" alt="M" width="15px"></button>';
                                        echo '<button type="submit" class="btn btn-danger btn-sm" name="supprimer" value="'.$recup['idMessage'].'"><img src="images/supprimer.png" alt="x" width="15px"></button>';

                                    } 
                                    echo '</form>'; ?>
                                </div>
                            </div>
                            <div class="toast-body">
                                <?php 
                                if($modification == 0){
                                    echo $recup['contenuMessage']; 
                                }else{
                                    if($idMessage_modif == $recup['idMessage']){
                                        echo '<form action="" method="POST">';
                                            echo '<div class="input-group mb-3"><input type="text" class="form-control" placeholder="Votre message" name="modification-text" value="'.$recup['contenuMessage'].'" aria-describedby="btn-modifiez">';
                                            echo '<input type="hidden" name="id-modification-text" value="'.$recup['idMessage'].'"><br>';
                                            echo '<div class="input-group-append"><input class="btn btn-primary btn-sm" type="submit" id="btn-modifiez" value="Modifiez !"></div></div>';
                                        echo '</form>';
                                    }else{
                                        echo $recup['contenuMessage'];
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <?php 
                    $nomTempo = $recup['nomProfil'];
                    $idTempo = $recup['idMessage'];
                    $cpt = 0;

                    $reponsemessage = mysqli_query($bdd, 'SELECT profil.idProfil, idMessage, nomProfil, contenuMessage, DATE_FORMAT(timestampMessage, "%d/%m/%y > %Hh%i") as dateMsg, photoProfil FROM profil INNER JOIN message ON(profil.idProfil = message.idProfil) WHERE idMessage_1 = '.$recup['idMessage'].' AND idTheme = '.$idTheme.' ORDER BY timestampMessage ASC;');
                    
                    foreach($reponsemessage as $reponse){ 
                        $cpt++; ?>
                        <div aria-live="polite" aria-atomic="true" class="d-flex align-items-center" style="position: relative;left: 40px;padding-bottom: 10px;">
                            <div class="toast" role="alert" aria-live="assertive" data-autohide="false">
                                <div class="toast-header" >
                                    <img src="<?php echo $reponse['photoProfil']; ?>" class="rounded mr-2" alt="Logo" height="20px">
                                    <a href="profil.php?idprofil=<?php echo $reponse['idProfil']; ?>" alt="<?php echo $reponse['nomProfil']; ?>"><strong class="mr-auto"><?php echo $reponse['nomProfil']; ?></strong></a>
                                    <small style="padding-left: 10px;"><?php echo $reponse['dateMsg']; ?></small>
                                    
                                    <div style="padding-left: 10px;">
                                        <?php $testdereponse = mysqli_query($bdd,'SELECT COUNT(idMessage) as cpt FROM message WHERE idMessage_1 = '.$idTempo.';');
                                        $testdereponse = mysqli_fetch_array($testdereponse, MYSQLI_ASSOC);

                                        if($reponse['idProfil'] == $idPseudo AND (int)$testdereponse['cpt'] == $cpt){ 
                                            echo '<form action="" method="POST">';
                                                echo '<button type="submit" class="btn btn-primary btn-sm" name="modifier" value="'.$reponse['idMessage'].'"><img src="images/modifier.png" alt="M" width="15px"></button>';
                                                echo '<button type="submit" class="btn btn-danger btn-sm" name="supprimer" value="'.$reponse['idMessage'].'"><img src="images/supprimer.png" alt="x" width="15px"></button>';
                                            echo '</form>';  
                                        } ?>
                                    </div>
                                </div>
                                    
                                <div class="toast-header"><small><?php echo "A répondu à ".$nomTempo." :"; ?></small></div>
                                
                                <div class="toast-body">
                                    <?php 
                                    if($modification == 0){
                                        echo $reponse['contenuMessage']; 
                                    }else{
                                        if($idMessage_modif == $reponse['idMessage']){
                                            echo '<form action="" method="POST">';
                                                echo '<div class="input-group mb-3"><input type="text" class="form-control" placeholder="Votre message" name="modification-text" value="'.$reponse['contenuMessage'].'" aria-describedby="btn-modifiez">';
                                                echo '<input type="hidden" name="id-modification-text" value="'.$reponse['idMessage'].'"><br>';
                                                echo '<div class="input-group-append"><input class="btn btn-primary btn-sm" type="submit" id="btn-modifiez" value="Modifiez !"></div></div>';
                                            echo '</form>';
                                        }else{
                                            echo $reponse['contenuMessage'];
                                        }
                                    }
                                    ?>
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