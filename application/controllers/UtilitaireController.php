<?php
class UtilitaireController extends Zend_Controller_Action
{
	

//fonction qui retourne un tableau de jours Paques/saint/ascension/pentacote
function Paques_saint_ascension_pentacote($annee)
{
    $dimanche_paques = date("Y-m-d", easter_date($annee));
    $vendredi_saint = date("Y-m-d", strtotime("$dimanche_paques - 2 day"));
	$lundi_paques = date("Y-m-d", strtotime("$dimanche_paques + 1 day"));
	$jeudi_ascension = date("Y-m-d", strtotime("$dimanche_paques + 39 day"));
    $lundi_pentecote = date("Y-m-d", strtotime("$dimanche_paques + 50 day"));
    $tab_psap = array('$dimanche_paques','$vendredi_saint','$lundi_paques','$jeudi_ascension','$lundi_pentecote');
	return $tab_psap;
}
	

// function jours fériés maroc          // A NOTER QUE L'APPEL A  RecupererLesJoursFeries($annee);
                                        // DOIT SE FAIRE 1 FOIS 
function jours_feries_maroc($annee) 
{
    $feris = new Default_Model_Ferie();       
	$jours_feries_maroc = $feris->RecupererLesJoursFeries($annee); //
	// retourne le tableau 
	return $jours_feries_maroc; 
}

// Fonction retourne les jours fériés 
function jours_feries($annee, $alsacemoselle=false, $maroc=false)
{
    $tab_psap = Paques_saint_ascension_pentacote($annee); 
    
	if (!$maroc)   // si France 
   	{    
		$jours_feries = array
		(    $tab_psap[0]   //  $dimanche_paques
		,    $tab_psap[2]   //  $lundi_paques($annee)
		,    $tab_psap[3]   //  $jeudi_ascension($annee)
		,    $tab_psap[4]   //  $lundi_pentecote
		
		,    "$annee-01-01"        //    Nouvel an
		,    "$annee-05-01"        //    Fête du travail
		,    "$annee-05-08"        //    Armistice 1945
		,    "$annee-05-15"        //    Assomption
		,    "$annee-07-14"        //    Fête nationale
		,    "$annee-11-11"        //    Armistice 1918
		,    "$annee-11-01"        //    Toussaint
		,    "$annee-12-25"        //    Noël
		);
		if($alsacemoselle)
		{
			$jours_feries[] = "$annee-12-26";
			$jours_feries[] = $tab_psap[1];
		}
		sort($jours_feries);
		return $jours_feries; // retourné les jours fériés france 
	}
	else         // si Maroc 
	{
		return jours_feries_maroc($annee);  // appel de la fonction qui retourne les jours fériés maroc 
	}
}




// fonction test si le jour passé en argument est férié ou pas 
function est_ferie($jour, $alsacemoselle=false, $maroc=false)
{
	$jour = date("Y-m-d", strtotime($jour));
	$annee = substr($jour, 0, 4);
	
	return in_array($jour, jours_feries($annee, $alsacemoselle, $maroc));
}

// Indique si une date doit être normalisée ou non
function a_normaliser($date,$maroc=false) 
{
	global $logger;
	
	if (in_array(date_format($date, 'l'),array('Saturday','Sunday')) || est_ferie(date_format($date, 'Y-m-d'),false,$maroc)) 
	{
		return true;
	}
	return false;
}


// On normalise un flag midi par rapport à une date non normalisée
function normaliser_flag_midi($date,$midi,$maroc=false) 
{
	global $logger;
	
	// Si la date de début ou fin congé tombe un WE ou JF, le flag midi ne peut pas être actif
	if (a_normaliser($date,$maroc)) 
	{
		$midi = false;
	}
	
	return $midi;
}


// Si la date de début de congé tombe un WE ou JF, on l'avance au 1er JO
function normaliser_date_debut_conge($date,$maroc=false) 
{
	global $logger;
	$logger->debug(date_format($date, 'l d F Y'));
	
	while (in_array(date_format($date, 'l'),array('Saturday','Sunday')) || est_ferie(date_format($date, 'Y-m-d'),false,$maroc)) 
	{
		$date->add(new DateInterval("P1D"));
	}
	
	$logger->debug(date_format($date, 'l d F Y'));
	
	return $date;
}


// Si la date de fin de congé tombe un WE ou JF, on la retarde au dernier JO
function normaliser_date_fin_conge($date,$maroc=false) 
{
	global $logger;
	$logger->debug(date_format($date, 'l d F Y'));
	
	while (in_array(date_format($date, 'l'),array('Saturday','Sunday')) || est_ferie(date_format($date, 'Y-m-d'),false,$maroc)) 
	{
		$date->sub(new DateInterval("P1D"));
	}
	
	$logger->debug(date_format($date, 'l d F Y'));
	
	return $date;
}


function calcul_nombre_jours_conges($date_debut,$date_fin,$debut_midi=false,$fin_midi=false,$maroc=false) {
	global $logger;
	$nombre_jours_conges = 0;
	
	// Normaliser : commencer par les flag midi...
	$debut_midi = normaliser_flag_midi($date_debut,$debut_midi,$maroc);
	$fin_midi = normaliser_flag_midi($date_fin,$fin_midi,$maroc);
	//... terminer par les dates
	$date_debut = normaliser_date_debut_conge($date_debut,$maroc);
	$date_fin = normaliser_date_fin_conge($date_fin,$maroc);
	
	// Parcourir l'intervalle
	$date_iterator = $date_debut;
	while ($date_iterator <= $date_fin) {
		
		// Loguer les jours ouvrés (tous les jours sauf les samedi, dimanche, fériés)
		$weekday = date_format($date_iterator, 'l');
		if (!in_array($weekday,array('Saturday','Sunday'))  && !est_ferie(date_format($date_iterator, 'Y-m-d'),false,$maroc)) {
			$logger->debug(date_format($date_iterator, 'l d F Y'));
			$nombre_jours_conges++;
		}
		else 
		{
			$logger->debug(date_format($date_iterator, 'l d F Y')." WE ou férié, non décompté dans les congés");
		}
		
		// Incrémenter l'iterator
		$date_iterator->add(new DateInterval("P1D"));
	}
	
	// Traiter les demi journées
	if ($debut_midi) 
	{
		$nombre_jours_conges = $nombre_jours_conges - 0.5;
	}
	if ($fin_midi) 
	{
		$nombre_jours_conges = $nombre_jours_conges - 0.5;
	}
	
	return $nombre_jours_conges;
}

}