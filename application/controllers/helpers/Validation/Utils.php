<?php
class  Default_Controller_Helpers_Validation_Utils    
{

	
	
///////Fonction qui retourne un tableau de jours Paques/saint/ascension/pentacote
	   public function Paques_saint_ascension_pentacote($annee)
	{
   	 	$dimanche_paques = date("Y-m-d", easter_date($annee));
    	$vendredi_saint = date("Y-m-d", strtotime("$dimanche_paques - 2 day"));
		$lundi_paques = date("Y-m-d", strtotime("$dimanche_paques + 1 day"));
		$jeudi_ascension = date("Y-m-d", strtotime("$dimanche_paques + 39 day"));
    	$lundi_pentecote = date("Y-m-d", strtotime("$dimanche_paques + 50 day"));
  	    $tab_psap = array(0=>$dimanche_paques,1=>$vendredi_saint,2=>$lundi_paques,3=>$jeudi_ascension,4=>$lundi_pentecote);
  	    return $tab_psap;
	}
	
	
	
///////Function recupére les jours fériés maroc 
	   public function jours_feries_maroc($annee) 
	{   /*****/
    	$feris = new Default_Model_Ferie();       
		$jours_feries_maroc = $feris->RecupererLesJoursFeries($annee);  // Couplage fort 
		/*****/
		// retourne le tableau 
		return $jours_feries_maroc; 
	}
	
	
///////Indique si une date doit être normalisée ou non
	public function a_normaliser($date,$maroc) 
	{   
		/*****/
	    $utils = new Default_Controller_Helpers_Validation_Utils();   // couplage fort 
		/*****/
	    if (in_array(date('l',strtotime($date)),array('Saturday','Sunday')) || $utils->est_ferie($date,false,$maroc))
		{
			return true;
		}
		
		return false;
	}
	

///////On normalise un flag midi par rapport à une date non normalisée
	public function normaliser_flag_midi($date,$midi,$maroc) 
	{   /*****/
	    $utils = new Default_Controller_Helpers_Validation_Utils();    // couplage fort
	    /*****/
		// Si la date de début ou fin congé tombe un WE ou JF, le flag midi ne peut pas être actif
		if ($utils->a_normaliser($date,$maroc)) 
		{
			$midi = false;
		}
		
		return $midi;
	}

///////Si la date de début de congé tombe un WE ou JF, on l'avance au 1er JO
	public function normaliser_date_debut_conge($date,$maroc) 
	{   
		/*****/
		$utils = new Default_Controller_Helpers_Validation_Utils();   // couplage fort 
		/*****/
		while (in_array(date('l',strtotime($date)),array('Saturday','Sunday')) || $utils->est_ferie($date,false,$maroc))
		{
			$new_timestamp = strtotime("+1 day",strtotime($date));
		    $date = date('Y-m-d',$new_timestamp) ;	
		}	
		return $date;
	}
	
///////Si la date de fin de congé tombe un WE ou JF, on la retarde au dernier JO
	public function normaliser_date_fin_conge($date,$maroc=false) 
	{   /*****/
		$utils = new Default_Controller_Helpers_Validation_Utils();  // couplage fort 
		/*****/
		while (in_array(date('l',strtotime($date)),array('Saturday','Sunday')) || $utils->est_ferie($date,false,$maroc)) 
		{
			$new_timestamp = strtotime("-1 day",strtotime($date));
		    $date = date('Y-m-d',$new_timestamp) ;	
		}
		return $date;
	}	
	
	
	
/////// Fonction calcul les jours fériés Maroc / France 	
	   public function jours_feries($annee, $alsacemoselle, $maroc)
	  { 	
	    /*****/
	  	$utils = new Default_Controller_Helpers_Validation_Utils();
	  	$tab_psap = $utils->Paques_saint_ascension_pentacote($annee);   // Couplage fort 
	    $jours_f_maroc = $utils->jours_feries_maroc($annee); 
	    /*****/
	    
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
			return  $jours_f_maroc;  // appel de la fonction qui retourne les jours fériés maroc 
		}
	}
	   
///////Fonction test si un jours passé en argument est férié ou non 
	    function est_ferie($jour, $alsacemoselle, $maroc)
	    {    
			$jour = date("Y-m-d", strtotime($jour));
			$annee = substr($jour, 0, 4);
			/*****/
			$utils = new Default_Controller_Helpers_Validation_Utils();                     // Couplage fort 
			$tab_jours_feries = $utils-> jours_feries($annee, $alsacemoselle, $maroc);
			/*****/
			$tab_tmp = array();  // tab temporaire 
			
			if ($maroc) // maroc 
			{
			        for ($i = 0; $i < count($tab_jours_feries); $i++) 
			        {
			        	$tab_tmp[$i] = $tab_jours_feries[$i]['date_debut'];
			        }
	    
	    	   return in_array($jour,$tab_tmp);    // si je $jour existe soit dans les jours fériés maroc ou france 
			}
			else  // france 
			{
				return in_array($jour,$tab_jours_feries);    // si je $jour existe soit dans les jours fériés maroc ou france 
			}	
		}   	
		

///////Fonction qui calcul le nombre de jours entre deux dates 
function calcul_nombre_jours_conges($date_debut,$date_fin,$debut_midi,$fin_midi,$maroc) 
{
    $utils = new Default_Controller_Helpers_Validation_Utils();
    
	$nombre_jours_conges = 0;
	
	// Normaliser : commencer par les flag midi...
	$debut_midi = $utils->normaliser_flag_midi($date_debut,$debut_midi,$maroc);
	$fin_midi = $utils->normaliser_flag_midi($date_fin,$fin_midi,$maroc);
	//... terminer par les dates
	$date_debut = $utils->normaliser_date_debut_conge($date_debut,$maroc);
	$date_fin = $utils->normaliser_date_fin_conge($date_fin,$maroc);
	
	// Parcourir l'intervalle
	$date_iterator = $date_debut;
	while ($date_iterator <= $date_fin) 
	{
		
		// Loguer les jours ouvrés (tous les jours sauf les samedi, dimanche, fériés)
		$weekday =  date('l',strtotime($date_iterator));
		if (!in_array($weekday,array('Saturday','Sunday')) && !$utils->est_ferie($date_iterator,false,$maroc)) 
		{
			$nombre_jours_conges++;
	    }	
	    else 
	   {

			// gestion des exceptions !!!!." WE ou férié, non décompté dans les congés");
	   }
		
		// Incrémenter l'iterator
		$new_timestamp = strtotime("+1 day",strtotime($date_iterator));
	    $date_iterator = date('Y-m-d',$new_timestamp) ;
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