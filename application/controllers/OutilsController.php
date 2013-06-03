<?php
class OutilsController extends Zend_Controller_Action
{
	  public function preDispatch() 
	  {
	    	    $doctypeHelper = new Zend_View_Helper_Doctype();
	            $doctypeHelper->doctype('HTML5');
	    		$this->_helper->layout->setLayout('mylayout');      
	  }
	  
	  public function calendrierMensuelBetaAction()
	  {
	     $conges = array();
	     $keysIndiceConge = array();
	 
	  	//requête Ajax reçue 
			if($this->getRequest()->isXmlHttpRequest())
			{
	     	//récupération des données envoyées en ajax
				$data = $this->getRequest()->getPost();
	  	
			  if(isset($data['annee']) && isset($data['mois']))
		  {
		  	
				if(isset($data['id_personne']) && $data['id_personne'] !== 'x')
				{
				
					  $personne = new Default_Model_Personne();
		              $personne->find($data['id_personne']);
		              $cs = $personne->getEntite()->getCs();
		              
		              if($cs == '1') $cs = true ; else $cs = false; 
		              
		              $congeObj = new Default_Model_Conge();
					  $congeArray = array();
					  
					  $outils = new Default_Controller_Helpers_outils();
					   	$jferies = $outils->setJoursFerie($data['annee'],$cs,false);
					    $jferies = (array) $jferies ;
					
					  
					 $dateDebut = '01-' . $data['mois'] . '-' . $data['annee'];
					 
					if($data['mois'] == '1') // : février ( Janvier = 0) 
					{
		             if( ((($data['annee'] % 4) == 0) && ((($data['annee'] % 100) != 0) || (($data['annee'] %400) == 0)))) // année bissextile
		             {
		             	$dateFin = '29-' . $data['mois'] . '-' . $data['annee'];
		             
		             }
		             else // année non bissextile
		             {
		             	$dateFin = '28-' . $data['mois'] . '-' . $data['annee'];
					 }
					}
					else if( $data['mois'] == '3' || $data['mois'] == '5' || $data['mois'] == '8' || $data['mois'] == 10  ) // avril / juin / Septembre / Novembre
					{
						 $dateFin = '30-' . $data['mois'] . '-' . $data['annee'];
					}
					else // Janvier/ Mars /Mai / Juillet /Aout / Octobre /Décembre
					{
						$dateFin = '31-' . $data['mois'] . '-' . $data['annee'];
					}
					  
					  $congeArray = $congeObj->conges_existant($personne->getId(), $dateDebut, $dateFin, '0');

					  foreach ($congeArray as $k=>$v)
					 {
					  if($congeArray[$k]['mi_debut_journee'] == '0') $dm = false; else if($congeArray[$k]['mi_debut_journee'] == '1') $dm = true;
					  if($congeArray[$k]['mi_fin_journee'] == '0') $fm = false; else if($congeArray[$k]['mi_fin_journee'] == '1') $fm = true;
					  
					   $conge = $outils->getPeriodeDetails($data['annee'],$congeArray[$k]['date_debut'] ,$congeArray[$k]['date_fin'],$dm,$fm,$cs,false );
					   $indiceConge = explode("-",$congeArray[$k]['date_debut']);
					   $indiceConge = $indiceConge['0'] . '-' .  $indiceConge['1']; // indice conge sous format Annee-mois

					   if(isset($keysIndiceConge[$indiceConge]))
					   {
					   	 $keysIndiceConge[$indiceConge] = $keysIndiceConge[$indiceConge] + 1;
					   }
					   else
					   {
					   	$keysIndiceConge[$indiceConge] = 0;
					   	
					   }
					   
					   $conges[$indiceConge][$keysIndiceConge[$indiceConge]] = (array) $conge;
					  
				
					   
					  
					 
					 }
		  
					
					  
					  $ressources = '{"ressources" :
				                             {"0" : {
				                             	  "id_personne" : "'. $personne->getId() .'",
				                             	  "Nom" : "'. $personne->getNomPrenom() .'",
				                                  "Pole" : "'. $personne->getPole()->getLibelle() .'",
				                                  "Entite" : "'. $personne->getEntite()->getLibelle().'",
				                                  "Fonction" :"'. $personne->getFonction()->getLibelle() .'",
				                                  "cs" : "'. $personne->getEntite()->getCs() .'",
				                                  "conge" :
				                                  '.json_encode($conges).'}},
				                                  
                                "Ferie" : '.json_encode($jferies['joursFerie']).'}';
                $this->_helper->viewRenderer->setNoRender(true);
				echo $ressources;
                exit;                 

					  
					  
					  
				}
				else
				{
					$this->view->error ='Choisissez une personne !';
				
				
				}
		      
		        
			
		  }
				
				
				
			} 
			else
			{ 
				$form = new Default_Form_CalendrierForm();
			  	$form->setDbOptions('personne',new Default_Model_Personne(),'getId','getNomPrenom');
			  	
			  	
			  	
			  	$this->view->form =$form;
			
			}
	  	
	  }
	  
	  public function calculNombreJoursCongeAction()
	{
		
		//cr�ation du fomulaire
		$form = new Default_Form_OutilsForm();
		//indique l'action qui va traiter le formulaire
		$form->setAction($this->view->url(array('controller' => 'outils', 'action' => 'calculNombreJoursConge'), 'default', true));

        //assigne le formulaire � la vue
		$this->view->form = $form;
		$this->view->title = "Créer un conge";
			/*************************************/
	    $conge = new Default_Model_Conge();
	  
	// requete POST 
		if($this->_request->isPost())   
		{
			
			
			// r�cup�ration des donnéees envoyées par le formulaire
			$data = $this->_request->getPost();
			
		
			// si date(s) non renseignée(s)
            if($data['dateDebut'] == '' || $data['dateFin'] == '')
            {
            	if($data['dateDebut'] =='')
            	{
            	$this->view->error = 'saisissez la date de d�but !!';
            	$form->populate($data);
            	}
            	else
            	{
            	$this->view->error = 'saisissez la date de fin !!';
            	$form->populate($data);
            	}
            }
            else if($data['dateDebut'] >$data['dateFin'] )
            {
            	$this->view->error = 'date fin doit être supperieur ou égale à date debut';
            	$form->populate($data);
            }
            else 
            {
            	
            $dateDebut = $data['dateDebut'];
			$dateFin = $data['dateFin'];
			$debutMidi = $data['DebutMidi'];
			$finMidi = $data['FinMidi'];
			$csm = $data['csm'];
			$am = $data['AlsaceMoselle'];
			$anneeReference = '2013';
			
			$conge->setDate_debut($dateDebut);
			$conge->setDate_fin($dateFin);
			$conge->setAnnee_reference($anneeReference);
			
			
			
			
			if($debutMidi == '0')
			{
				$conge->setMi_debut_journee(false);
			}
			else
			{
				$conge->setMi_debut_journee(true);
			}
			if($finMidi == '0')
			{
				$conge->setMi_fin_journee(false);
			}
			else 
			{
				$conge->setMi_fin_journee(true);
			}
			if($csm == '0' && $am == '0' )
			{//si CSM et Alsace Moselle non checkés
				$conge->CalculNombreJoursConge();
			}
			else
			{
				//CSM checké
				if($csm == '1')
				{
					$csm = true;
				}
				//Alsace Moselle checké
				if($am == '1')
				{
					$am = true;
				}	
							
				$conge->CalculNombreJoursConge($csm,$am);
			}
			

			$form->populate($data);
          }
            	
            }
			
	}

		
		
	
	  
	  
	  
}