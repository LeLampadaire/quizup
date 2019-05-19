<?php session_start();
	if(!isset($_SESSION['idprofil']) || empty($_SESSION['idprofil']))
	{
		header('Location: themes.php?erreur=1');
	}
	else
	{

		/****Création de PARTIE et INTEGRER et PARTICIPER****/
		require_once('baseDeDonnee.php');
		$_SESSION['compteur'] = 0;




		//Insertion d'une ligne PARTIE
		mysqli_query($bdd, 'INSERT INTO `partie` (`idPartie`, `timestampPartie`) VALUES (NULL, SYSDATE());');
		$_SESSION['idpartie'] = mysqli_insert_id($bdd);
		$idpartie = $_SESSION['idpartie'];




		//Insertion d'une ligne PARTICIPER
		$idprofil = $_SESSION['idprofil'];
		$jours = $_SESSION['jours'];
		$bonus = 1;
		$i = 2;
		while ($i <= 6)//Initialisation du BONUSBOOST 
		{
			if($jours >= $i)
			{
				$bonus += 0.2;
			}
			$i++;
		}
		$_SESSION['bonus'] = $bonus;
		mysqli_query($bdd, 'INSERT INTO `participer` (`idProfil`, `idPartie`, `bonusBoost`) VALUES ('.$idprofil.', '.$idpartie.', '.$bonus.');');





		//Insertion d'une ligne INTEGRER et selection des questions de la partie
		$ordre_rep_tab  = array('1' => "1234" ,'2' => "1243" ,'3' => "1342" ,'4' => "1324" ,'5' => "1423" ,'6' => "1432" ,'7' => "2134" ,'8' => "2143" ,'9' => "2341" ,'10' => "2314" ,'11' => "2431" ,'12' => "2413" ,'13' => "3124" ,'14' => "3142" ,'15' => "3241" ,'16' => "3214" ,'17' => "3421" ,'18' => "3412" ,'19' => "4123" ,'20' => "4132" ,'21' => "4213" ,'22' => "4231" ,'23' => "4312" ,'24' => "4321" );
		$ordre;
		$theme = (int)$_GET['idtheme'];
		$id_question = array('1' => 0 ,'2' => 0 ,'3' => 0 ,'4' => 0 ,'5' => 0 ,'6' => 0 ,'7' => 0 );
		$question = mysqli_query($bdd,'SELECT idQuestion FROM question WHERE idTheme = '.$theme.';');
		$nb_question = mysqli_num_rows($question);
		$choix_question = rand(0, $nb_question);
		$liste_choix  = array();
		
		foreach($id_question as $key => $value) 
		{
			$j = rand(1, 24);
			$ordre = $ordre_rep_tab[$j];
			$choix_question = rand(0, $nb_question-1);
			while(in_array($choix_question, $liste_choix)) 
			{
				$choix_question = rand(0, $nb_question-1);
			}

			$liste_choix[$key] = $choix_question;
			mysqli_data_seek($question, $choix_question);
			$id_question[$key] = mysqli_fetch_array($question, MYSQLI_ASSOC);
			$numero = $id_question[$key]['idQuestion'];
			mysqli_query($bdd,'INSERT INTO `integrer` (`idPartie`, `idQuestion`, `numero`, `ordreReponses`) VALUES ('.$idpartie.','.$numero.','.$key.','.$ordre.');');
		}
		
		mysqli_close($bdd);
		header('Location: partie.php');
	}
?>