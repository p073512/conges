<?php
   class Default_Controller_Helpers_outils extends Zend_Controller_Action_Helper_Abstract
  {
        
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
		
		
  		function est_ferie($jour, $alsacemoselle=false, $maroc=false)
		{
		    $jour = date("Y-m-d", strtotime($jour));
		    $annee = substr($jour, 0, 4);
		
		    try 
		    {
		    	$s = in_array($jour, $this->jours_feries($annee, $alsacemoselle, $maroc));
		    } 
		    catch (Exception $e) 
		    {
		    		   
		    }
		    return $s;
		}
		
		function jours_feries($annee, $alsacemoselle=false, $maroc=false)
	   {
		if (!$maroc) 
		{
			$jours_feries = array
			(    $this->dimanche_paques($annee)
			,    $this->lundi_paques($annee)
			,    $this->jeudi_ascension($annee)
			,    $this->lundi_pentecote($annee)

			,    "$annee-01-01"        //    Nouvel an
			,    "$annee-05-01"        //    Féte du travail
			,    "$annee-05-08"        //    Armistice 1945
			,    "$annee-08-15"        //    Assomption
			,    "$annee-07-14"        //    Féte nationale
			,    "$annee-11-11"        //    Armistice 1918
			,    "$annee-11-01"        //    Toussaint
			,    "$annee-12-25"        //    Noél
			);
			
			if($alsacemoselle)
			{
				$jours_feries[] = "$annee-12-26";
				$jours_feries[] = $this->vendredi_saint($annee);
			}
			sort($jours_feries);
			return $jours_feries;
		}
		else {
			return $this->jours_feries_maroc($annee);
		}
	}
	
	
	
  	function jours_feries_maroc($annee) 
	{
		//global $logger;
		////$logger->debug("appel en base");
        $jours_feries_csm_dates = null;
		$logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('php://output');
		$logger->addWriter($writer);

		$ferie = new Default_Model_Ferie();
		$jours_feries_csm = $ferie->fetchAll("annee_reference = '".$annee."'");
		foreach ($jours_feries_csm as $j) 
		{
			$jours_feries_csm_dates[] = $j->getDate_debut();
		}
        
		if($jours_feries_csm_dates == null)
			return array();
		else
			return $jours_feries_csm_dates;
	}
		
	
	
	
		public function setJoursFerie($annee,$maroc = false,$alsacemoselle = false)
		{
			
			if (!$maroc) 
			{
				$this->joursFerie = array('France' => array(
				
			    "$annee-01-01"=> 'Nouvel an'      //    Nouvel an
				,$this->dimanche_paques($annee) => 'Dimanche P&agrave;ques'
				,$this->lundi_paques($annee) => 'Lundi P&agrave;ques'
				,$this->jeudi_ascension($annee) => 'Jeudi ascension'
				,$this->lundi_pentecote($annee) => 'Lundi pentecote'
	            ,"$annee-05-01" => 'F&ecirc;te du travail'      //    Féte du travail
				,"$annee-05-08" =>'Armistice 1945'              //    Armistice 1945
				,"$annee-07-14" =>'F&ecirc;te nationale'        //    Féte nationale
				,"$annee-08-15" =>'Assomption'                  //    Assomption
				,"$annee-11-11" => 'Armistice 1918'             //    Armistice 1918
				,"$annee-11-01" => 'Toussaint'                  //    Toussaint
				,"$annee-12-25" => 'No&euml;l'                  //    Noél
				
				));
				
				if($alsacemoselle)
				{
					$this->joursFerie['France']["$annee-12-26"] = 'alsace Moselle';
					$this->joursFerie['France'][$this->vendredi_saint($annee)] = 'Vendredi Saint';
				}
				
				return $this; // retourne un tableau de jours fériés français
			}
			else 
			{
		
				$ferie = new Default_Model_Ferie();
				$jours_feries_csm = $ferie->fetchAll("annee_reference = '".$annee."'");
			
			
				foreach ($jours_feries_csm as $j) 
				{
					$this->joursFerie['CSM'][$j->getDate_debut()] = $j->getLibelle();
				
				}
	
			      return $this; // retourne un tableau de jours fériés marocain
		       }
				
			}
		// fin setJoursFerie
		
			
		
   /**
	 * @desc : La fonction getPeriodeDetails retourne un tableau associatif contenant
	 *		   tous les jours de la période ,renseignée en paramétre, avec leur type : 
	 *          -N : jour ordinaire (ni Férié ni Weekend)
	 *          -F : jour férié (selon le $cs indiqué en paramétre sinon jours fériéés Français)
	 *          -WE : les weekends sur la période donnée.
	 *          -DM : si une période de congé , et le congé démarre à midi.
	 * 			-FM : si une période de congé , et le congé prend fin à midi ./.
	 * @param :
	 *        (String) $annee  : année référence;
	 * 		  (String)$dateDebut : date debut Période/congé
	 * 		  (String)$dateFin : date fin Période/congé
	 * 		  (bool) $cs [optionnel] : $cs à true pour le csm , à false pour la France
	 *		  (bool) $alsacemoselle ;
	 *
	 * @return : (Array) $conge : $conge[date] = typeJour
	 * 
	 * @author Mohamed BAINA 
	 */
	
	public function getPeriodeDetails($dateDebut,$dateFin,$typeConge=null,$cs=false,$alsacemoselle = false)
	{
		$dateD = substr($dateDebut,0,10); // extraire la date_debut
		$dm = substr($dateDebut,11,18);   // extraire le time de la date_debut
	
		if($dm == '12:00:00') $dm = true;
		else $dm = false;
		
		$dateF = substr($dateFin,0,10);   // extraire la date_fin
		$fm = substr($dateFin,11,18);     // extraire le time de la date_fin
		
		if($fm == '11:59:59') $fm = true;
		else $fm = false;
	  
		
		$conge = array();
		
		$dateTimeFin = new DateTime($dateF);
		$dateTimeFin->add(new DateInterval('P1D'));
		
		// calcul de la période , et subdivision en jours .
		$period = new DatePeriod(new datetime($dateD),new DateInterval('P1D'),$dateTimeFin);
	    $setJferie = 0;
		$annee = substr($dateD, 0, 4); // annee référence début
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
			  	
			   $anneet = $date->format("Y");
				 if($setJferie != 1) // flag pour changer les jours férié de l'année référence une seule fois 
				 {
					 if($annee !==$anneet )
					 {
					 	$jFerie =  $this->setJoursFerie($anneet,$cs,$alsacemoselle);
					 	$jFerie =(array) $jFerie;
					 
					 	$setJferie = 1;
				 	 }
				  }
			  	
			  	//formatage de la date 
				$dDate = $date->format('Y-m-d');
				$conge[$dDate]['TypeConge'] = $typeConge;
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
 	  * Fonction qui prend en paramètre le tableau de détails période et retourne 
  	  * les compteurs jours normaux(N),weekends(WE),Fériés(FE) dans un tableau.
      * @param (Array) $conge[]
   	  * @return (Array) $nombreJour[]
	  * @author Mohamed BAINA
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
				    {    
				    	 $cpt++;
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
			   else 
			   {
			       $dm = 0; // si date debut == we ou == f
			   }
		
			   if($conge[$fin]['FinMidi'] == true && $conge[$dateFin]['TypeJour'] != 'F' && $conge[$dateFin]['TypeJour'] != 'WE')
			   $fm = 0.5;
			   else 
			   {
			       $fm = 0; // si date fin == we ou == f
			   }
			
			  $nbrej = $n -($dm + $fm); 
			
			  $nombreJour = array('Total' => $cpt-($dm + $fm),
			                      'Fériés' => $f,
								  'Weenkends'=> $we,
								  'Nombre Jours congés' => $nbrej)	;
		    return $nombreJour;
		}

		
		
	/*
	 * PTRI - Calculer les droits à congés d'une ressource
	 */
	function calculer_droits_a_conges($ressource,$annee_reference) 
	{
		/*
		 * si annee_entree = annee_reference
		 * 	si date_entree < 1er juin : cp = nb_mois depuis date_entrée * 2.25
		 * 	sinon cp = 0
		 * sinon si annee_entree = annee_reference - 1
		 * 	si date_entree < 1er juin : cp = 27
		 * 	sinon cp = nb_mois depuis date_entrée * 2.25
		 * sinon cp = 27 + anciennete
		 * 
		 * ancienneté($annee_reference)
		 * 	switch 01/06/annee_reference - date_entree
		 * 		case 2<=n<3 : anciennete = 1
		 * 		case 3<=n<5 : anciennete = 2
		 * 		case 5<=n<8 : anciennete = 3
		 * 		case n>=8 : anciennete = 4
		 * 	
		 * rtt dépend de la modalite
		 * 
		 * temps partiel (pourcentage)
		 * 
		 * Q2 : initialisé é l'écran de gestion des soldes
		 * 
		 */
		
		$logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('php://output');
		$logger->addWriter($writer);
		
		$cp = 0;
		$cpa = 0;
		$q1 = 0;
		
		$date_entree = new DateTime($ressource->getDate_entree());
		
		$annee_entree = date_format($date_entree, 'Y'); // annee au format 2013
		$mois_entree = date_format($date_entree, 'n'); // mois au format 1 é 12
		$jour_entree = date_format($date_entree, 'j'); // mois au format 1 é 31

		// Calcul des CP
		if ($annee_entree == $annee_reference) 
		{
			if ($mois_entree < 6) 
			{
				$cp = 2.25 * (6 - $mois_entree);
				
				if ($jour_entree >= 15) 
				{
					$cp -= 2.25;
				}
			}
			else 
			{
				$cp = 0;
			}
		}
		elseif ($annee_entree == $annee_reference - 1) 
		{
			if ($mois_entree < 6) 
			{
				$cp = 27;
			}
			else 
			{
				$cp = 2.25 * (5 + 12 - $mois_entree + 1);
				if ($jour_entree >= 15) 
				{
					$cp -= 2.25;
				}
			}
		}
		else 
		{
			$cp = 27;
		}
		
		// Calcul des CP Ancienneté
		$annee_reference = new DateTime($annee_reference.'-06-01');
		echo date_format($annee_reference, 'd-m-Y').'<BR>';
		$interval = $date_entree->diff($annee_reference);
		$i = $interval->format('%y');
		if ($i >= 2 && $i < 3) 
		{
			$cpa = 1;
		}
		elseif ($i >= 3 && $i < 5) 
		{
			$cpa = 2;
		}
		elseif ($i >= 5 && $i < 8) 
		{
			$cpa = 3;
		}
		elseif ($i >= 8) 
		{
			$cpa = 4;
		}
				
		// Calcul des RTT Q1
		$annee_reference = date_format($annee_reference, 'Y');
		$debut_annee = new DateTime($annee_reference.'-01-01');
		$fin_annee = new DateTime($annee_reference.'-12-31');
		$nb_jo = $this->calculer_jours_ouvres($debut_annee,$fin_annee);
		
		$nb_rtt_ms = 7.4 *($nb_jo-25-12) + 7 > 1607 ? 13 : 12;
		$nb_rtt_rm_ac = $nb_jo-25-218 < 10 ? 10 : $nb_jo-25-218;
		
		$modalite = new Default_Model_Modalite();
		$modalite = $modalite->find($ressource->getId_modalite());
		$modalite = $modalite->getCode();
		
		if ($modalite == "MS") 
		{
			$q1 = 7.4 * ($nb_jo-25-12) + 7  > 1607 ? 13 : 12;
		}
		elseif ($modalite == "RM" || $modalite == "AC") 
		{
			$q1 = $nb_jo-25-218 < 10 ? 10 : $nb_jo-25-218;
		}
		elseif ($modalite == "NO") 
		{
			$q1 = 0;
		}
		else 
		{
			$q1 = 10;
		}
	
		// Pour les nouveaux entrants, appliquer un prorata
		if ($annee_entree == $annee_reference) 
		{
			$nb_mois_complets = 12 - $mois_entree + 1;
			
			if ($jour_entree >= 15) 
			{
				$nb_mois_complets -= 1;
			}
			
			$q1 = round($q1 * $nb_mois_complets / 12, 0, PHP_ROUND_HALF_DOWN); 
		}
		
		// ratio temps partiels
		$ressource->getPourcent();
		$cp = round($cp * $ressource->getPourcent() / 100, 0, PHP_ROUND_HALF_DOWN);
		$cpa = round($cpa * $ressource->getPourcent() / 100, 0, PHP_ROUND_HALF_DOWN);
		$q1 = round($q1 * $ressource->getPourcent() / 100, 0, PHP_ROUND_HALF_DOWN);
		
		//	$logger->log($jours_feries_csm[0]->getDate_debut(), Zend_Log::INFO);
		return array("CP" => $cp,"CPA" => $cpa,"Q1" => $q1);
	}

		
	public function normaliser_date($dateDebut,$dateFin,$maroc=false,$alsacemoselle=false)
	{

		$dateD = substr($dateDebut,0,10); // extraire la date_debut
		$dm = substr($dateDebut,11,18);   // extraire le time de la date_debut
	
		if($dm == '12:00:00') $dm = true;
		else $dm = false;
		
		$dateF = substr($dateFin,0,10);   // extraire la date_fin
		$fm = substr($dateFin,11,18);     // extraire le time de la date_fin
		
		if($fm == '11:59:59') $fm = true;
		else $fm = false;
		
		
		$conge = array();
		$cpt = 0;
		$i = 0;
		$setJferie = 0;
		$annee = substr($dateDebut, 0, 4); // annee référence début
		
		
		
		$dateTimeFin = new DateTime($dateF);
		$dateTimeFin->add(new DateInterval('P1D'));
		$jFerie =  $this->setJoursFerie($annee,$maroc,$alsacemoselle);
		// calcul de la période , et subdivision en jours .
		$period = new DatePeriod(new datetime($dateD),new DateInterval('P1D'),$dateTimeFin);
	
		// récupération des jours fériés sur l'année référence.
		
        $jFerie =(array) $jFerie;
      
        
		if($maroc == false)
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
			  
			 $anneet = $date->format("Y");
			 if($setJferie != 1) // flag pour changer les jours férié de l'année référence une seule fois 
			 {
			 if($annee !==$anneet )
			 {
			 	$jFerie =  $this->setJoursFerie($anneet,$maroc,$alsacemoselle);
			 	$jFerie =(array) $jFerie;
			 
			 	$setJferie = 1;
			 	
			 }
			 }
			  	$dDate = $date->format('Y-m-d');
				
			  	if($cpt == 0 && !$this->isWeekend($dDate) && !isset($jFerie['joursFerie'][$iCs][$dDate]))
			  	{
			  		$conge[$i]['TypeJour'] = 'N';
			  		$conge[$i]['Date'] = $dDate;
					$cpt++;
					if($k >0) $dm = false;				  
			  	}
			  	
			  	else if($cpt > 0)
			  	{
				  	if($this->isWeekend($dDate) )
					{
					$typeJour ='WE';
					
						
					}
				// vérif si le date est fériée
				    elseif(isset($jFerie['joursFerie'][$iCs][$dDate]))
				    {
				    	$typeJour ='F';
				    	
						
				    }
					else
					{
						$typeJour = 'N';
						$cpt++;
						
					}
			        $i++; 
			        $conge[$i]['Date'] = $dDate;
			     	$conge[$i]['TypeJour'] = $typeJour;
			     	
		     		}
			  	}
			      if($cpt == 0)
			       {
			       	return null;
			       }
					  	
			  	if($conge !== null)
			  	{
			  	$countConge = count($conge) -1;

			  	while($conge[$countConge]['TypeJour'] !== 'N' )
			  	{
			  		array_pop($conge);
			  		$countConge = count($conge) -1;
			  	    $fm = false;
			  		
			  	}
			  	}
			  	
         $dateTime =  $this->makeDatetime($conge['0']['Date'], $conge[$countConge]['Date'], $dm, $fm);
		   
	       $dateNormalisee[0] = $dateTime[0];
	       $dateNormalisee[1] = $dateTime[1];
	      
	       
		return $dateNormalisee;

	}		

   /** 
     *  @desc  Fonction qui calcul le nombre de jours ouvrable entre deux dates ($date_debut et $date_fin) 
     *         en tenant compte des jours fériés csm ou france a travers le parametre $maroc
	 * 
     *  @name  calculer_jours
     *
	 *  @param Datetime $date_debut
	 *  @param Datetime $date_fin
	 *  @param boolean  $maroc 
	 * 
	 *  @return float   $nbj
	 *                                             
	 *  @example calculez_jours pour un membre de l'equipe front (france)    $maroc == false
	 *     
	 *           calculer_jours( 2013-05-02 12:00:00 , 2013-05-10 23:59:59 , false)   
	 *          
	 *            2013-05-02   -  à Midi
	 *            2013-05-03   -
	 *            2013-05-04   We
	 *            2013-05-05   We
	 *            2013-05-06   -
	 *            2013-05-07   -
	 *            2013-05-08   Férié
	 *            2013-05-09   Férié 
	 *            2013-05-10   - 
	 *      
	 *            return  4.5 ;
	 *                   
	 *  @author Mohamed khalil TAKAFI
	 */
	   //////////////////////////////////////////////Calcul nombre de jours (Propositions et congés)//////////////////////////////////////////////
		public function calculer_jours($date_debut,$date_fin,$maroc) 
		{  
			
			    $tab = array();
				$nbj = 0;
				$i = 0;
		        foreach (new DatePeriod($date_debut,new DateInterval('PT1H'),$date_fin) as $d) 
		        { 
		           $tab[$i] =  $d->format('Y-m-d H:i:s'); 
		           $i++;
		        } 
				$nbj =  count($tab)/ 24;   // resultat en Heurs / 24 = jours 
		
		
				// Parcourir l'intervalle
				$date_iterator = $date_debut;
				while ($date_iterator <= $date_fin) 
				{
					// Loguer les jours ouvrés (tous les jours sauf les samedi, dimanche, fériés)
					$weekday = date_format($date_iterator, 'l');       
						                            																			   // alsacmoselle = false 
					if (in_array($weekday,array('Saturday','Sunday'))  || $this->est_ferie(date_format($date_iterator,"Y-m-d"),false,$maroc)) 
					{     																											
				 	      $nbj -- ;  // si on trouve un weekend ou férié entre la periode donnée on décremente le nombre de jours 
				    }
				   // Incrémenter l'iterator
				   $date_iterator->add(new DateInterval("P1D"));
			
				}
			    return  $nbj;

		
		}//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


	
    /** 
     *  @desc  Fonction qui prend en parametre une date 
     *         et en fonction des $debut_midi et $fin_midi 
     *         nous retourne une date format Y-m-d H:i:s
	 * 
     *  @name  makeDatetime
     *
	 *  @param string $date_debut
	 *  @param string $date_fin
	 *  @param int $debut_midi
	 *  @param int $fin_midi
	 * 
	 *  @return array() de strings $date[0] = $date_debut format "Y-m-d H:i:s"
	 *                             $date[1] = $date_fin format "Y-m-d H:i:s"
	 *                           
	 *                           
	 *  @example  makeDatetime('2013-05-22','2013-05-25',1,0)
	 *            return $date[0] = '2013-05-22 12:00:00'
	 *                   $date[1] = '2013-05-25 23:59:59' ;
	 *                   
	 *                   
	 *  @author Mohamed khalil TAKAFI
	 */
	////////////////////////////// Fonction reglage des dates en fonction des demis journées ////////////////////////////////
	public function makeDatetime($date_debut,$date_fin,$debut_midi,$fin_midi) 
	{	 	
		    $date_deb = new DateTime($date_debut);
		   $date_fi = new DateTime($date_fin);
	
			// gérer les datetimes 			
			if($debut_midi == 1)
			{    // ajouter 12h00m00s à la date 
				 $date_deb =  $date_deb->add(new DateInterval('PT12H00M00S'));				    
			} 					    
			if($fin_midi == 1)
			{    // ajouter 11h59m59s à la date 
				 $date_fi =   $date_fi->add(new DateInterval('PT11H59M59S'));	     			    
			}
			else //  $fin_midi == 0
			{    // ajouter 23h59m59s à la date 
				 $date_fi =  $date_fi->add(new DateInterval('PT23H59M59S'));
			}
		 
            $date[0] =  $date_deb->format("Y-m-d H:i:s");
		    $date[1] =  $date_fi->format("Y-m-d H:i:s");

	    return $date;
	} //////////////////////////////////////////////////////////////////////////////////////////////////////////////


	
	/** 
	 *  @desc  Fonction responsable de l'affichage du message succés et warning
     *         qui remplace " 12:00:00 "   ou  " 11:59:59 "   par   " à Midi " 
     *         et  remplace " 00:00:00 "   ou  " 23:59:59 "   par   "" (chaine vide)
     *         
     *         et formatage de la date depuis yyyy-mm-jj hh:mm:ss  à  jj/mm/yyyy hh:mm:ss
     *            depuis "2013-05-22 12:00:00" 		à 		"22/05/2013 12:00:00" 
     *               
     *  @name  makeMidi
     *
	 *  @param string $date_debut
	 *  @param string $date_fin
	 * 
	 *  @return array() de strings $date[0] = $date_debut format "d-m-Y"
	 *                             $date[1] = $debut_midi  soit " "  ou  "à Midi"
	 *                             $date[2] = $date_fin format "d-m-Y"
	 *                             $date[3] = $fin_midi   soit " "  ou  "à Midi"
	 *  
	 *  @example  makeMidi('2013-05-22 12:00:00','2013-05-25 23:59:59')
	 *            return $date[0] = '22/05/2013'
	 *                   $date[1] = 'à Midi' 
	 *                   $date[2] = '25/05/2013'
	 *                   $date[3] = ' ';
	 *                   
	 *  @author Mohamed khalil TAKAFI
	 */
	/////////////////////fonction responsable de l'affichage du message succés,warning////////////////////////////  
	public function makeMidi($date_debut,$date_fin)
	{
		$date[0] = substr($date_debut,0,10); // extraire la date_debut
		$t_deb = substr($date_debut,11,18);   // extraire le time de la date_debut
	
		$date[1] = substr($date_fin,0,10);   // extraire la date_fin
		$t_fin = substr($date_fin,11,18);     // extraire le time de la date_fin
			
	    $chaine[0] = '';      $chaine[1] = '';

	    // formatage time (remplacer "12:00:00" ou "11:59:59" par "à Midi")
		if($t_deb == '12:00:00' || $t_deb == '11:59:59') 	{$chaine[0] = '&agrave; Midi';}
		if($t_fin == '12:00:00' || $t_fin == '11:59:59')    {$chaine[1] = '&agrave; Midi';}
		
		// formatage date " jj/mm/aaaa " 
		$dd = new DateTime($date[0]);
		$dd = $dd->format("d/m/Y");
	    $df = new DateTime($date[1]);
		$df = $df->format("d/m/Y");
		
	    return array($dd, $chaine[0],$df,$chaine[1]);	    
	}  //////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	


	
	
	
	/** 
	 *                   
	 *  @author Mohamed khalil TAKAFI
	 */
	/////////////////////fonction responsable pour autorisé la création d'une proposition ou d'un congé ////////////////////////////  
	
    // Bool :   resultat fonction 
    // code :   pour le message d'erreur 
    // personne->getDate_debut() : date_debut dans le  projet 
    // $personne_datefin : date_sortie du projet
    // obj_dd : date_debut proposition 
    // obj_df : date_fin  proposition 
    
	public function authorized($personne,$obj)
	{   
	   $bool = false ;
	   
	   $obj_dd = substr($obj->getDate_debut(),0,11);
	   $obj_df = substr($obj->getDate_fin(),0,11);

	   $personne_datefin = $this->makedate($personne->getDate_fin()); 

        if($personne_datefin  == "-" || $personne_datefin  == "01/01/1970" || $personne_datefin  == "1970-01-01" || $personne_datefin  == "0000-00-00" || $personne_datefin  == "00-00-0000")   // date_fin non mensioné   soit 01/01/1970 (date unix par default) oubien  "" chaine vide 
        {
	            if($obj_dd >= $personne->getDate_debut())
		        {
		            $bool = true ;
		        }
        }
        else                             // date_fin mentioné 
        {
            
       		 if(($obj_dd <= $personne->getDate_fin() &&  $obj_df <= $personne->getDate_fin()) && ($obj_dd >= $personne->getDate_debut() &&  $obj_df >= $personne->getDate_debut()))
	        {
	            $bool = true ;
	        }
            
            
        }
	      return array($bool,$personne->getDate_debut(),$personne_datefin,$obj_dd,$obj_df);
	}
	
	
	

    //////////////////////// fonction pour l'affichage de la date de fin //////////////////////// 
    // afficher "-" si date de fin non mensioné
    // afficher la date si date de fin mensioné 
    
	public function makedate($date)
	{
	
	    if($date == "1970-01-01" || $date == "01/01/1970" || $date == "" || $date == "0000-00-00" || $date == "00-00-0000" )
	    {
	        return "-";
	    }
	    else
	    {
	        return $date;
	    }

	}
	
	                                                                   
                                                                     
                                             
   /** 
	 *  @desc  Fonction responsable du découpage des congés en sous congés (par mois) et (par demi journée)
     *               
     *  @name  sous_periodes
     *
	 *  @param string $date_debut
	 *  @param string $date_fin
	 * 
	 *  @return array() de strings 
	 *  
	 *  @example  sous_periodes('2013-08-01 12:00:00','2013-10-21 11:59:59')    
	 *            return $periode ;
	 *             
	 *            // details du tableau : 
	 *                      
	 *            $periode[0]['date_debut'] = '2013-08-01 12:00:00';
	 *            $periode[0]['date_fin']   = '2013-08-01 23:59:59';
	 *                   
     *            $periode[1]['date_debut'] = '2013-08-02 00:00:00';
	 *            $periode[1]['date_fin']   = '2013-08-31 23:59:59';
	 *                   
	 *            $periode[2]['date_debut'] = '2013-09-01 00:00:00';
	 *            $periode[2]['date_fin']   = '2013-09-30 23:59:59';
	 *                   
	 *            $periode[3]['date_debut'] = '2013-10-01 00:00:00';
	 *            $periode[3]['date_fin']   = '2013-10-20 23:59:59';
	 *                   
	 *            $periode[4]['date_debut'] = '2013-10-21 00:00:00';
	 *            $periode[4]['date_fin']   = '2013-10-21 11:59:59';                
	 */

	
	
	function sous_periodes($date_debut,$date_fin) 
	{

				
			$date_debut = new DateTime($date_debut);
			$date_fin = new DateTime($date_fin);
				
			$date_it = new DateTime(date_format($date_debut,'Y-m-d H:i:s')); // curseur qui parcoure nos dates du début à la fin
			$periode_debut = new DateTime(date_format($date_debut, 'Y-m-d H:i:s'));
			$periode_fin = new DateTime(date_format($date_fin, 'Y-m-d H:i:s'));
			$periodes = array();
			
				// Traiter les 1/2 journées en début et fin de période
				// Période 0 : [12h-0h]
				if (!strcmp(date_format($date_debut, 'H'),'12')) 
				{
					
					$periodes[] = array('date_debut' => date_format($date_debut, 'Y-m-d 12:00:00'), 'date_fin' => date_format($date_debut, 'Y-m-d 23:59:59'));
					
					// Incrémentation du curseur
					$date_it->add(new DateInterval("P1D"));
					$date_it->setTime(0,0,0); // RAZ de l'heure
					
						if (date_format($date_it, 'Y-m-d') > date_format($date_fin, 'Y-m-d')) 
						{
							return $periodes;
						}
				}
			
				// Période N : [0h-12h]
				if (!strcmp(date_format($date_fin, 'H'),'11')) 
				{
				
					$periodeFin = array('date_debut' => date_format($date_fin, 'Y-m-d 00:00:00'), 'date_fin' => date_format($date_fin, 'Y-m-d 11:59:59'));
					
					// On décrémente d'une journée la période de fin
					$periode_fin->sub(new DateInterval("P1D"));
					$periode_fin->setTime(0,0,0); // RAZ de l'heure
					
						if (date_format($date_it, 'Y-m-d') > date_format($periode_fin, 'Y-m-d')) 
						{
							$periodes[] = $periodeFin;
							return $periodes;
						}
				}
			
				// Tant que le mois du curseur n'a pas rejoint le dernier mois à traiter, on enregistre des périodes
				while (date_format($date_it, 'Y-m') < date_format($periode_fin, 'Y-m')) 
				{
				
					// Nouvelle date début pour la prochaine période
					$periode_debut = new DateTime(date_format($date_it, 'Y-m-d 0:0:0'));
					
					// Avancer le curseur jusqu'à la fin du mois
					$nb_jours = date_format($date_it, 't') - date_format($date_it, 'j');
					$date_it->add(new DateInterval("P".$nb_jours."D"));
				
					// Enregistrer la nouvelle période
				
					$periodes[] = array('date_debut' => date_format($periode_debut, 'Y-m-d 00:00:00'), 'date_fin' => date_format($date_it, 'Y-m-d 23:59:59'));
					
					// Avancer le curseur jusqu'au début du mois suivant (+1 jour)
					$date_it->add(new DateInterval("P1D"));
					
				}
			
				// Enregistrer la dernière période
			
				$periodes[] = array('date_debut' => date_format($date_it, 'Y-m-d 00:00:00'), 'date_fin' => date_format($periode_fin, 'Y-m-d 23:59:59'));
				
				if (isset($periodeFin)) 
				{
					$periodes[] = $periodeFin;
				}
				return $periodes;
}
	
	

	
 }// End of class 
