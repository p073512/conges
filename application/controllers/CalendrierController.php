<?php
class CalendrierController extends Zend_Controller_Action
{
	//action par défaut

	public function calendriermensuelAction()
	{
		
		//Zend_Session::start();
		$mois = new Zend_Session_Namespace('salut',false);
		//$mois->mois = (int)$m;// IL FAUT PROPGRAMMER UNE FONCTION AU NIVEAU DE BOOSTTRAP POUR INITIALISER LES SESSIONS
		//$mois->annee = $y;
		
		$form = new Default_Form_Filtre();
		//$form->setAction($this->view->url(array( 'controller' => 'calendrier', 'action' => 'calendriermensuel'), 'default', true));
		$form->submit_ca->setLabel('Filtrer');
		$this->view->form = $form;
		
		$form2 = new Default_Form_ChoixMois();
		$form2->setAction($this->view->url(array( 'controller' => 'calendrier', 'action' => 'calendriermensuel'), 'default', true));
		$this->view->form2 = $form2;
		 
		$form3 = new Default_Form_ChoixAnnee();
		$form3->setAction($this->view->url(array( 'controller' => 'calendrier', 'action' => 'calendriermensuel'), 'default', true));
		$this->view->form3 = $form3;
		$this->view->title_filtre = "Filtrer sur ressources";
		$this->view->title_choix = "Filtre sur une periode";
		$this->view->title = "Calendrier Mensuel";
		
		
		// recuperation du premier de mois et de la fin du mois actuel
		if ($this ->_getParam ('mois') )
		{
			$mois->mois = $this ->_getParam ('mois');
			$mois->annee = $this ->_getParam ('annee');
			
		}
		
		if(!($this->_request->ispost()))
		{
			$fin_mois=date('Y-m-d',mktime(0,0,0,$_SESSION['salut']['mois']+1,0,$_SESSION['salut']['annee']));
			$debut_mois=date('Y-m-d',mktime(0,0,0,$_SESSION['salut']['mois'],1,$_SESSION['salut']['annee']));
			$personne = new Default_Model_Personne();
			$reponse = $this->_helper->validation->calendrier($str=array(),$debut_mois,$fin_mois);
			
			if (count($reponse)== 0)
			{
				echo'<strong ><span style="color:red">Tous le monde bosse ce mois </span ></strong>';
							
			} 
			
			$this->view->calendrierArray = $reponse;
			$this->view->mois  = $_SESSION['salut']['mois'];
			$this->view->annee = $_SESSION['salut']['annee'];
		
		}
		if ($this->_request->ispost())
		{
			$data = $this->_request->getPost();
			
			if (!(array_key_exists('num_mois', $data)|| array_key_exists('num_annee', $data))) 
			{
				if($form->isValid($data))
				{
					$set_personnes =array();
					$fin_mois=date('Y-m-d',mktime(0,0,0,$_SESSION['salut']['mois']+1,0,$_SESSION['salut']['annee']));
					$debut_mois=date('Y-m-d',mktime(0,0,0,$_SESSION['salut']['mois'],1,$_SESSION['salut']['annee']));
					$personne = new Default_Model_Personne();
					$id_pole = $form->getValue('id_pole_ca');
					$id_fonction = $form->getValue('id_fonction_ca');
					$id_entite =  $form->getValue('id_entite_ca');
					
					if ($id_pole!=0 && $id_entite==0 && $id_fonction==0 ) 
					{
						$set_personnes= $personne->fetchAll('id_pole ='.$id_pole );
					}
					elseif ($id_pole==0 && $id_entite!=0 &&$id_fonction==0 ) 
					{
						$set_personnes= $personne->fetchAll( 'id_entite ='.$id_entite );
					}
					elseif ($id_pole==0 && $id_entite==0 &&$id_fonction!=0 ) 
					{
						$set_personnes= $personne->fetchAll( 'id_fonction ='.$id_fonction );
					}
					elseif (($id_pole!=0 && $id_entite!=0) &&$id_fonction==0 ) 
					{
						$set_personnes= $personne->fetchAll('id_pole ='.$id_pole .'&&'. 'id_entite ='.$id_entite );
					}
					elseif (($id_fonction!=0 && $id_entite!=0) &&$id_pole==0 ) 
					{
						$set_personnes= $personne->fetchAll( 'id_fonction ='.$id_fonction .'&&'. 'id_entite ='.$id_entite );
					}
					elseif (($id_fonction!=0 && $id_pole!=0) &&$id_entite==0 ) 
					{
						$set_personnes= $personne->fetchAll('id_pole ='.$id_pole .'&&'. 'id_fonction ='.$id_fonction );
					}
					elseif ($id_fonction!=0 && $id_pole!=0 &&$id_entite!=0 ) 
					{
						$set_personnes= $personne->fetchAll('id_pole ='.$id_pole .'&&'. 'id_fonction ='.$id_fonction .'&&'. 'id_entite ='.$id_entite );
					}

				}

				$tableau_personnes = array();
				$reponse= array();
				foreach($set_personnes as $p)
				{
					$tableau_personnes[] = $p->getId();
				}
				
				if (count($tableau_personnes) == 0)
				{
					echo'<strong ><span style="color:red">Tous le monde bosse ce mois </span ></strong>';
					
				}
				else 
				
				$reponse = $this->_helper->validation->calendrier($tableau_personnes,$debut_mois,$fin_mois); 
				$this->view->calendrierArray = $reponse;
				$this->view->mois = $_SESSION['salut']['mois'];
				$this->view->annee = $_SESSION['salut']['annee'];
			}
	 		else 
	 		{
		 			if (array_key_exists('num_mois', $data))
		 			{
		 				$_SESSION['salut']['mois'] = $data['num_mois'];
		 			}
		 			
		 			if (array_key_exists('num_annee', $data))
		 			{
		 				$_SESSION['salut']['annee'] = $data['num_annee'];
		 			}
	 	
				 	
	 				$fin_mois=date('Y-m-d',mktime(0,0,0,$_SESSION['salut']['mois']+1,0,$_SESSION['salut']['annee']));
					$debut_mois=date('Y-m-d',mktime(0,0,0,$_SESSION['salut']['mois'],1,$_SESSION['salut']['annee']));
					$personne = new Default_Model_Personne();
					$reponse = $this->_helper->validation->calendrier($str=array(),$debut_mois,$fin_mois);
	 				
					if (count($reponse)==0)
					{
						echo'<strong ><span style="color:red">il n y a personne qui ne bosse pas ce mois </span ></strong>';
						
					}

					$this->view->calendrierArray = $reponse;
					$this->view->mois = $_SESSION['salut']['mois'];
					$this->view->annee = $_SESSION['salut']['annee'];
	 		
	 		}
		}

	}

	public function calendrierannuelAction()
	{
	
		$id_personne =$this ->_getParam ('id');
		for ($mois_A=1;$mois_A<=12;$mois_A++)
		{	
			$fin_mois=date('Y-m-d',mktime(0,0,0,$mois_A+1,0,$_SESSION['salut']['annee']));
			$debut_mois=date('Y-m-d',mktime(0,0,0,$mois_A,1,$_SESSION['salut']['annee']));
			$personne = new Default_Model_Personne();
			$reponse = $this->_helper->validation->calendrier($id_personne,$debut_mois,$fin_mois);
			$conge = new Default_Model_Conge();
			$propostion = new Default_Model_Proposition();
			$tableau_jours_feries=$conge->chercher_jours_feriers($debut_mois, $fin_mois);
			$ferie = new Default_Model_Ferie();
			$jours_feries_marocain = $ferie->RecupererLesJoursFeries($_SESSION['salut']['annee']);
			$nb= count($jours_feries_marocain );
			$tableau = array();
			for ($i=0;$i<$nb;$i++)
			{
				$tableau[$i]=$jours_feries_marocain[$i]['date_debut'];
				
			}
					
			$vac= new Default_Model_Vacances();
			$vac = $vac->jours_vacances( $debut_mois, $fin_mois);
			$comp = 0;
			$resultat[$mois_A]['A']=array();
			$resultat[$mois_A]['B']=array();
			$resultat[$mois_A]['C']=array();
			for ($comp =0;$comp<count($vac);$comp++)
			{
				
				if ($vac[$comp]['date_debut']>=$debut_mois && $vac[$comp]['date_fin']<=$fin_mois)
				{
					$debut=	new Zend_Date($vac[$comp]['date_debut']);	
					$fin = new Zend_Date($vac[$comp]['date_fin']);
					$resultat [$mois_A][$vac[$comp]['zone']]=($this->_helper->validation->CompteurJours($debut,$fin));
				
				}
				elseif ($vac[$comp]['date_debut']< $debut_mois && $vac[$comp]['date_fin']<$fin_mois)
				{
					$fin = new Zend_Date($vac[$comp]['date_fin']);
					$debut =  new Zend_Date($debut_mois);
					$resultat[$mois_A][$vac[$comp]['zone']]=($this->_helper->validation->CompteurJours($debut,$fin));
				}
				elseif ($vac[$comp]['date_debut']< $fin_mois && $vac[$comp]['date_fin']>$fin_mois && $vac[$comp]['date_debut']> $debut_mois )
				{
					$fin =  new Zend_Date($fin_mois);
					$debut =  new Zend_Date($vac[$comp]['date_debut']);
					$resultat [$mois_A][$vac[$comp]['zone']]=($this->_helper->validation->CompteurJours($debut,$fin));
				}
				elseif ($vac[$comp]['date_debut']< $debut_mois && $vac[$comp]['date_fin']>$fin_mois)
				{
					$fin = new Zend_Date($fin_mois);
					$debut =  new Zend_Date($debut_mois);
					$resultat [$mois_A][$vac[$comp]['zone']]=($this->_helper->validation->CompteurJours($debut,$fin));
				}
					
			}
			
			
			$timestamp = mktime (0, 0, 0,$mois_A, 01, $_SESSION['salut']['annee']);
			 $nombreDeJoursDeMois = intval(date("t",$timestamp));
			
			
			$feries=array();
			$k=1;
			$var = strtotime($debut_mois );
			for($k==1;$k<=$nombreDeJoursDeMois;$k++)
			{
				
				
				if(in_array(date('w', $var), array(0, 6)))
				{
					$feries[$k]='<div class="colorgrisA">WE</div>';
				}
				elseif((((in_array(date('j_n_'.date('Y', $var), $var), $tableau_jours_feries))&&($reponse[0][0]['centre_service']== 0))||((in_array(date(date('Y', $var).'-m-d', $var), $tableau))&&($reponse[0][0]['centre_service']==1))) && !(in_array(date('w', $var), array(0, 6))) ) 
				{
					if ((in_array(date('j_n_'.date('Y', $var), $var), $tableau_jours_feries))&&(in_array(date(date('Y', $var).'-m-d', $var), $tableau)))
					{
						$feries[$k] = '<div class="FCA">FC</div>';
						
					}
					if((in_array(date('j_n_'.date('Y', $var), $var), $tableau_jours_feries))&&($reponse[0][0]['centre_service']==0))
					{
						$feries[$k]=  '<div class="FFA"> FF</div>';
						
					}
					if((in_array(date(date('Y', $var).'-m-d', $var), $tableau))&&($reponse[0][0]['centre_service']==1))
					{
						$feries[$k]=   '<div class="FFA">FM</div>';
						
					}
				}
			
			 $var = mktime(date('H', $var), date('i', $var), date('s', $var), date('m', $var), date('d', $var)+1, date('Y', $var));
			}
			
			
			$i=0;
			$t=0;
			$tab =array();
			$conge_type =array();
			for($i==0;$i<count ($reponse[0]);$i++) 
			{
				$date1[$i] = $reponse[0][$i]['date_debut']->get(Zend_Date::DAY);
				$date2[$i] = $reponse[0][$i]['date_fin']->get(Zend_Date::DAY);
				$type_conge[$i]= $reponse[0][$i]['id_type_conge'];
				$mi_debut_journee[$i]= $reponse[0][$i]['mi_debut_journee'];
				$mi_fin_journee[$i]= $reponse[0][$i]['mi_fin_journee'];
				
				$j=0;
				for($j==1;$j<=($date2[$i]-$date1[$i]);$j++)
				{
					$tab[$t]=  $date1[$i]+$j;
					
					if ($mi_debut_journee[$i]==1 && $mi_fin_journee[$i]==0)
					{
						$conge_type[$t]="<div class=".$type_conge[$i]."A_1>".$type_conge[$i]."</div>";
					}
					elseif ($mi_debut_journee[$i]==0 && $mi_fin_journee[$i]==1)
					{
						$conge_type[$t]="<div class=".$type_conge[$i]."A_2>".$type_conge[$i]."</div>";
					}
					elseif ($mi_debut_journee[$i]==1 && $mi_fin_journee[$i]==1)
					{
						$type_conge[$i]= "<div class=".$reponse[0][$i]['id_type_conge']."A_1>".$reponse[0][$i]['id_type_conge']."</div>"."<div class=".$reponse[0][$i]['id_type_conge2']."A_22>".$reponse[0][$i]['id_type_conge2']."</div>";
						$conge_type[$t]= $type_conge[$i];
					}
					elseif($mi_debut_journee[$i]==0 && $mi_fin_journee[$i]==0) {$conge_type[$t]="<div class=".$type_conge[$i]."A>".$type_conge[$i]."</div>";}
					$t++;
				
				}
			}
			$resultat[$mois_A]['conge']=array_flip($tab);
			$resultat[$mois_A]['type_conge']= $conge_type;
			$resultat[$mois_A]['feries']= $feries;
		}
		
		$this->view->calendrierArray =  $resultat;
		
		
		
	}
}