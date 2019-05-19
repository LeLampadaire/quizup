<?php session_start();
    
	require_once('baseDeDonnee.php');

	$Pseudo = $_SESSION['nomprofil'];
	$idPseudo = $_SESSION['idprofil'];

	if(empty($_SESSION)){
		header('Location: 404.php');
    }

    if(!empty($_POST)){
        $newnomprofil = $_POST['newnomprofil'];
        $newmail = $_POST['newmail'];
        if($_POST['mdp'] != NULL){
            $newmdp = md5($_POST['mdp']);
            mysqli_query($bdd, 'UPDATE usersession SET email = "'.utf8_decode($newmail).'", password = "'.$newmdp.'" WHERE idProfil = '.$idPseudo.' ;');
        }else{
            mysqli_query($bdd, 'UPDATE usersession SET email = "'.utf8_decode($newmail).'" WHERE idProfil = '.$idPseudo.' ;');
        }

        $newvilleorigine = $_POST['newvilleorigine'];
        $newlangue = $_POST['newlangue'];
        $newpays = $_POST['newpays'];
        $newregion = $_POST['newregion'];
        $newbio = $_POST['newbio'];

        if(empty($_POST['newprofilprive'])){
            $newprofilprive = 0;
        }else{
            $newprofilprive = 1;
        }

        $idpays = mysqli_query($bdd, 'SELECT idPays FROM pays WHERE libellePays = "'.utf8_decode($newpays).'";');
        $idpays = mysqli_fetch_array($idpays, MYSQLI_ASSOC);
        $idregion = mysqli_query($bdd, 'SELECT idRegion FROM region WHERE libelleRegion = "'.utf8_decode($newregion).'";');;
        $idregion = mysqli_fetch_array($idregion, MYSQLI_ASSOC);
        $idpays = (int)$idpays['idPays'];
        $idregion = (int)$idregion['idRegion'];

        if(!empty($_FILES['profil']['name']) || !empty($_FILES['facade']['name']))
        {
            /*PHOTO DE PROFIL*/
            //Récupère l'extension du fichier par exemple .png
            if(!empty($_FILES['profil']['name']) && !empty($_FILES['facade']['name']))
            {
                $extension_upload = strtolower(  substr(  strrchr($_FILES['profil']['name'], '.')  ,1)  );
                
                //Définition du nom et du chemin d'accès de l'image
                $nomphoto = "images/profil/".$idPseudo.".{$extension_upload}";
                

                //Transfert du fichier uploadé par l'utilisateur du dossier temporaire à l'emplacement indiqué dans $nom
                $move = move_uploaded_file($_FILES['profil']['tmp_name'],$nomphoto);

                /*PHOTO DE FACADE*/
                $extension_upload = strtolower(  substr(  strrchr($_FILES['facade']['name'], '.')  ,1)  );
                $nomfacade = "images/cover/".$idPseudo.".{$extension_upload}";
                move_uploaded_file($_FILES['facade']['tmp_name'],$nomfacade);

                mysqli_query($bdd, 'UPDATE profil SET photoFacade = "'.utf8_decode($nomfacade).'", photoProfil = "'.utf8_decode($nomphoto).'", nomProfil = "'.utf8_decode($newnomprofil).'", villeOrigine = "'.utf8_decode($newvilleorigine).'", langue = "'.utf8_decode($newlangue).'", idPays = '.$idpays.', idRegion = '.$idregion.', bio = "'.utf8_decode($newbio).'", profilPrive = '.$newprofilprive.' WHERE idProfil = '.$idPseudo.';');
            }
            else
            {
                if(!empty($_FILES['profil']['name']))
                {
                    $extension_upload = strtolower(  substr(  strrchr($_FILES['profil']['name'], '.')  ,1)  );
                
                    //Définition du nom et du chemin d'accès de l'image
                    $nomphoto = "images/profil/".$idPseudo.".{$extension_upload}";
                    

                    //Transfert du fichier uploadé par l'utilisateur du dossier temporaire à l'emplacement indiqué dans $nom
                    $move = move_uploaded_file($_FILES['profil']['tmp_name'],$nomphoto);

                    /*PHOTO DE FACADE*/
                    $extension_upload = strtolower(  substr(  strrchr($_FILES['facade']['name'], '.')  ,1)  );
                    $nomfacade = "images/cover/".$idPseudo.".{$extension_upload}";
                    move_uploaded_file($_FILES['facade']['tmp_name'],$nomfacade);

                    mysqli_query($bdd, 'UPDATE profil SET photoProfil = "'.utf8_decode($nomphoto).'", nomProfil = "'.utf8_decode($newnomprofil).'", villeOrigine = "'.utf8_decode($newvilleorigine).'", langue = "'.utf8_decode($newlangue).'", idPays = '.$idpays.', idRegion = '.$idregion.', bio = "'.utf8_decode($newbio).'", profilPrive = '.$newprofilprive.' WHERE idProfil = '.$idPseudo.';');
                }
                else
                {
                    
                /*PHOTO DE FACADE*/
                $extension_upload = strtolower(  substr(  strrchr($_FILES['facade']['name'], '.')  ,1)  );
                $nomfacade = "images/cover/".$idPseudo.".{$extension_upload}";
                move_uploaded_file($_FILES['facade']['tmp_name'],$nomfacade);

                mysqli_query($bdd, 'UPDATE profil SET photoFacade = "'.utf8_decode($nomfacade).'", nomProfil = "'.utf8_decode($newnomprofil).'", villeOrigine = "'.utf8_decode($newvilleorigine).'", langue = "'.utf8_decode($newlangue).'", idPays = '.$idpays.', idRegion = '.$idregion.', bio = "'.utf8_decode($newbio).'", profilPrive = '.$newprofilprive.' WHERE idProfil = '.$idPseudo.';');
                }
            }
            
           

        }
        else
        {
            mysqli_query($bdd, 'UPDATE profil SET nomProfil = "'.utf8_decode($newnomprofil).'", villeOrigine = "'.utf8_decode($newvilleorigine).'", langue = "'.utf8_decode($newlangue).'", idPays = '.$idpays.', idRegion = '.$idregion.', bio = "'.utf8_decode($newbio).'", profilPrive = '.$newprofilprive.' WHERE idProfil = '.$idPseudo.';');
        }
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>
<head>
	<title><?php echo $nomSite; ?> - Modification du profil</title>
    <meta charset="utf-8">
    <?php require_once('styles.php'); ?>
</head>
<body class="bg-secondary">

    <!-- HEADER -->
    <?php require_once('header.php'); ?>

    <section class="container text-center mt-5 text-white principale">
        <div class="card text-center bg-dark">
        <?php 
            $profil = mysqli_query($bdd, 'SELECT nomProfil, langue, villeOrigine, bio, profilPrive, region.libelleRegion as region, pays.libellePays as pays FROM profil INNER JOIN region ON(profil.idRegion = region.idRegion) INNER JOIN pays ON(profil.idPays = pays.idPays) WHERE idProfil = '.$_SESSION['idprofil'].';');
            $profil = mysqli_fetch_array($profil, MYSQLI_ASSOC);
            $compte = mysqli_query($bdd, 'SELECT email FROM usersession WHERE idProfil = '.$idPseudo.';');
            $compte = mysqli_fetch_array($compte, MYSQLI_ASSOC);
        ?>

            <div class="card-header">
                <h2>Modification du profil</h2>
            </div>

            <div class="card-body">

                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col-md-6">

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="nomprofil">Nom du profil</span>
                                </div>
                                <input type="text" class="form-control" value="<?php echo $profil['nomProfil']; ?>" aria-label="nomprofil" aria-describedby="nomprofil" name="newnomprofil">
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="villeorigine">Ville d'origine</span>
                                </div>
                                <input type="text" class="form-control" value="<?php echo utf8_encode($profil['villeOrigine']); ?>" aria-label="villeorigine" aria-describedby="villeorigine" name="newvilleorigine">
                            </div>
                            
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="langue">Langue</span>
                                </div>

                                <select class="form-control" name="newlangue" id="langue" required>
                                    <?php if(utf8_encode($profil['langue']) == "allemand"){ echo '<option value="allemand" selected>Allemand</option>';}else{ echo '<option value="allemand">Allemand</option>'; } ?>
                                    <?php if(utf8_encode($profil['langue']) == "anglais"){ echo '<option value="anglais" selected>Anglais</option>';}else{ echo '<option value="anglais">Anglais</option>'; } ?>
                                    <?php if(utf8_encode($profil['langue']) == "espagnol"){ echo '<option value="espagnol" selected>Espagnol</option>';}else{ echo '<option value="espagnol">Espagnol</option>'; } ?>
                                    <?php if(utf8_encode($profil['langue']) == "français"){ echo '<option value="français" selected>Français</option>';}else{ echo '<option value="français">Français</option>'; } ?>
                                    <?php if(utf8_encode($profil['langue']) == "néerlandais"){ echo '<option value="néerlandais" selected>Néerlandais</option>';}else{ echo '<option value="néerlandais">Néerlandais</option>'; } ?>
								</select>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="pays">Pays</span>
                                </div>

                                <select class="form-control" name="newpays" id="pays" required>
                                    <?php 
                                    $listpays = mysqli_query($bdd,'SELECT libellePays FROM pays');

                                    foreach($listpays as $donnees){
                                        if($donnees['libellePays'] == utf8_encode($profil['pays'])){
                                            echo'<option value="'. $donnees['libellePays'] .'" selected>'. utf8_encode($donnees['libellePays']) .'</option>';
                                        }else{
                                            echo'<option value="'. $donnees['libellePays'] .'">'. utf8_encode($donnees['libellePays']) .'</option>';
                                        }
                                    } ?>
                                </select>
                            </div>
                            
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="regiontag">Region</span>

                                    <input list="region" name="newregion" value="<?php echo utf8_encode($profil['region']); ?>">
                                </div>
                                <datalist id="region">
                                    <?php 
                                        $listregion = mysqli_query($bdd,'SELECT libelleRegion FROM region');

                                        foreach($listregion as $donnees){
                                            echo '<option value="'.utf8_encode($donnees['libelleRegion']).'">';
                                        }
                                    ?>
                                </datalist>

                            </div>
                            
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="bio">Biographie</span>
                                </div>
                                <textarea id="bio" rows="3" cols="56" name="newbio"><?php echo utf8_encode($profil['bio']); ?></textarea>
                            </div>

                            <div class="form-check">
                                <label class="form-check-label" for="profilprive">
                                <input class="form-check-input" type="checkbox" id="profilprive" name="newprofilprive" <?php if($profil['profilPrive']){ echo "checked"; } ?>>
                                    Compte privé
                                </label>
                            </div>

                        </div>

                        <div class="form-group col-md-6">
                            
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="mdp">Mot de passe</span>
                                </div>
                                <input type="password" class="form-control" placeholder="Nouveau mot de passe" aria-label="mdp" aria-describedby="mdp" name="mdp">
                            </div>
                            
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="mail">Mail</span>
                                </div>
                                <input type="text" class="form-control" value="<?php echo utf8_encode($compte['email']); ?>" aria-label="mail" aria-describedby="mail" name="newmail">
                            </div>

                            <label><h5>Uploader une nouvelle image de profil</h5>
                                <input type="file" name="profil"><br>
                            </label><br><br>

                            <label><h5>Uploader une nouvelle image de façade</h5>
                                <input type="file" name="facade"><br>
                            </label><br><br>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-outline-success">Mettre à jour</button>
                </form>
                <div class="text-left">
                    <a href="profil.php?idprofil=<?php echo $_SESSION['idprofil']; ?>" alt="<?php echo $_SESSION['nomprofil']; ?>">
                        <button type="button" class="btn btn-outline-light">Retour</button>
                    </a>
                </div>
            </div>
        
        </div>
    </section>

    <!-- Footer -->
    <?php 
        require_once('footer.php'); 
        mysqli_close($bdd);
    ?>

</body>
</html>