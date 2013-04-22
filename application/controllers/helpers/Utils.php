<?php
class  Default_Controller_Helpers_Utils    
{

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
		if (in_array(date_format($date, 'l'),array('Saturday','Sunday')) || $this->est_ferie(date_format($date, 'Y-m-d'),false,$maroc)) 
		{
			return true;
		}
		
		return false;
	}
	

///////On normalise un flag midi par rapport à une date non normalisée
	public function normaliser_flag_midi($date,$midi,$maroc) 
	{   
		// Si la date de début ou fin congé tombe un WE ou JF, le flag midi ne peut pas être actif
		if (a_normaliser($date,$maroc)) 
		{
			$midi = false;
		}
		
		return $midi;
	}


///////Si la date de début de congé tombe un WE ou JF, on l'avance au 1er JO
	public function normaliser_date_debut_conge($date,$maroc)   
	{ 
		while (in_array(date_format($date, 'l'),array('Saturday','Sunday')) || $this->est_ferie(date_format($date, 'Y-m-d'),false,$maroc)) 
		{
			$date->add(new DateInterval("P1D"));
		}
		return $date;
	}


///////Si la date de fin de congé tombe un WE ou JF, on la retarde au dernier JO
	public function normaliser_date_fin_conge($date,$maroc=false) 
	{  
		while (in_array(date_format($date, 'l'),array('Saturday','Sunday')) || $this->est_ferie(date_format($date, 'Y-m-d'),false,$maroc)) 
		{
			$date->sub(new DateInterval("P1D"));
		}
		return $date;
	}	
	
/////// Fonction calcul les jours fériés Maroc / France 	
	   public function jours_feries($annee, $alsacemoselle, $maroc)
	  { 	
        $dimanche_paques = date("Y-m-d", easter_date($annee));
		if (!$maroc)   // si France 
	   	{    
			$jours_feries = array
			(    $dimanche_paques  //  $dimanche_paques
			,    date("Y-m-d", strtotime("$dimanche_paques + 1 day"))  //  $lundi_paques($annee)
			,    date("Y-m-d", strtotime("$dimanche_paques + 39 day"))  //  $jeudi_ascension($annee)
			,    date("Y-m-d", strtotime("$dimanche_paques + 50 day"))   //  $lundi_pentecote
			
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
				$jours_feries[] = date("Y-m-d", strtotime("$dimanche_paques - 2 day")); // $vendredi_saint
			}
			sort($jours_feries);
			return $jours_feries; // retourné les jours fériés france 
		}
		else         // si Maroc 
		{
			return  $this->jours_feries_maroc($annee);  // appel de la fonction qui retourne les jours fériés maroc 
		}
	}
	   
///////Fonction test si un jours passé en argument est férié ou non 
	    function est_ferie($jour, $alsacemoselle, $maroc)
	    {    
			$jour = date("Y-m-d", strtotime($jour));
			$annee = substr($jour, 0, 4);
			/*****/
			$tab_jours_feries = $this->jours_feries($annee, $alsacemoselle, $maroc);
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
	$nombre_jours_conges = 0;
	
	// Normaliser : commencer par les flag midi...
	$debut_midi = $this->normaliser_flag_midi($date_debut,$debut_midi,$maroc);
	$fin_midi = $this->normaliser_flag_midi($date_fin,$fin_midi,$maroc);
	//... terminer par les dates
	$date_debut = $this->normaliser_date_debut_conge($date_debut,$maroc);
	$date_fin = $this->normaliser_date_fin_conge($date_fin,$maroc);
	
	// Parcourir l'intervalle
	$date_iterator = $date_debut;
	
	
	while ($date_iterator <= $date_fin) 
	{
		
		// Loguer les jours ouvrés (tous les jours sauf les samedi, dimanche, fériés)
		$weekday =  date('l',strtotime($date_iterator));
		if (!in_array($weekday,array('Saturday','Sunday')) && !$this->est_ferie($date_iterator,false,$maroc)) 
		{
			$nombre_jours_conges++;
	    }	
	    else 
	   {

			// gestion des exceptions !!!!." WE ou férié, non décompté dans les congés");
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