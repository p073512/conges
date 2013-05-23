<?php
class OutilsController extends Zend_Controller_Action
{
	  public function preDispatch() 
	  {
	    	    $doctypeHelper = new Zend_View_Helper_Doctype();
	            $doctypeHelper->doctype('HTML5');
	    		$this->_helper->layout->setLayout('mylayout');      
	  }
	  
	  
	  
	  public function calculAction()
	{
		
		//création du fomulaire
		$form = new Default_Form_OutilsForm();
		//indique l'action qui va traiter le formulaire
		$form->setAction($this->view->url(array('controller' => 'outils', 'action' => 'Calcul'), 'default', true));

        //assigne le formulaire à la vue
		$this->view->form = $form;
		$this->view->title = "Créer un conge";
			/*************************************/
	    $conge = new Default_Model_Conge();
	  
	// requete POST 
		if($this->_request->isPost())   
		{
			
			
			// récupération des données envoyés par le formulaire
			$data = $this->_request->getPost();
			
		
			// si date(s) non renseignée(s)
            if($data['dateDebut'] == '' || $data['dateFin'] == '')
            {
            	if($data['dateDebut'] =='')
            	{
            	$this->view->error = 'saisissez la date de début !!';
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
			
			
			
			
			if(!isset($debutMidi))
			{
				$conge->setMi_debut_journee(false);
			}
			else
			{
				$conge->setMi_debut_journee(true);
			}
			if(!isset($finMidi))
			{
				$conge->setMi_fin_journee(false);
			}
			else 
			{
				$conge->setMi_fin_journee(true);
			}
			if(!isset($csm) && !isset($am) )
			{//si CSM et Alsace Moselle non checkés
				$conge->CalculNombreJoursConge();
			}
			else
			{
				//CSM checké
				if(isset($csm) == 1)
				{
					$csm = true;
				}
				//Alsace Moselle checké
				if(isset($am) == 1)
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