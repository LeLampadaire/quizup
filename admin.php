<?php session_start();
    
	require_once('baseDeDonnee.php');

	$Pseudo = $_SESSION['nomprofil'];
	$idPseudo = $_SESSION['idprofil'];

	if($idPseudo != 4){
		header('Location: index.php');
    }

?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>
<head>
	<title><?php echo $nomSite; ?> - Admin</title>
    <meta charset="utf-8">
    <?php require_once('styles.php'); ?>
</head>
<body class="bg-secondary">

    <!-- HEADER -->
    <?php require_once('header.php'); ?>

    <section class="container text-center mt-5 text-white principale">
        <div class="card text-center bg-dark">

            <div class="card-header">
                <h2>Admin</h2>
            </div>

            <div class="card-body">
                
                <form action="modification-region.php" method="POST">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Pays</span>
                                </div>

                                <select class="form-control" name="recherche-pays" required>
                                    <?php 
                                    $listpays = mysqli_query($bdd,'SELECT idPays, libellePays FROM pays');

                                    foreach($listpays as $donnees){
                                        echo'<option value="'.utf8_encode($donnees['libellePays']).' '.$donnees['idPays'].'">'. utf8_encode($donnees['libellePays']) .'</option>';
                                    } ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-outline-light">Modification</button>
                        </div>
                    </div>

                </form>

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