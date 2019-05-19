<?php 

    $bdd = mysqli_connect("185.157.246.42:3306", "quizup", "Ex6v1z6~", "quizup");

    if(!$bdd){
        echo "<div class='container'><div class='alert alert-danger' role='alert'>";
        echo "Erreur connexion SQL !";
        echo "</div></div>";
    }

?>