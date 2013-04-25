<?php
class Default_Controller_Helpers_Validation extends Zend_Controller_Action_Helper_Abstract
{
	
/*
 *  MTA :  Fonctions utilitaires pour calculer le nombre de jours de congé 
 */

//MTA 
///////Function recupére les jours fériés maroc 
	   public function jours_feries_maroc($annee) 
	{  
    	$feris = new Default_Model_Ferie();       
		$jours_feries_maroc = $feris->RecupererLesJoursFeries($annee);  // Couplage fort 

		// retourne le tableau 
		return $jours_feries_maroc; 
	}
	
//MTA 
///////Function recuperer session 
	public function recup_session($jfm)
	{
	if (isset($jfm))
	return $jfm;
	else 
	return null;
	}
	
//MTA 
///////Indique si une date doit être normalisée ou non
	public function a_normaliser($date,$maroc) 
	{   
		if (in_array(date_format($date, 'l'),array('Saturday','Sunday')) || $this->est_ferie($date,false,$maroc)) 
		{
			return true;
		}
		
		return false;
	}
	
//MTA 
///////On normalise un flag midi par rapport à une date non normalisée
	public function normaliser_flag_midi($date,$midi,$maroc) 
	{   
		// Si la date de début ou fin congé tombe un WE ou JF, le flag midi ne peut pas être actif
		if ($this->a_normaliser($date,$maroc)) 
		{
			$midi = false;
		}
		return $midi;
	}

//MTA 
///////Si la date de début de congé tombe un WE ou JF, on l'avance au 1er JO
	public function normaliser_date_debut_conge($date,$maroc)   
	{ 
		while (in_array(date_format($date, 'l'),array('Saturday','Sunday')) || $this->est_ferie($date,false,$maroc)) 
		{
			$date->add(new DateInterval("P1D"));
		}
		return $date;
	}

//MTA      
///////Si la date de fin de congé tombe un WE ou JF, on la retarde au dernier JO
	public function normaliser_date_fin_conge($date,$maroc) 
	{  
		while (in_array(date_format($date, 'l'),array('Saturday','Sunday')) || $this->est_ferie($date,false,$maroc)) 
		{
			$date->sub(new DateInterval("P1D"));
		}
		return $date;
	}	
	
//MTA 
/////// Fonction calcul les jours fériés Maroc / France 	
	   public function jours_feries($annee, $alsacemoselle, $maroc)
	  { 	
        $dimanche_paques = date("Y-m-d", easter_date($annee));
        $tab = array(); // tab temporaire 
        $tab_tmp = array(); // tab temporaire 
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
			        // jours fériés maroc depuis la session
					$tab = $this->recup_session($_SESSION['TEST']['jfm']);  

			        // rendre un tableau à deux dimension en un tableau à une seule dimension 
	            	for ($i = 0; $i < count($tab); $i++) 
			        {
			        	$tab_tmp[$i] = $tab[$i]['date_debut'];
			        }		
			 
			return  $tab_tmp;	//retourne les jours fériés maroc   //$this->jours_feries_maroc($annee);  	
	              
		}
	}
//MTA 	   
///////Fonction test si un jours passé en argument est férié ou non 
	    function est_ferie($jour, $alsacemoselle, $maroc)
	    {   
	        $jour1 =  date_timestamp_get($jour);
	    	$jour = date("Y-m-d",$jour1);
	    	$annee = substr($jour, 0,4);

	        return in_array($jour,$this->jours_feries($annee, $alsacemoselle, $maroc));
		}   	
		
//MTA 
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
		$weekday = date_format($date_iterator, 'l');
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





	public function verifierConges($id_personne,$date_debut,$date_fin,$mi_debut_journee,$mi_fin_journee,$id_type,$centre)
	{
				
		if (($date_debut < $date_fin) || (($mi_debut_journee|| $mi_fin_journee) && (($date_debut <= $date_fin))&& ($mi_debut_journee!= $mi_fin_journee)) || (($mi_debut_journee == $mi_fin_journee) && (($date_debut < $date_fin))) )
		{
		
		
				$conge1 = new Default_Model_Conge();
				$conge1 = $conge1 ->fetchall('id_personne = '.$id_personne);
				$flag_save = false;
				$flag_save1 = false;
				$flag_save2 = false;		
				
				if (count($conge1)!=0  ) 
				{
					foreach ($conge1 as $c ) 
					{
						$date_debut_base_conge = $c->getDate_debut();
						$date_fin_base_conge = $c->getDate_fin();
						$date_mi_debut_base_conge = $c->getMi_debut_journee();
						$date_mi_fin_base_conge = $c->getMi_fin_journee();
						$date_id_type_conge = $c->getId_type_conge();
						
						
					
						if ( (($date_debut <= $date_debut_base_conge) &&($date_fin >= $date_fin_base_conge))||(($date_debut >= $date_debut_base_conge) &&($date_fin <= $date_fin_base_conge)) || (($date_debut <= $date_fin_base_conge) &&($date_fin >= $date_fin_base_conge)) ||(($date_debut <= $date_debut_base_conge) &&($date_fin >= $date_debut_base_conge)))
						{
							if ($mi_debut_journee|| $mi_fin_journee)
							{
								if ((($date_debut == $date_debut_base_conge)&&($date_fin == $date_fin_base_conge))&&(($mi_debut_journee==$date_mi_debut_base_conge)||($mi_fin_journee==$date_mi_fin_base_conge)))
								{
									$flag_save2 =1;
									break;
								}
								elseif ((($date_debut == $date_debut_base_conge)&&($date_fin == $date_fin_base_conge))&&(($mi_debut_journee!=$date_mi_debut_base_conge)&&($mi_fin_journee!=$date_mi_fin_base_conge)))
								{$flag_save =TRUE;}
							}
							
							if (!$flag_save)
							{
								$flag_save1 = 1;
								break;
							}
						
						}
						
						else {$flag_save1 = 0;}
					}
					if(!($flag_save1||$flag_save2)) 
					{
						return true;
						
					}
					else 
					{
						return false;
						
					}
				}
			else 
			{
				return true;
			}
        
		}
		return false;
	}
        
  public function verifierPropositions($id_personne,$date_debut,$date_fin,$mi_debut_journee,$mi_fin_journee)
  {
  	    // date_debut < date_fin     ou     (  ( debut_midi = 1   ou    fin_midi = 1 ) et ( date_debut < date_fin ) )
        if (($date_debut < $date_fin) || (($mi_debut_journee|| $mi_fin_journee) && (($date_debut <= $date_fin)))  )
        {

        	$proposition = new Default_Model_Proposition();
        	$proposition  = $proposition ->fetchall('id_personne = '.$id_personne);
        	$flag_save = false;
        	$flag_save1 = false;
        	$flag_save2 = false;
        
        	if (count($proposition)!=0  )
        	{
        		foreach ($proposition as $p )
        		{
        			$date_debut_base_proposition = $p->getDate_debut();
        			$date_fin_base_proposition = $p->getDate_fin();
        			$date_mi_debut_base_proposition = $p->getMi_debut_journee();
        			$date_mi_fin_base_proposition = $p->getMi_fin_journee();

        		
        		if ( (($date_debut <= $date_debut_base_proposition) && ($date_fin >= $date_fin_base_proposition))||(($date_debut >= $date_debut_base_proposition) && ($date_fin <= $date_fin_base_proposition)) || (($date_debut <= $date_fin_base_proposition) && ($date_fin >= $date_fin_base_proposition)) ||(($date_debut <= $date_debut_base_proposition) && ($date_fin >= $date_debut_base_proposition)))
        			{
        				if ($mi_debut_journee|| $mi_fin_journee)
        				{
        					if ((($date_debut == $date_debut_base_proposition) && ($date_fin == $date_fin_base_proposition))&&(($mi_debut_journee==$date_mi_debut_base_proposition)||($mi_fin_journee==$date_mi_fin_base_proposition)))
        					{
        						$flag_save2 =1;
        						break;
        					}
        					elseif ((($date_debut == $date_debut_base_proposition)&&($date_fin == $date_fin_base_proposition))&&(($mi_debut_journee!=$date_mi_debut_base_proposition)&&($mi_fin_journee!=$date_mi_fin_base_proposition)))
        					{
        						$flag_save = TRUE;
        					}
        				}
        					
        				if (!$flag_save)
        				{
        					$flag_save1 = 1;
        					break;
        				}
        			}
        
        			else {
        				$flag_save1 = 0;
        			}
        		}
        		if(!($flag_save1||$flag_save2))
        		{
        			return true;
        
        		}
        		else
        		{
        			return false;
        
        		}
        	}
        	else
        	{
        		return true;
        	}
        }
       return false;
     }
        
        public function verifierSolde ($id_personne,$debut_annee_reference, $fin_annee_reference,$annee_reference,$nombre_jours)
        {
        	$conge = new Default_Model_Conge();
        	$solde = new Default_Model_Solde();
        	$solde = $solde->find($id_personne,$annee_reference);
        	$solde = $solde->getTotal_cp()+$solde->getTotal_q1()+$solde->getTotal_q2();
        	$conge = $conge ->somme_solde_annuel_confe($id_personne, $debut_annee_reference, $fin_annee_reference);
        	if ($solde <=($conge[0]['sum(nombre_jours)']+$nombre_jours))
        	{
        		
        		return true;
        	}
        	else return false;
        }

        
	public function calendrier($tableau_id,$debut_mois,$fin_mois)
	{
		$month =$_SESSION['salut']['mois'];
		$fin_mois1=date('Y-m-d',mktime(0,0,0,$month+1,0,$_SESSION['salut']['annee']));
		$debut_mois1=date('Y-m-d',mktime(0,0,0,$month,1,$_SESSION['salut']['annee']));
		
		$conge = new Default_Model_Conge();  
		$propostion = new Default_Model_Proposition();
		$nb_jr_ouv_mois_fr = $conge->joursOuvresDuMois($debut_mois1,$fin_mois1);
		$nb_jr_ouv_mois_ma = $propostion->joursOuvresDuMois($debut_mois1,$fin_mois1);
		
		$typeconge = new Default_Model_TypeConge();
		$result_set_types = $typeconge->fetchAll($str=array());
		$tableau_types = array();
		foreach($result_set_types as $p)
		{
			$tableau_types[$p->getId()] = $p->getCode();
		}
		
		$personne = new Default_Model_Personne();
		$conge = new Default_Model_Conge();
		$tableau_doublon = array();
		$tableau_non_doublon = array();
		
		if (count($tableau_id))
		{
			 
			$doublont = $conge->DoublontAuNiveauPole( $tableau_id, $debut_mois,  $fin_mois);
			$nondoublont = $conge->CongesNondoublontPole( $tableau_id,$debut_mois,$fin_mois) ;
			for($i=0;$i<count($nondoublont );$i++)
			{
				$tableau_non_doublon[$i]= $nondoublont[$i]['id_personne'];
			}
			for($i=0;$i<count($doublont);$i++)
			{
				$tableau_doublon[$i]= $doublont[$i]['id_personne'];
				
			}
			
		}
		
		else 
		{
			$doublont = $conge->doublont($debut_mois,$fin_mois);
			$nondoublont = $conge->CongesNondoublont( $debut_mois,$fin_mois) ;
			for($i=0;$i<count($nondoublont );$i++)
			{
				$tableau_non_doublon[$i]= $nondoublont[$i]['id_personne'];
			}
			for($i=0;$i<count($doublont);$i++)
			{
				$tableau_doublon[$i]= $doublont[$i]['id_personne'];
				
			}
		
		}
		$calendrier = array();
		
		if (count($tableau_non_doublon))
		{
			
			for($j=0;$j<count($tableau_non_doublon);$j++)
			{
				
				if (!(in_array($tableau_non_doublon[$j],$tableau_doublon)))
				{
					$reponse2 = $personne->obtenirresources($tableau_non_doublon[$j],$debut_mois,$fin_mois);
				
						$t=0;
						$calendrier[$j][$t]['nom']=$reponse2[0]['nom'];
						$calendrier[$j][$t]['id']=$reponse2[0]['id'];
						$calendrier[$j][$t]['prenom']=$reponse2[0]['prenom'];
						$calendrier[$j][$t]['centre_service']=$reponse2[0]['centre_service'];
						$calendrier[$j][$t]['date_debut']=new Zend_Date($reponse2[0]['date_debut']);
						$calendrier[$j][$t]['date_fin']=new Zend_Date($reponse2[0]['date_fin']);
						$calendrier[$j][$t]['nombre_jours']=$this->calculNombreJour(1,$calendrier[$j][$t]['date_debut'],$calendrier[$j][$t]['date_fin'],$reponse2[0]['centre_service'],$reponse2[0]['nombre_jours']);
						$calendrier[$j][$t]['id_type_conge']=$tableau_types[$reponse2[0]['id_type_conge']];
						$calendrier[$j][$t]['mi_debut_journee']=$reponse2[0]['mi_debut_journee'];
						$calendrier[$j][$t]['mi_fin_journee']=$reponse2[0]['mi_fin_journee'];
						
				}
				else 
				{
					$reponse2 = $personne->obtenirresources($tableau_non_doublon[$j],$debut_mois,$fin_mois);
					for ($i=0;$i<count($reponse2);$i++)
					{
						$sommejours =array();
						$totaljours =0;
						for ($l=0;$l<count($reponse2);$l++)
						{
							$date_debut = new Zend_Date($reponse2[$l]['date_debut']);
							$date_fin = new Zend_Date($reponse2[$l]['date_fin']);
							$somme[$l] = $this->calculNombreJour(0,$date_debut,$date_fin,$reponse2[$l]['centre_service'],$reponse2[$l]['nombre_jours']);
							$totaljours = $totaljours + $somme[$l];
								
						}
						
					 	
						if ($reponse2[$i]['centre_service'] ==1) 
						{
							$totaljours = $totaljours -  $nb_jr_ouv_mois_ma;
						}
						elseif($reponse2[$i]['centre_service'] ==0) 
						{
							$totaljours = $totaljours - $nb_jr_ouv_mois_fr; 
						}
						$calendrier[$j][$i]['nom']=$reponse2[$i]['nom'];
						$calendrier[$j][$i]['id']=$reponse2[$i]['id'];
						$calendrier[$j][$i]['prenom']=$reponse2[$i]['prenom'];
						$calendrier[$j][$i]['centre_service']=$reponse2[$i]['centre_service'];
						$calendrier[$j][$i]['date_debut']=new Zend_Date($reponse2[$i]['date_debut']);
						$calendrier[$j][$i]['date_fin']=new Zend_Date($reponse2[$i]['date_fin']);
						$resu =$conge->RecupererLeNombreConge( $tableau_non_doublon[$j],$reponse2[$i]['date_debut']);
						
						
						$calendrier[$j][$i]['nombre_jours']=$totaljours;
						if (($reponse2[$i]['date_debut']==$reponse2[$i]['date_fin']) && (count($resu)==2) )
						{
							
								$type =array();
								$calendrier[$j][$i]['mi_fin_journee']=1;
								$calendrier[$j][$i]['mi_debut_journee']=1;
							
								for ($l=0;$l<count($resu);$l++)
								{
									$type[$l]=$resu[$l]['id_type_conge'];
								
									
										if ($reponse2[$i]['mi_debut_journee'] ==1)
										{
											$calendrier[$j][$i]['id_type_conge'] =$tableau_types[$reponse2[$i]['id_type_conge']];
											$calendrier[$j][$i]['id_type_conge2'] =$tableau_types[$type[$l]];
										}
										elseif($reponse2[$i]['mi_fin_journee'] ==1)
										{
											$calendrier[$j][$i]['id_type_conge'] =$tableau_types[$type[$l]];
											$calendrier[$j][$i]['id_type_conge2'] = $tableau_types[$reponse2[$i]['id_type_conge']] ;
										}
									
								}	
							
							
						}
						else
						{
						$calendrier[$j][$i]['id_type_conge']=$tableau_types[$reponse2[$i]['id_type_conge']];
						$calendrier[$j][$i]['mi_debut_journee']=$reponse2[$i]['mi_debut_journee'];
						$calendrier[$j][$i]['mi_fin_journee']=$reponse2[$i]['mi_fin_journee'];
						$calendrier[$j][$i]['id']=$reponse2[$i]['id'];
					
						}
					}
				}
			}
			return($calendrier) ;
		}
		
	}

	// PTRI - à supprimer
	/*
	 * calculNombreJour
	 * définition : calcul le nombre de jours de congés pour un ressource
	 * paramètres :
	 * $flag			: ???
	 * $date_debut		: date de début renseignée dans le formulaire (jour inclus)
	 * $date_fin		: date de fin renseignée dans le formulaire (jour inclus)
	 * $centre_cervice	: distinction congé FRANCE ou MAROC
	 * $nombre_jours	: ???
	 */
	public function calculNombreJour($flag,$date_debut,$date_fin,$centre_cervice,$nombre_jours)
	{
		$month =$_SESSION['salut']['mois'];
		$fin_mois=date('Y-m-d',mktime(0,0,0,$month+1,0,$_SESSION['salut']['annee']));
		$debut_mois=date('Y-m-d',mktime(0,0,0,$month,1,$_SESSION['salut']['annee']));
		$conge = new Default_Model_Conge();
		$propostion = new Default_Model_Proposition();
		$nb_jr_ouv_mois_fr = $conge->joursOuvresDuMois($debut_mois,$fin_mois);
		$nb_jr_ouv_mois_ma = $propostion->joursOuvresDuMois($debut_mois,$fin_mois);
	
		// (date_fin->mois  > session.mois)  et (date_debut->mois  < session.mois)  
		if (($date_fin->get(Zend_Date::MONTH)>$_SESSION['salut']['mois']) &&($date_debut->get(Zend_Date::MONTH)<$_SESSION['salut']['mois']))
		{
			return 0;		
		}
		// Mohamed khalil TAKAFI 
		// (date_fin->mois  == session.mois)  et (date_debut->mois  == session.mois)  
		elseif((($date_fin->get(Zend_Date::MONTH)== $_SESSION['salut']['mois']) &&($date_debut->get(Zend_Date::MONTH)== $_SESSION['salut']['mois'])))	
		{
			if (($centre_cervice==1)) 
			{
				return 	$propostion->joursOuvresDuMois($debut_mois,$date_fin) - $nombre_jours  ; 
				
			}
			elseif (($centre_cervice==0)) 
			{
				return 	$conge->joursOuvresDuMois($debut_mois,$fin_mois) - $nombre_jours ; 
			}
		
		}
		// (date_fin->mois  == session.mois)  et (date_debut->mois < session.mois)  		
		elseif((($date_fin->get(Zend_Date::MONTH)== $_SESSION['salut']['mois']) &&($date_debut->get(Zend_Date::MONTH)< $_SESSION['salut']['mois'])))	
		{
			
			if (($centre_cervice==1)) 
			{
				return 	$nb_jr_ouv_mois_ma - $propostion->joursOuvresDuMois($debut_mois,$date_fin);	
			}
			elseif (($centre_cervice==0)) 
			{	
				return 	 $nb_jr_ouv_mois_fr - $conge->joursOuvresDuMois($debut_mois,$date_fin); 
				
			}
		
		}
		// (date_fin->mois  > session.mois)  et (date_debut->mois < session.mois) 
		elseif((($date_fin->get(Zend_Date::MONTH)> $_SESSION['salut']['mois']) &&($date_debut->get(Zend_Date::MONTH)== $_SESSION['salut']['mois'])))	
		{
			
			if (($centre_cervice==1)) 
			{
				
				return 	$nb_jr_ouv_mois_ma - $propostion->joursOuvresDuMois($date_debut,$fin_mois);
				
			}
			elseif (($centre_cervice==0)) 
			{
				
				return 	 $nb_jr_ouv_mois_fr - $conge->joursOuvresDuMois($date_debut,$fin_mois);
				
			}
		
		}
		
							
							
	}


	/*
	 * PTRI - Ajout des fonctions de calcul de jour de congés
	 */
/*
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

	function jours_feries_maroc($annee) 
	{
		//global $logger;
		////$logger->debug("appel en base");

		$logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('php://output');
		$logger->addWriter($writer);

		$ferie = new Default_Model_Ferie();
		$jours_feries_csm = $ferie->fetchAll("annee_reference = '".$annee."'");
		foreach ($jours_feries_csm as $j) {
			$jours_feries_csm_dates[] = $j->getDate_debut();
		}

		//	$logger->log($jours_feries_csm[0]->getDate_debut(), Zend_Log::INFO);
		 
		return $jours_feries_csm_dates;
	}

	function jours_feries($annee, $alsacemoselle=false, $maroc=false)
	{
		if (!$maroc) {
			$jours_feries = array
			(    $this->dimanche_paques($annee)
			,    $this->lundi_paques($annee)
			,    $this->jeudi_ascension($annee)
			,    $this->lundi_pentecote($annee)

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
				$jours_feries[] = $this->vendredi_saint($annee);
			}
			sort($jours_feries);
			return $jours_feries;
		}
		else {
			return $this->jours_feries_maroc($annee);
		}
	}

	function est_ferie($jour, $alsacemoselle=false, $maroc=false)
	{
		$jour = date("Y-m-d", strtotime($jour));
		$annee = substr($jour, 0, 4);
		return in_array($jour, $this->jours_feries($annee, $alsacemoselle, $maroc));
	}

	// Indique si une date doit être normalisée ou non
	function a_normaliser($date,$maroc=false) {
		global $logger;

		if (in_array(date_format($date, 'l'),array('Saturday','Sunday'))
		|| $this->est_ferie(date_format($date, 'Y-m-d'),false,$maroc)) {
			return true;
		}

		return false;
	}

	// On normalise un flag midi par rapport à une date non normalisée
	function normaliser_flag_midi($date,$midi,$maroc=false) {
		global $logger;

		// Si la date de début ou fin congé tombe un WE ou JF, le flag midi ne peut pas être actif
		if ($this->a_normaliser($date,$maroc)) {
			$midi = false;
		}

		return $midi;
	}

	// Si la date de début de congé tombe un WE ou JF, on l'avance au 1er JO
	function normaliser_date_debut_conge($date,$maroc=false) {
		global $logger;
		//$logger->log(date_format($date, 'l d F Y'),Zend_Log::INFO);

		while (in_array(date_format($date, 'l'),array('Saturday','Sunday'))
		|| $this->est_ferie(date_format($date, 'Y-m-d'),false,$maroc)) {
			$date->add(new DateInterval("P1D"));
		}

		//$logger->debug(date_format($date, 'l d F Y'));

		return $date;
	}

	// Si la date de fin de congé tombe un WE ou JF, on la retarde au dernier JO
	function normaliser_date_fin_conge($date,$maroc=false) {
		global $logger;
		//$logger->debug(date_format($date, 'l d F Y'));

		while (in_array(date_format($date, 'l'),array('Saturday','Sunday'))
		|| $this->est_ferie(date_format($date, 'Y-m-d'),false,$maroc)) {
			$date->sub(new DateInterval("P1D"));
		}

		//$logger->debug(date_format($date, 'l d F Y'));

		return $date;
	}

	function calculer_jours_conges($date_debut,$date_fin,$debut_midi=false,$fin_midi=false,$maroc=false) {
		global $logger;
		$nombre_jours_conges = 0;

		// Normaliser : commencer par les flag midi...
		$debut_midi = $this->normaliser_flag_midi($date_debut,$debut_midi,$maroc);
		$fin_midi = $this->normaliser_flag_midi($date_fin,$fin_midi,$maroc);
		//... terminer par les dates
		$date_debut = $this->normaliser_date_debut_conge($date_debut,$maroc);
		$date_fin = $this->normaliser_date_fin_conge($date_fin,$maroc);

		// Parcourir l'intervalle
		$date_iterator = $date_debut;
		while ($date_iterator <= $date_fin) {

			// Loguer les jours ouvrés (tous les jours sauf les samedi, dimanche, fériés)
			$weekday = date_format($date_iterator, 'l');
			if (!in_array($weekday,array('Saturday','Sunday'))
		 && !$this->est_ferie(date_format($date_iterator, 'Y-m-d'),false,$maroc)) {
		 	//$logger->debug(date_format($date_iterator, 'l d F Y'));
		 	$nombre_jours_conges++;
		 }
		 else {
		 	//$logger->debug(date_format($date_iterator, 'l d F Y')." WE ou férié, non décompté dans les congés");
		 }

		 // Incrémenter l'iterator
		 $date_iterator->add(new DateInterval("P1D"));
		}

		// Traiter les demi journées
		if ($debut_midi) {
			$nombre_jours_conges = $nombre_jours_conges - 0.5;
		}
		if ($fin_midi) {
			$nombre_jours_conges = $nombre_jours_conges - 0.5;
		}

		return $nombre_jours_conges;
	}

	function calculer_jours_ouvres($date_debut,$date_fin) {
		global $logger;
		$nombre_jours_ouvres = 0;

		// Parcourir l'intervalle
		$date_iterator = $date_debut;
		while ($date_iterator <= $date_fin) {
			// Loguer les jours ouvrés (tous les jours sauf les samedi, dimanche, fériés)
			$weekday = date_format($date_iterator, 'l');
			if (!in_array($weekday,array('Saturday','Sunday'))
			 && !$this->est_ferie(date_format($date_iterator, 'Y-m-d'),false,false)) {
		 		$nombre_jours_ouvres++;
		 	}
			else {
				//$logger->debug(date_format($date_iterator, 'l d F Y')." WE ou férié, non décompté dans les congés");
			}
	
			// Incrémenter l'iterator
			$date_iterator->add(new DateInterval("P1D"));
		}

		return $nombre_jours_ouvres;
	}
*/
	/*
	 * PTRI - FIN des fonctions de calcul de nombre de jours de congés
	 */

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
		 * Q2 : initialisé à l'écran de gestion des soldes
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
		$mois_entree = date_format($date_entree, 'n'); // mois au format 1 à 12
		$jour_entree = date_format($date_entree, 'j'); // mois au format 1 à 31

		// Calcul des CP
		if ($annee_entree == $annee_reference) {
			if ($mois_entree < 6) {
				$cp = 2.25 * (6 - $mois_entree);
				if ($jour_entree >= 15) {
					$cp -= 2.25;
				}
			}
			else {
				$cp = 0;
			}
		}
		elseif ($annee_entree == $annee_reference - 1) {
			if ($mois_entree < 6) {
				$cp = 27;
			}
			else {
				$cp = 2.25 * (5 + 12 - $mois_entree + 1);
				if ($jour_entree >= 15) {
					$cp -= 2.25;
				}
			}
		}
		else {
			$cp = 27;
		}
		
		// Calcul des CP Ancienneté
		$annee_reference = new DateTime($annee_reference.'-06-01');
		echo date_format($annee_reference, 'd-m-Y').'<BR>';
		$interval = $date_entree->diff($annee_reference);
		$i = $interval->format('%y');
		if ($i >= 2 && $i < 3) {
			$cpa = 1;
		}
		elseif ($i >= 3 && $i < 5) {
			$cpa = 2;
		}
		elseif ($i >= 5 && $i < 8) {
			$cpa = 3;
		}
		elseif ($i >= 8) {
			$cpa = 4;
		}
				
		// Calcul des RTT Q1
		$annee_reference = date_format($annee_reference, 'Y');
		$debut_annee = new DateTime($annee_reference.'-01-01');
		$fin_annee = new DateTime($annee_reference.'-12-31');
		$nb_jo = $this->calculer_jours_ouvres($debut_annee,$fin_annee);
		
		$nb_rtt_ms = 7.4*($nb_jo-25-12)+7>1607 ? 13 : 12;
		$nb_rtt_rm_ac = $nb_jo-25-218<10 ? 10 : $nb_jo-25-218;
		
		$modalite = new Default_Model_Modalite();
		$modalite = $modalite->find($ressource->getId_modalite());
		$modalite = $modalite->getCode();
		
		if ($modalite == "MS") {
			$q1 = 7.4*($nb_jo-25-12)+7>1607 ? 13 : 12;
		}
		elseif ($modalite == "RM" || $modalite == "AC") {
			$q1 = $nb_jo-25-218<10 ? 10 : $nb_jo-25-218;
		}
		elseif ($modalite == "NO") {
			$q1 = 0;
		}
		else {
			$q1 = 10;
		}
	
		// Pour les nouveaux entrants, appliquer un prorata
		if ($annee_entree == $annee_reference) {
			$nb_mois_complets = 12 - $mois_entree + 1;
			if ($jour_entree >= 15) {
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

}
