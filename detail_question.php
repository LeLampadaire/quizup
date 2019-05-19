<?php session_start() ; 
    if(!isset($_GET['idpartie']) || empty($_GET['idpartie']))
    {
      $idprofil = $_SESSION['idprofil'];
      $idpartie = $_SESSION['idpartie'];
    }
    else
    {
      $idprofil = $_GET['idprofil'];
      $idpartie = $_GET['idpartie'];
    }
    
    $num_question = (int)$_GET['numero'];
    require_once('baseDeDonnee.php'); 

    $question = mysqli_query($bdd,'SELECT question.libelleQuestion, question.answer, question.distracteur01, question.distracteur02, question.distracteur03, question.Illustration, integrer.ordreReponses, repondre.reponse FROM question, integrer, repondre WHERE question.idQuestion = integrer.idQuestion AND question.idQuestion = repondre.idQuestion AND integrer.idPartie = repondre.idPartie AND integrer.numero = '.$num_question.' AND repondre.idProfil = '.$idprofil.' AND integrer.idPartie = '.$idpartie.';');
    $question = mysqli_fetch_array($question, MYSQLI_ASSOC);

    $donnee_question = $question['libelleQuestion'];
    $donnee_reponse = $question['answer'];
    $donnee_distract1 = $question['distracteur01'];
    $donnee_distract2 = $question['distracteur02'];
    $donnee_distract3 = $question['distracteur03'];
    $donnee_illustration = $question['Illustration'];
    $ordre_reponse = (int)$question['ordreReponses']; 
    $reponse_ut = $question['reponse'];

    $style1 = "btn btn-secondary rounded btn-outline-warning text-white";
    $style2 = "btn btn-secondary rounded btn-outline-warning text-white";
    $style3 = "btn btn-secondary rounded btn-outline-warning text-white";
    $style4 = "btn btn-secondary rounded btn-outline-warning text-white";

    switch ($reponse_ut) 
    {
      case '1':
          $style1 = "btn rounded btn-success";
        break;
      
        case '2':
          $style2 = "btn rounded btn-danger";
        break;

        case '3':
         $style3 = "btn rounded btn-danger";
        break;

        case '4':
         $style4 = "btn rounded btn-danger";
        break;
    }

    

      $rep2 = '
            <button type="button" class=" '.$style2.' ">'.utf8_encode($donnee_distract1).'</button>
        <br>';
        


        $rep3 = '
            <button  type="button" class=" '.$style3.'">' .utf8_encode($donnee_distract2).'</button>
        <br>';
        


        $rep4 = '
          <button  type="button" class=" '.$style4.'">'.utf8_encode($donnee_distract3).'</button>
        <br>';
        


        $rep1 = '
          <button  type="button" class=" border-success '.$style1.'">'.utf8_encode($donnee_reponse).'</button>
        <br>';
?>
<!DOCTYPE html>
<html lang="fr">
    <?php require_once('configuration.php'); ?>

<head>
	<title><?php echo $nomSite; ?> - Thèmes</title>
	<meta charset="utf-8">
  	<?php require_once('styles.php'); ?>
    <style type="text/css">  .border-success{
    border-width:5px !important;
}</style>
</head>
<body class="bg-secondary">
  <div>
    
    <!-- HEADER -->
    <?php require_once('header.php'); ?>
    <!-- Contenu principale -->
    <section class="container text-center mt-5 text-white principale">
      <!-- Illustration de la question (peut être NULL) -->
      <div class="card text-center bg-dark">
        <div class="card-header">
      <?php 
      if($donnee_illustration != NULL)
      {
        echo '<div class="container">';
        echo '<figure class="figure">';
        echo '<img class="figure-img rounded" style="max-width:400px;" src="'.$donnee_illustration.'" alt="Responsive image">';
        echo "</figure>";
        echo "</div>";
      }
      ?>
      

      <!-- Question méthode get peut-être ? -->
      
        <?php
          echo '<h3>'.utf8_encode($donnee_question).'</h3>';
            echo "<br>";
        ?>
        </div>
      
      <div class="card-body">
      <form method="POST" action="repondre.php">
        <div class=" btn-group btn-group-toggle btn-group-vertical" data-toggle="buttons">

    <?php
        $t1 = time();

        $ordre4 = $ordre_reponse%10;
        
        $ordre3 = ($ordre_reponse/10)%10;
        
        $ordre2 = ($ordre_reponse/100)%10;
        
        $ordre1 = ($ordre_reponse/1000)%10;

        $ordre = array($ordre1 => $rep1 ,$ordre2 => $rep2 ,$ordre3 => $rep3 ,$ordre4 => $rep4 );
        $i = 1; 
        
        while ($i <= 4) 
        {
          echo $ordre[$i];
          $i++;
        }
    ?>
        </div>
        <br>

        <input type="hidden" name="t1" value=<?php echo $t1; ?> >
      </form>
      </div>
        <div class="card-footer">
          <?php echo'<a href="resultat.php?idpartie='.$idpartie.'&idprofil='.$idprofil.'"'; ?>><button type="button" class="btn btn-dark">Revenir au résultat</button></a>   
        </div>
      </div>
    </section>
    <!-- Footer -->
    <?php require_once('footer.php'); ?>
</body>
</html>