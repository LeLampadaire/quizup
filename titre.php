<?php session_start() ; ?>
<!DOCTYPE html>
<html lang="fr">
    <?php
      require_once('configuration.php');
      require_once('baseDeDonnee.php');

    $titreTheme = mysqli_query($bdd, 'SELECT titre.idTitre as idtitremodif, titre.libelleTitre, titre.niveauRequis FROM titre WHERE '.$_GET['idtheme'].' = titre.idTheme ORDER BY libelleTitre ASC;');

    $theme_class = "active";

    if($_SESSION['idprofil'] == NULL){
      header('Location: 404.php');
    }
    ?>

<head>
	<title><?php echo $nomSite; ?> - Titres</title>
	<meta charset="utf-8">
  	<?php require_once('styles.php'); ?>
</head>
<body class="bg-secondary">
	<!-- HEADER -->
    <?php require_once('header.php'); ?>

    <!-- Contenu principale -->
    <section class="container text-center mt-5 text-white principale">
      <div class="card text-center bg-dark">
        <div class="card-header">
          <h2>Titres</h2>
        </div>
        <div class="card-body">
          <?php 
            if(isset($_GET['erreur']) && $_GET['erreur'] == 1){
              echo '<div class="alert alert-danger" role="alert">';
              echo 'Ce thème ne peut pas être supprimé car il possède au-moins une question !';
              echo '</div>';
            }
            $count = 0;
            while($recup = mysqli_fetch_array($titreTheme, MYSQLI_ASSOC)){
              if($count != 0){
                echo '<hr>';
              }
              $nomQuestion = str_replace(" ","_",$recup['libelleTitre']);
              $nomQuestion = str_replace(".","_",$nomQuestion);
              $nomQuestion = str_replace("?","_",$nomQuestion);
              $nomQuestion = str_replace("'","_",$nomQuestion);
              $nomQuestion = str_replace("!","_",$nomQuestion);
              $nomQuestion = str_replace("&","_",$nomQuestion);
              $nomQuestion = str_replace("@","_",$nomQuestion);
              $nomQuestion = str_replace("#","_",$nomQuestion);
              $nomQuestion = str_replace("$","_",$nomQuestion);
              $nomQuestion = str_replace("%","_",$nomQuestion);
              $nomQuestion = str_replace("+","_",$nomQuestion);
              ?>

              <!-- Button trigger modal -->
              <button type="button" class="btn btn-outline-light" data-toggle="modal" data-target="#-<?php echo $nomQuestion; ?>-">
                  <?php echo $recup['libelleTitre']; ?>
              </button>

              <!-- Modal -->
              <div style="color: black;" class="modal fade" id="-<?php echo $nomQuestion; ?>-" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel"><?php echo $recup['libelleTitre']; ?></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <?php
                          echo 'Niveau requis : ' . $recup['niveauRequis'] . '<br>';
                      ?>
                  </div>
                  <div class="modal-footer">
                      <?php
                          echo '<a href="mise_a_jour_Titre.php?idtitre='.$recup['idtitremodif'].'"><button  class="btn btn-dark float-right">'."Modifier un titre".'</button></a>';
                          echo '<a href="menu_themes.php?idtheme='.$_GET['idtheme'].'"><button  class="btn btn-dark float-right">'."Retour".'</button></a>';
                      ?>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                  </div>
                  </div>
              </div>
              </div>
            <?php
              $count++;
          }
            ?>
        </div>

        <div class="card-footer">
          <?php
            echo '<a href="ajouter_titre.php?idtheme='.$_GET['idtheme'].'"><button  class="btn btn-dark float-right">'."Ajouter un titre".'</button></a>';
            echo '<a href="menu_themes.php?idtheme='.$_GET['idtheme'].'"><button  class="btn btn-dark float-left">'."Retour".'</button></a>';
          ?>
        </div>
    </section>

    <!-- Footer -->
    <?php require_once('footer.php'); 
          mysqli_close($bdd);
    ?>
</body>
</html>