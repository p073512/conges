<?php
   class Default_Controller_Helpers_outils extends Zend_Controller_Action_Helper_Abstract
  {
  	//
	public $joursFerie = array();
	
	
    function dimanche_paques($annee)
	{
		return date("Y-m-d", easter_date($annee));
	}
	function vendredi_saint($annee)
	{
		$dimanche_paques = $this->dimanche_paques($annee);
		return date("Y-m-d", strtotime("$dimanche_paques -2 day"));
	}
	function lundi_paques($annee)
	{
		$dimanche_paques = $this->dimanche_paques($annee);
		return date("Y-m-d", strtotime("$dimanche_paques +1 day"));
	}
	function jeudi_ascension($annee)
	{
		$dimanche_paques = $this->dimanche_paques($annee);
		return date("Y-m-d", strtotime("$dimanche_paques +39 day"));
	}
	function lundi_pentecote($annee)
	{
		$dimanche_paques = $this->dimanche_paques($annee);
		return date("Y-m-d", strtotime("$dimanche_paques +50 day"));
	}
	
	
	public function isWeekend($date)
	{
		return (date('N',strtotime($date))>=6);
	}
	
	
	public function setJoursFerie($annee,$cs = false,$alsacemoselle = false)
	{
		
		if (!$cs) {
			$this->joursFerie = array('France' => array(
		    "$annee-01-01"=> 'Nouvel an'      //    Nouvel an
			,$this->dimanche_paques($annee) => 'Dimanche Pâques'
			,$this->lundi_paques($annee) => 'Lundi Pâques'
			,$this->jeudi_ascension($annee) => 'Jeudi ascension'
			,$this->lundi_pentecote($annee) => 'Lundi pentecote'
            ,"$annee-05-01" => 'Fête du travail'      //    Fête du travail
			,"$annee-05-08" =>'Armistice 1945'       //    Armistice 1945
			,"$annee-07-14" =>'Fête nationale'      //    Fête nationale
			,"$annee-08-15" =>'Assomption'      //    Assomption
			,"$annee-11-11" => 'Armistice 1918'       //    Armistice 1918
			,"$annee-11-01" => 'Toussaint'       //    Toussaint
			,"$annee-12-25" => 'Noël'       //    Noël
			));
			if($alsacemoselle)
			{
				$this->joursFerie['France']["$annee-12-26"] = 'alsace Moselle';
				$this->joursFerie['France'][$this->vendredi_saint($annee)] = 'Vendredi Saint';
			}
			
			return $this; // retourne tableau de jours fériés français
		}
		else {
	
		$ferie = new Default_Model_Ferie();
		$jours_feries_csm = $ferie->fetchAll("annee_reference = '".$annee."'");
		
		
		foreach ($jours_feries_csm as $j) 
		{
			$this->joursFerie['CSM'][$j->getDate_debut()] = $j->getLibelle();
		
		}

		return $this; // retourne tableau de jours fériés marocain
	       }
			
		}
	// fin setJoursFerie
	
	/**
	 * @desc : La fonction getPeriodeDetails retourne un tableau associatif contenant
	 *		   tous les jours de la période ,renseignée en paramétre, avec leur type : 
	 *          -N : jour ordinaire (ni Férié ni Weekend)
	 *          -F : jour férié (selon le $cs indiqué en paramétre sinon jours férié�s Français)
	 *          -WE : les weekends sur la période donnée.
	 *          -DM : si une période de congé , et le congé démarre à midi.
	 * 			-FM : si une période de congé , et le congé prend fin à midi ./.
	 * 
	 * 
	 * 
	 * @param :
	 *        (String) $annee  : année référence;
	 * 		  (String)$dateDebut : date debut Période/congé
	 * 		  (String)$dateFin : date fin Période/congé
	 * 		  (bool) $dm [optionnel]: début Midi en cas de saise de période de congé
	 * 		  (bool) $fm [optionnel]: Fin Midi
	 * 		  (bool) $cs [optionnel] : $cs à true pour le csm , à false pour la France
	 *		  (bool) $alsacemoselle ;
	 * 
	 * 
	 * 
	 * 
	 * @return : (Array) $conge : $conge[date] = typeJour
	 */
	
	public function getPeriodeDetails($annee,$dateDebut,$dateFin,$dm=false,$fm=false,$cs=false,$alsacemoselle = false)
	{
		
		$conge = array();
		
		$dateTimeFin = new DateTime($dateFin);
		$dateTimeFin->add(new DateInterval('P1D'));
		
		// calcul de la période , et subdivision en jours .
		$period = new DatePeriod(new datetime($dateDebut),new DateInterval('P1D'),$dateTimeFin);
	
		// récupération des jours fériés sur l'année référence.
		$jFerie =  $this->setJoursFerie($annee,$cs,$alsacemoselle);
        $jFerie =(array) $jFerie;
        
		if($cs == false)
		{
			$iCs = 'France'; // indice cs france
		}
		else 
		{
			$iCs = 'CSM'; // indice csm
		}
		
        //récupération des jours de la période.
			  foreach ($period as $k=>$date)
			  {
			  	//formatage de la date 
				$dDate = $date->format('Y-m-d');
				
				if($this->isWeekend($dDate) )
				{
					$conge[$dDate]['TypeJour'] ='WE';
					
				}
				// vérif si le date est fériée
			    elseif(isset($jFerie['joursFerie'][$iCs][$dDate]))
			    {
			    	$conge[$dDate]['TypeJour'] ='F';
					$conge[$dDate]['LibelleFerie'] = $jFerie['joursFerie'][$iCs][$dDate];
			    }
				else
				{
					$conge[$dDate]['TypeJour'] = 'N';
					
				}
			    
			     $conge[$k]['Date'] = $dDate;
		   	}
		   //debut midi et fin midi pour la période de congé.
	       $conge['0']['DebutMidi'] = $dm;
	       $fin = (count($conge) -2)/2;
	       $conge[$fin]['FinMidi'] = $fm;
		   
		return $conge;
	
  }
	/**
	 * 
	 * Fonction qui prend en paramètre le tableau de détails période et retourne 
	 * les compteurs jours normaux(N),weekends(WE),Fériés(FE) dans un tableau.
	 * @param (Array) $conge[]
	 * @return (Array) $nombreJour[]
	 */
	public function calculNombreJourConge($conge)
	{     
		  // initialisation des compteurs
		  $n = $f = $we = $cpt =  0;
		  // dernier indice du tableau congé
		  $fin = (count($conge) -2)/2;
			
			$dateDebut = $conge['0']['Date'];
			$dateFin = $conge[$fin]['Date'];

			// parcourir le tableau de la période de congé
			foreach ($conge as $k=>$v)
			  {
			  	// compteur jour 
			    
			    if(isset($conge[$k]['Date']))
			    {$cpt++;
			    	 $date = $conge[$k]['Date'];
			    
				  	if($conge[$date]['TypeJour'] == 'N')
						$n++;
					elseif($conge[$date]['TypeJour'] == 'F')
						$f++;
					elseif($conge[$date]['TypeJour'] == 'WE')
						$we++;
			    }
			   
			
			  }
			  
		   if($conge['0']['DebutMidi'] == true && $conge[$dateDebut]['TypeJour'] != 'F' && $conge[$dateDebut]['TypeJour'] != 'WE')
		   $dm = 0.5;
		   else {
		       $dm = 0; // si date debut == we ou == f
		   }
	
		   if($conge[$fin]['FinMidi'] == true && $conge[$dateFin]['TypeJour'] != 'F' && $conge[$dateFin]['TypeJour'] != 'WE')
		   $fm = 0.5;
		   else {
		       $fm = 0; // si date fin == we ou == f
		   }
		
		  $nbrej = $n -($dm + $fm); 
		
		  $nombreJour = array('Total' => $cpt-($dm + $fm),
		                      'Fériés' => $f,
							  'Weenkends'=> $we,
							  'Nombre Jours congés' => $nbrej)	;
	    return $nombreJour;
	}


 }
 
  