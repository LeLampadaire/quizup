<?php 

	// Nom du site
	$nomSite = "QuizUp";

	//REGEX
	$pattern_pseudo = "#([[:alnum:]éèêëÉËÈ_@]){3,50}#";
	$pattern_ville = "#(([[:alpha:]]){3,50})|(^$)#";
	$pattern_region = "#([[:alpha:]]){3,50}#";
	$pattern_email = "#^([A-Za-z0-9._éèêëÉËÈ-])+@([A-Za-z0-9._-]){2,15}\.([A-Za-z]){2,4}$#";
	$pattern_mdp = "#([[:alnum:]éèêëÉËÈ_@]){3,50}#";

	//REGEX Ajout_Themes
	$pattern_libelle = "#([[:alnum:]éèêëÉËÈ_@]){3,50}#";
?>