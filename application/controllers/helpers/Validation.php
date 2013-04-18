<?php
class Default_Controller_Helpers_Validation extends Zend_Controller_Action_Helper_Abstract
{
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
        if (($date_debut < $date_fin) || (($mi_debut_journee|| $mi_fin_journee) && (($date_debut <= $date_fin)))  )
        {
        	
        	
        	
        	$proposition = new Default_Model_Proposition();
        	$proposition  = $proposition  ->fetchall('id_personne = '.$id_personne);
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
        
        				
        				
        		if ( (($date_debut <= $date_debut_base_proposition) &&($date_fin >= $date_fin_base_proposition))||(($date_debut >= $date_debut_base_proposition) &&($date_fin <= $date_fin_base_proposition)) || (($date_debut <= $date_fin_base_proposition) &&($date_fin >= $date_fin_base_proposition)) ||(($date_debut <= $date_debut_base_proposition) &&($date_fin >= $date_debut_base_proposition)))
        			{
        				if ($mi_debut_journee|| $mi_fin_journee)
        				{
        					if ((($date_debut == $date_debut_base_proposition)&&($date_fin == $date_fin_base_proposition))&&(($mi_debut_journee==$date_mi_debut_base_proposition)||($mi_fin_journee==$date_mi_fin_base_proposition)))
        					{
        						$flag_save2 =1;
        						break;
        					}
        					elseif ((($date_debut == $date_debut_base_proposition)&&($date_fin == $date_fin_base_proposition))&&(($mi_debut_journee!=$date_mi_debut_base_proposition)&&($mi_fin_journee!=$date_mi_fin_base_proposition)))
        					{
        						$flag_save =TRUE;
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
        
        public function jours_feries_csm($annee)
        {
        
        $tableau_jours_feries[0][0] = $annee.'-1-1'; 
	    $tableau_jours_feries[0][1] = $annee.'-1-11'; 
	    $tableau_jours_feries[0][2] = $annee.'-5-1'; 
	    $tableau_jours_feries[0][3] = $annee.'-7-30'; 
	    $tableau_jours_feries[0][4] = $annee.'-8-14'; 
	    $tableau_jours_feries[0][5] = $annee.'-8-20';  
	    $tableau_jours_feries[0][6] = $annee.'-8-21'; 
	    $tableau_jours_feries[0][7] = $annee.'-11-6'; 
	    $tableau_jours_feries[0][8] = $annee.'-11-18'; 
        
	    $tableau_jours_feries[1][0] =  "Jour de lan";
	    $tableau_jours_feries[1][1] =  "Anniversaire du manifeste de lindependance";
	    $tableau_jours_feries[1][2] =  "Fete du travail";
	    $tableau_jours_feries[1][3] = "Fete du trone";
	    $tableau_jours_feries[1][4] =  "Journee de Oued Ed Dahab";
	    $tableau_jours_feries[1][5] =  "Fete de la revolution du roi et du peuple" ;
	    $tableau_jours_feries[1][6] =  "Anniversaire de sa majeste Henry je sais pas vi";
	    $tableau_jours_feries[1][7] =  "Anniversaire du roi";
	    $tableau_jours_feries[1][8] =  "Fete de lindependance";

        return  $tableau_jours_feries;
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
		/* Mohamed khalil TAKAFI */
		// (date_fin->mois  == session.mois)  et (date_debut->mois  == session.mois)  
		elseif((($date_fin->get(Zend_Date::MONTH)== $_SESSION['salut']['mois']) &&($date_debut->get(Zend_Date::MONTH)== $_SESSION['salut']['mois'])))	
		{
			if (($centre_cervice==1)) 
			{
				// bug corrigé : retourne un nombre négatif 
				return 	$nombre_jours - $propostion->joursOuvresDuMois($debut_mois,$date_fin) ; // MTA : inversion de B - A => A - B
				
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
	
	public function CompteurJours($date_debut,$date_fin)
	{
		
		$date1 = $date_debut->get(Zend_Date::DAY);
		$date2 = $date_fin->get(Zend_Date::DAY);	
		$j=0;
		for($j=1;$j<=($date2-$date1)+1;$j++)
		{
			$tab[$j]=  $date1+$j-1;		
		}
	
		return $tab;
	
	}
}