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
		
		    return in_array($jour, $this->jours_feries($annee, $alsacemoselle, $maroc));
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
			,    "$annee-05-01"        //    F�te du travail
			,    "$annee-05-08"        //    Armistice 1945
			,    "$annee-08-15"        //    Assomption
			,    "$annee-07-14"        //    F�te nationale
			,    "$annee-11-11"        //    Armistice 1918
			,    "$annee-11-01"        //    Toussaint
			,    "$annee-12-25"        //    No�l
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
	            ,"$annee-05-01" => 'F&ecirc;te du travail'      //    F�te du travail
				,"$annee-05-08" =>'Armistice 1945'              //    Armistice 1945
				,"$annee-07-14" =>'F&ecirc;te nationale'        //    F�te nationale
				,"$annee-08-15" =>'Assomption'                  //    Assomption
				,"$annee-11-11" => 'Armistice 1918'             //    Armistice 1918
				,"$annee-11-01" => 'Toussaint'                  //    Toussaint
				,"$annee-12-25" => 'No&euml;l'                  //    No�l
				
				));
				
				if($alsacemoselle)
				{
					$this->joursFerie['France']["$annee-12-26"] = 'alsace Moselle';
					$this->joursFerie['France'][$this->vendredi_saint($annee)] = 'Vendredi Saint';
				}
				
				return $this; // retourne tableau de jours f�ri�s français
			}
			else 
			{
		
				$ferie = new Default_Model_Ferie();
				$jours_feries_csm = $ferie->fetchAll("annee_reference = '".$annee."'");
			
			
				foreach ($jours_feries_csm as $j) 
				{
					$this->joursFerie['CSM'][$j->getDate_debut()] = $j->getLibelle();
				
				}
	
			      return $this; // retourne tableau de jours f�ri�s marocain
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
	 * 		  (bool) $cs [optionnel] : $cs à true pour le csm , à false pour la France
	 *		  (bool) $alsacemoselle ;
	 * 
	 * 
	 * 
	 * 
	 * @return : (Array) $conge : $conge[date] = typeJour
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
		 * 
		 * Fonction qui prend en paramètre le tableau de d�tails p�riode et retourne 
		 * les compteurs jours normaux(N),weekends(WE),F�ri�s(FE) dans un tableau.
		 * @param (Array) $conge[]
		 * @return (Array) $nombreJour[]
		 */
		
	  public function calculNombreJourConge($conge)
	 {     
			  // initialisation des compteurs
			  $n = $f = $we = $cpt =  0;
			  // dernier indice du tableau cong�
			  $fin = (count($conge) -2)/2;
				
				$dateDebut = $conge['0']['Date'];
				$dateFin = $conge[$fin]['Date'];
	
				// parcourir le tableau de la p�riode de cong�
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
			                      'F�ri�s' => $f,
								  'Weenkends'=> $we,
								  'Nombre Jours cong�s' => $nbrej)	;
		    return $nombreJour;
		}

		
		
	/*
	 * PTRI - Calculer les droits � cong�s d'une ressource
	 */
	function calculer_droits_a_conges($ressource,$annee_reference) 
	{
		/*
		 * si annee_entree = annee_reference
		 * 	si date_entree < 1er juin : cp = nb_mois depuis date_entr�e * 2.25
		 * 	sinon cp = 0
		 * sinon si annee_entree = annee_reference - 1
		 * 	si date_entree < 1er juin : cp = 27
		 * 	sinon cp = nb_mois depuis date_entr�e * 2.25
		 * sinon cp = 27 + anciennete
		 * 
		 * anciennet�($annee_reference)
		 * 	switch 01/06/annee_reference - date_entree
		 * 		case 2<=n<3 : anciennete = 1
		 * 		case 3<=n<5 : anciennete = 2
		 * 		case 5<=n<8 : anciennete = 3
		 * 		case n>=8 : anciennete = 4
		 * 	
		 * rtt d�pend de la modalite
		 * 
		 * temps partiel (pourcentage)
		 * 
		 * Q2 : initialis� � l'�cran de gestion des soldes
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
		$mois_entree = date_format($date_entree, 'n'); // mois au format 1 � 12
		$jour_entree = date_format($date_entree, 'j'); // mois au format 1 � 31

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
		
		// Calcul des CP Anciennet�
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
		$annee = substr($dateDebut, 0, 4); // annee r�f�rence d�but
		
		
		
		$dateTimeFin = new DateTime($dateF);
		$dateTimeFin->add(new DateInterval('P1D'));
		$jFerie =  $this->setJoursFerie($annee,$maroc,$alsacemoselle);
		// calcul de la p�riode , et subdivision en jours .
		$period = new DatePeriod(new datetime($dateD),new DateInterval('P1D'),$dateTimeFin);
	
		// r�cup�ration des jours f�ri�s sur l'ann�e r�f�rence.
		
        $jFerie =(array) $jFerie;
      
        
		if($maroc == false)
		{
			$iCs = 'France'; // indice cs france
			}
				else 
				{
					$iCs = 'CSM'; // indice csm
					}
		
		
        //r�cup�ration des jours de la p�riode.
			  foreach ($period as $k=>$date)
			  {
			  
			 $anneet = $date->format("Y");
			 if($setJferie != 1) // flag pour changer les jours f�ri� de l'ann�e r�f�rence une seule fois 
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
				// v�rif si le date est f�ri�e
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
	 * 
	 * MTA : calcul nombre jours cong�  (modifi� 18-06-2013) 
	 * @param Datetime $date_debut
	 * @param Datetime $date_fin
	 * @param boolean $maroc
	 */	
	//////////////////////////////////////////////////Calcul nombre de jours (Propositions et cong�s)//////////////////////////////////////////////////////////////
		function calculer_jours($date_debut,$date_fin,$maroc) 
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
				// Loguer les jours ouvr�s (tous les jours sauf les samedi, dimanche, f�ri�s)
				$weekday = date_format($date_iterator, 'l');       
	
				                            																			   // alsacmoselle = false 
				if (in_array($weekday,array('Saturday','Sunday'))  || $this->est_ferie(date_format($date_iterator,"Y-m-d"),false,$maroc)) 
				{     																											
			 	      $nbj -- ;  // si on trouve un weekend ou f�ri� entre la periode donn�e on d�cremente le nombre de jours 
			    }
			   // Incr�menter l'iterator
			   $date_iterator->add(new DateInterval("P1D"));
		
			}
			return  $nbj;
		}//////////////////////////////////////////////////////////////////////////////////////////////////////////


	

	/**
	 * Description : 
	 * Fonction qui ajoute 12h00m00s � la date_debut si debut_midi == 1   
	 * 			 et qui ajoute 11h59m59s � la date_fin si fin_midi == 1
	 */
	////////////////////////////// Fonction reglage des dates en fonction des demis journ�es ////////////////////////////////
	public function makeDatetime($date_debut,$date_fin,$debut_midi,$fin_midi) 
	{
			 	
		    $date_deb = new DateTime($date_debut);
		    $date_fi = new DateTime($date_fin);
	
			// gerer les datetimes 			
			if($debut_midi == 1)
			{    // ajouter 12h00m00s � la date 
				 $date_deb =  $date_deb->add(new DateInterval('PT12H00M00S'));				    
			} 					    
			if($fin_midi == 1)
			{    // ajouter 11h59m59s � la date 
				 $date_fi =   $date_fi->add(new DateInterval('PT11H59M59S'));	     			    
			}
			else //  $fin_midi == 0
			{    // ajouter 23h59m59s � la date 
				 $date_fi =  $date_fi->add(new DateInterval('PT23H59M59S'));
			}
		 
			 $date[0] = $date_deb->format('Y-m-d H:i:s');
	   		 $date[1] = $date_fi->format('Y-m-d H:i:s');
	   		 
	    return $date;
	} //////////////////////////////////////////////////////////////////////////////////////////////////////////////

	
	
	/**
	 * Description : 
	 * Fonction responsable de l'affichage du message succ�s , warning
	 * qui remplace " 12:00:00 "   ou  " 11:59:59 "   par   " � Midi "
	 */
	/////////////////////fonction responsable de l'affichage du message succ�s,warning////////////////////////////  
	public function makeMidi($date_debut,$date_fin)
	{
	
		$date[0] = substr($date_debut,0,10); // extraire la date_debut
		$t_deb = substr($date_debut,11,18);   // extraire le time de la date_debut
	
		$date[1] = substr($date_fin,0,10);   // extraire la date_fin
		$t_fin = substr($date_fin,11,18);     // extraire le time de la date_fin
			
	    $chaine[0] = '';      $chaine[1] = '';
							        
		if($t_deb == '12:00:00' || $t_deb == '11:59:59') 	{$chaine[0] = '&agrave; Midi';}
		if($t_fin == '12:00:00' || $t_fin == '11:59:59')    {$chaine[1] = '&agrave; Midi';}
	
	    return array($date[0], $chaine[0],$date[1],$chaine[1]);
			    
	}  //////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
	
	

 }
 
  