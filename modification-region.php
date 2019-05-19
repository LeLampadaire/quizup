<?php session_start();
    
	require_once('baseDeDonnee.php');

	$Pseudo = $_SESSION['nomprofil'];
	$idPseudo = $_SESSION['idprofil'];

	if($idPseudo != 4){
		header('Location: index.php');
    }

    if(isset($_POST['recherche-pays'])){
        $recup = array_reverse(explode(" ",$_POST['recherche-pays']));
        
        $recherche_pays_id = (int)$recup[0];
        
        $espace = " ";
        $taille = count($recup);
        if($taille == 2){
            $recherche_pays_nom = $recup[1];
        }else if($taille == 3){
            $recherche_pays_nom = $recup[2].$espace.$recup[1];
        }else if($taille == 4){
            $recherche_pays_nom = $recup[3].$espace.$recup[2].$espace.$recup[1];
        }else if($taille == 5){
            $recherche_pays_nom = $recup[4].$espace.$recup[3].$espace.$recup[2].$espace.$recup[1];
        }else if($taille == 6){
            $recherche_pays_nom = $recup[5].$espace.$recup[4].$espace.$recup[3].$espace.$recup[2].$espace.$recup[1];
        }else if($taille == 7){
            $recherche_pays_nom = $recup[6].$espace.$recup[5].$espace.$recup[4].$espace.$recup[3].$espace.$recup[2].$espace.$recup[1];
        }else if($taille == 8){
            $recherche_pays_nom = $recup[7].$espace.$recup[6].$espace.$recup[5].$espace.$recup[4].$espace.$recup[3].$espace.$recup[2].$espace.$recup[1];
        }
    }
    
    if(!empty($_POST['ajoutnomregion'])){
        $libelle = $_POST['ajoutnomregion'];
        $recherche_pays_nom = $_POST['recup-nom'];
        $recherche_pays_id = $_POST['recup-id'];
        mysqli_query($bdd, 'INSERT INTO region(idRegion, libelleRegion, drapeauRegion, idPays) VALUES (NULL, "'.$libelle.'", NULL, '.$recherche_pays_id.');');
    }

    if(!empty($_POST['newnomregion'])){
        $libelle = $_POST['newnomregion'];
        $recherche_pays_nom = $_POST['recup-nom'];
        $recherche_pays_id = $_POST['recup-id'];
        $ancien_nom = $_POST['ancien-nom'];
        $test = mysqli_query($bdd, 'UPDATE region SET libelleRegion = "'.$libelle.'" WHERE region.libelleRegion = "'.$ancien_nom.'" AND region.idPays = "'.$recherche_pays_id.'";');
        

    }
    if(!empty($_FILES['image-region']['name']))
    {
        $ancien_nom = $_POST['newpays'];
        $recherche_pays_id = $_POST['recup-id'];
        $recup = mysqli_query($bdd, 'SELECT idRegion FROM region WHERE region.libelleRegion = "'.$ancien_nom.'" AND region.idPays = "'.$recherche_pays_id.'";');
        $recup = mysqli_fetch_assoc($recup);
        $recup = $recup['idRegion'];
        $extension_upload = strtolower(  substr(  strrchr($_FILES['image-region']['name'], '.')  ,1)  );
            
        //Définition du nom et du chemin d'accès de l'image
        $nomphoto = "images/region/".$_FILES['image-region']['name'].".{$extension_upload}";                

        //Transfert du fichier uploadé par l'utilisateur du dossier temporaire à l'emplacement indiqué dans $nom
        $move = move_uploaded_file($_FILES['image-region']['tmp_name'],$nomphoto);
        mysqli_query($bdd, 'UPDATE `region` SET `drapeauRegion`= "'.$nomphoto.'"  WHERE idRegion = '.$recup.';');
    }

    if(!empty($_POST['suppression-region'])){
        $libelle = $_POST['suppression-region'];
        $recherche_pays_nom = $_POST['recup-nom'];
        $recherche_pays_id = $_POST['recup-id'];
        mysqli_query($bdd, 'DELETE FROM region WHERE region.libelleRegion = "'.$libelle.'" AND region.idPays = "'.$recherche_pays_id.'";');
    }

    if(empty($recherche_pays_id)){
        header('Location: admin.php');
    }

    $listregion = mysqli_query($bdd, 'SELECT libelleRegion FROM region INNER JOIN pays ON(region.idPays = pays.idPays) WHERE pays.idPays = "'.$recherche_pays_id.'";');
    if(mysqli_fetch_array($listregion, MYSQLI_ASSOC) == NULL){
        $region = 0;
        $regionNull = "-> Il n'y a aucune région dans ce pays !";
    }else{
        $region = 1;
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>
<head>
	<title><?php echo $nomSite; ?> - Modification des régions</title>
    <meta charset="utf-8">
    <?php require_once('styles.php'); ?>
</head>
<body class="bg-secondary">

    <!-- HEADER -->
    <?php require_once('header.php'); ?>

    <section class="container text-center mt-5 text-white principale">
        <div class="card text-center bg-dark">

            <div class="card-header">
                <h2>Pays : <u><?php echo $recherche_pays_nom; ?></u></h2>
            </div>

            <div class="card-body">
                <hr style="background-color: white;">

                <h4>Ajout d'une nouvelle région :</h4><br>

                <form action="modification-region.php" method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-10">

                            <div class="input-group-prepend">
                                <span class="input-group-text" id="ajoutnomregion">Nom de la région : </span>
                            <input type="text" class="form-control" aria-label="ajoutnomregion" aria-describedby="ajoutnomregion" name="ajoutnomregion">
                            </div>

                        </div>
                        
                        <div class="form-group col-md-2">
                            <input type="hidden" value="<?php echo $recherche_pays_id; ?>" name="recup-id">
                            <input type="hidden" value="<?php echo $recherche_pays_nom; ?>" name="recup-nom">
                            <input type="hidden" value="<?php echo $_POST['recherche-pays']; ?>" name="recherche-pays">
                            <input type="submit" value="Ajoutez" class="btn btn-outline-success">
                        </div>
                    </div>

                </form>



                <hr style="background-color: white;">

                <h4>Changement du nom de la région :</h4><br>

                <form action="" method="POST">
                    <div class="form-row">
                        <?php if($region != 0){ ?>

                            <div class="form-group col-md-5">

                                <div class="input-group mb-3">
                                    <select class="form-control" name="ancien-nom" id="region" required>
                                        <?php 
                                            $listregion = mysqli_query($bdd, 'SELECT libelleRegion FROM region INNER JOIN pays ON(region.idPays = pays.idPays) WHERE pays.idPays = "'.$recherche_pays_id.'";');
                                        
                                            foreach($listregion as $donnees){
                                                echo'<option value="'. $donnees['libelleRegion'] .'">'. utf8_encode($donnees['libelleRegion']) .'</option>';
                                            } 
                                        ?>
                                    </select>
                                </div>

                            </div>

                            <div class="form-group col-md-5">

                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="newnomregion">Nouveau nom : </span>
                                <input type="text" class="form-control" aria-label="newnomregion" aria-describedby="newnomregion" name="newnomregion">
                                </div>

                            </div>
                            
                            <div class="form-group col-md-2">
                                <input type="hidden" value="<?php echo $recherche_pays_id; ?>" name="recup-id">
                                <input type="hidden" value="<?php echo $recherche_pays_nom; ?>" name="recup-nom">
                                <input type="hidden" value="<?php echo $_POST['recherche-pays']; ?>" name="recherche-pays">
                                <input type="submit" value="Modifier" class="btn btn-outline-success">
                            </div>

                        <?php }else{
                                echo '<div class="form-group col-md-12">';
                                echo '<h5 class="badge badge-warning">'.$regionNull.'</h5>';
                                echo '</div>';
                            } ?>
                    </div>

                </form>
                
                <hr style="background-color: white;">

                <h4>Changement de drapeau region :</h4><br>

                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        
                        <?php if($region != 0){ ?>

                            <div class="form-group col-md-5">

                                <div class="input-group mb-3">
                                    <select class="form-control" name="newpays" id="pays" required>
                                        <?php 
                                        $listregion = mysqli_query($bdd, 'SELECT libelleRegion FROM region INNER JOIN pays ON(region.idPays = pays.idPays) WHERE pays.idPays = "'.$recherche_pays_id.'";');

                                        foreach($listregion as $donnees){
                                            echo'<option value="'. $donnees['libelleRegion'] .'">'. utf8_encode($donnees['libelleRegion']) .'</option>';
                                        } ?>
                                    </select>
                                </div>

                            </div>

                            <div class="form-group col-md-5">

                                <label><h6>Uploader une nouvelle image pour la région</h6>
                                    <input type="file" name="image-region"><br>
                                </label>

                                <input type="hidden" value="<?php echo $recherche_pays_id; ?>" name="recup-id">
                                <input type="hidden" value="<?php echo $recherche_pays_nom; ?>" name="recup-nom">
                                <input type="hidden" value="<?php echo $_POST['recherche-pays']; ?>" name="recherche-pays">

                            </div>
                            
                            <div class="form-group col-md-2">
                                
                                <input type="submit" value="Modifier" class="btn btn-outline-success">
                            </div>                        

                        <?php }else{
                                echo '<div class="form-group col-md-12">';
                                echo '<h5 class="badge badge-warning">'.$regionNull.'</h5>';
                                echo '</div>';
                            } ?>
                    </div>

                </form>

                <hr style="background-color: white;">

                <h4>Suppression d'une région :</h4><br>

                <form action="" method="POST">
                    <div class="form-row">
                    
                        <?php if($region != 0){ ?>

                            <div class="form-group col-md-10">

                                <div class="input-group mb-3">
                                    <select class="form-control" name="suppression-region" required>
                                        <?php 
                                        $listregion = mysqli_query($bdd, 'SELECT libelleRegion FROM region INNER JOIN pays ON(region.idPays = pays.idPays) WHERE pays.idPays = "'.$recherche_pays_id.'";');

                                        foreach($listregion as $donnees){
                                            echo'<option value="'. $donnees['libelleRegion'] .'">'. utf8_encode($donnees['libelleRegion']) .'</option>';
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-2">
                                <input type="hidden" value="<?php echo $recherche_pays_id; ?>" name="recup-id">
                                <input type="hidden" value="<?php echo $recherche_pays_nom; ?>" name="recup-nom">
                                <input type="hidden" value="<?php echo $_POST['recherche-pays']; ?>" name="recherche-pays">
                                <input type="submit" value="Supprimer" class="btn btn-outline-danger">
                            </div>

                        <?php }else{
                                echo '<div class="form-group col-md-12">';
                                echo '<h5 class="badge badge-warning">'.$regionNull.'</h5>';
                                echo '</div>';
                            } ?>
                    </div>

                </form>
                
                <hr style="background-color: white;">

                
                <div class="text-left">
                    <a href="admin.php" alt="Admin">
                        <button type="button" class="btn btn-light">Retour</button>
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