<?php
class PropositionController extends Zend_Controller_Action
{
	
     public function preDispatch() /* MTA : Mohamed khalil Takafi */
    {
		
    	    $doctypeHelper = new Zend_View_Helper_Doctype();
            $doctypeHelper->doctype('HTML5');
    		$this->_helper->layout->setLayout('mylayout');
	}
 
	//:::::::::::::// ACTION INDEX //::::::::::::://
	public function indexAction()
	{
		// on ajoute le filtre sur la vue des propositions
		
		$proposition = new Default_Model_Proposition;
		//$this->view->propositionArray =$proposition->fetchAll('Etat = "NV"');

		//création de notre objet Paginator avec comme paramétre la méthode
		//récupérant toutes les entrées dans notre base de données
		$paginator = Zend_Paginator::factory($proposition->fetchAll($str=array()));
		//indique le nombre déléments é afficher par page
		$paginator->setItemCountPerPage(20);
		//récupére le numéro de la page é afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'accéder é la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		$this->view->propositionArray = $paginator;
		
		/*//création d'un d'une instance Default_Model_Users
		$proposition = new Default_Model_Proposition();

		//$this->view permet d'accéder é la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		//la valeur correspond é un tableau d'objets de type Default_Model_Users récupérés par la méthode fetchAll($str)
		//$this->view->PropositionArray = $propositon->fetchAll($str);

		//création de notre objet Paginator avec comme paramétre la méthode
		//récupérant toutes les entrées dans notre base de données
		$paginator = Zend_Paginator::factory($proposition->fetchAll($str=array()));
		//indique le nombre déléments é afficher par page
		$paginator->setItemCountPerPage(10);
		//récupére le numéro de la page é afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'accéder é la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		$this->view->propositionArray = $paginator;*/
	}

	//:::::::::::::// ACTION CREATEPROPOSITION //::::::::::::://
	public function createpropositionAction()   /* MTA : Mohamed khalil Takafi */
	{
		//création du fomulaire
		$form = new Default_Form_Proposition();
		//indique l'action qui va traiter le formulaire
		//$form->setAction($this->view->url(array('controller' => 'proposition', 'action' => 'create'), 'default', true));
		
		$data = array();
		//assigne le formulaire é la vue
		$this->view->form = $form;
		$this->view->title = "Creer Proposition";
		
		$where = array('centre_service = ?' => '1');
		$form->setDbOptions('NomPrenom',new Default_Model_Personne(),'getId','getNomPrenom',$where);
		
		 $this->_helper->viewRenderer('createproposition');
	     $this->view->form = $form;
		//si la page est POSTée = formulaire envoyé
		if($this->_request->isPost())   
		{
			//récupération des données envoyées par le formulaire
			
			$data = $this->_request->getPost();

			//vérifie que les données répondent aux conditions des validateurs
			if($form->isValid($data))  
			{
				//création et initialisation d'un objet Default_Model_Proposition
				//qui sera enregistré dans la base de données
				$proposition = new Default_Model_Proposition();
                 
			
				$proposition->setId_personne($data['NomPrenom']);
				$proposition->setDate_debut($data['date_debut']);
				$proposition->setDate_fin($data['date_fin']);
				$proposition->setMi_debut_journee($data['DebutMidi']);
				$proposition->setMi_fin_journee($data['FinMidi']);
				$proposition->setNombre_jours();
				$proposition->setEtat('NC');
				
				/*
				 * Gestion du chevauchement
				 * on appelle le helper pour verifierl'existance des proposition avant 
				 * l'enregistrement dans la base
				 */
				
				if($this->_helper->validation->verifierConges($data['NomPrenom'],$data['date_debut'],$data['date_fin'],$data['DebutMidi'],$data['FinMidi'],1,1)) //&& $this->_helper->validation->verifierPropositions($data['NomPrenom'],$data['date_debut'],$data['date_fin'],$data['DebutMidi'],$data['FinMidi']))
				{
					
					$proposition->save();

					//redirection
					$this->_helper->redirector('affichercsm');
					
				}
				else   /* MTA : Mohamed khalil Takafi */ 
				{
					$form->populate($data);
					if ($data['date_debut'] > $data['date_fin'])
					// MTA : modification du message echo "......."
					echo "<div align=center><strong><em><span style='background-color:rgb(255,0,0)'> La date de début doit être inférieure ou égale à la date de fin</span></em></strong></div>";
				
				}
			}
			else 
			{
				$form->populate($data);
				echo "<div align=center><strong><em><span style='background-color:rgb(255,0,0)'> proposition ou conge deja demande </span></em></strong></div>";
			}
		}
		else 
		{
			//si erreur rencontrée, le formulaire est rempli avec les données
			//envoyées précédemment
			$form->populate($data);
		}
	
	}
	
	
// /* MOHAMED KHALIL TAKAFI*/	
// MTA : corrigé !
	public function editAction()
	{
        $this->_helper->viewRenderer('createproposition');
		//création du fomulaire
		$form = new Default_Form_Proposition();
		//indique l'action qui va traiter le formulaire
		//$form->setAction($this->view->url(array('controller' => 'proposition', 'action' => 'edit'), 'default', true));
		$form->Valider->setLabel('Modifier');
		//assigne le formulaire à la vue
		$this->view->form = $form;
		$this->view->title = "Modifier Proposition"; //MTA
		
		//récupération des données envoyées par le formulaire
		$data_id =  $this->getRequest()->getParams();
  
		
		
		
        // recuperer des données a charger dans le formulaire 
         $proposition = new Default_Model_Proposition();
         $personne = new Default_Model_Personne();
         
         // recupere l'id personne qui a posé la proposition  
         $prop = $proposition->find($data_id['id']);
	     $id_personne =  $prop->getId_personne();  // id personne 
	  
	     $pers = $personne->find($id_personne);   // retourne l'objet personne ayant l'id "$id_personne"
	     
	     
	    // stocker les anciennes valeurs du formulaire 
		$PreData['date_debut']=  $proposition->getDate_debut();
		$PreData['DebutMidi'] = $proposition->getMi_debut_journee();
		$PreData['date_fin'] = $proposition->getDate_fin();
		$PreData['FinMidi'] = $proposition->getMi_fin_journee();
	     
	     // stocker les nouvelles valeurs du formulaire 
	     $data = array();
	     $data['_date_debut'] = $form->getElement('date_debut')->getValue();
	     $data['_mi_debut_journee'] = $form->getElement('DebutMidi')->getValue();
	     $data['_date_fin'] = $form->getElement('date_fin')->getValue();
	     $data['_mi_fin_journee'] = $form->getElement('FinMidi')->getValue();
	     
	     
	    
		 
	            
	     // remplie le select avec le  nom et prenom de la personne ayant id personne  
	     $where = array('id = ?' => $id_personne);
		 $form->setDbOptions('NomPrenom',new Default_Model_Personne(),'getId','getNomPrenom',$where);


		 
		// remplir le formulaire par les données recupérer 
		$form->getElement('NomPrenom')->setValue($id_personne);
		$form->getElement('date_debut')->setValue($PreData['date_debut']);
		$form->getElement('date_fin')->setValue($PreData['date_fin']);
		$form->getElement('DebutMidi')->setValue($PreData['DebutMidi']);
		$form->getElement('FinMidi')->setValue($PreData['FinMidi']);
		

		//si la page est POSTée = formulaire envoyé
		if($this->getRequest()->isPost())
		{ 
			//récupération des données envoyées par le formulaire
			$data =  $this->getRequest()->getParams();
            
			//vérifie que les données répondent aux conditions des validateurs
			if($form->isValid($data))
			{
                
				           $i = 1;
					       // vérifie si les données ont subit une modification
					        foreach($PreData as $k=>$v)
					        {
					        	if((string)$PreData[$k] != (string)$data[$k])
					        	{
					        		$i*=0;
					        	}
					        	
					        }
					        
							if($i == 1)
				        	{
				        	 	 $this->view->error = "Vous n'avez modifié aucun champ !";
				         	}
				         	else 
			                {       
			                	 
			     
				        	     // remplir l'objet proposition par les valeurs modifiées     
			                	 $proposition->setId($data_id['id']);
			                     $proposition->setId_personne($id_personne);
			                	 $proposition->setDate_debut($data['date_debut']);
					             $proposition->setDate_fin($data['date_fin']);
					             $proposition->setMi_debut_journee($data['DebutMidi']);
					             $proposition->setMi_fin_journee($data['FinMidi']);
					             $proposition->setNombre_jours();
					             $proposition->setEtat('NC');
					             
					             $this->view->title = "Modification de la proposition de Mr/Mme : ".$pers->getNomPrenom();	

								 $proposition->save();  // insérer dans la base 
									
								 //redirection vers afficher csm 	
								 $this->_helper->redirector('affichercsm');

			                }

			}
		}
	}
	public function deleteAction()
	{
		//récupére les paramétres de la requéte
		
		$params = $this->getRequest()->getParams();

	

		//vérifie que le paramétre id existe
		if(isset($params['id']))
		{
			
			$id = $params['id'];
			//création du modéle pour la suppression
			$proposition = new Default_Model_Proposition();
			//appel de la fcontion de suppression avec en argument,
			//la clause where qui sera appliquée
			$result = $proposition->delete("id=$id");

			//redirection
			$this->_helper->redirector('affichercsm');
		}
		else
		{
			$this->view->form = 'Impossible delete: id missing !';
		}
	}
		/*
		 * cette fonction permet é l'admin de valiser les propositions et les enregistré dans 
		 * la table :conge
		 */
	
	public function accepterAction()
	{
		//récupére les paramétres de la requéte
		
		$params = $this->getRequest()->getParams();
		//vérifie que le paramétre id existe
		
		
	if(isset($params['id']))
	{
			$id = $params['id'];
		
		if ($params['solde']== 2)
		{
			
			
			$proposition = new Default_Model_Proposition();
			$result = $proposition->find($id);
			$nombre_jours = $result->getNombre_jours();
			$id_personne = $result->getId_personne();
			$date=date('Y-m-d');
			list($annee_debut, $mois_debut,$jour_debut ) = explode("-", $date);
			list($annee_fin, $mois_fin,$jour_fin ) = explode("-", $date);
			$debut_annee_reference = $annee_debut.'-01-01';
			$fin_annee_reference = $annee_debut.'-12-31';
			if ($this->_helper->validation->verifierSolde ($id_personne,$debut_annee_reference, $fin_annee_reference,$annee_debut,$nombre_jours))
			{
				$parametres = array('id'=>3);
				//$this->_helper->redirector('message','proposition',$parametres=array('id'=>3));
				
				$url = '/proposition/message/id/'.$id;
        		$this->_helper->redirector->gotoUrl($url);
			}
		
		
		}
			
	if($params['solde']==2  || $params['solde']==1 )
			{
				
				$id = $params['id'];
				$proposition = new Default_Model_Proposition();
				$result = $proposition->find($id);
				
				$result->setEtat("OK")->save();
				
				// apres la validation de la proposition sera enregister dans la table conge
				$str=NULL;
				$conge = new Default_Model_Conge();
				$resultat_id_conge = $conge->fetchAll($str);
				$tableau_id_conge = array();
				$index =0;
				foreach($resultat_id_conge as $c)
				{
					$tableau_id_conge[$index] = $c->getId_proposition();
					$index++;
				}
				$conge->setId_proposition($result->getId('id'));
				$conge->setId_personne($result->getId_personne('id_personne'));
				$conge->setDate_debut($result->getDate_debut());
				$conge->setDate_fin($result->getDate_fin());
				$conge->setMi_debut_journee($result->getMi_debut_journee());
				$conge->setMi_fin_journee($result->getMi_fin_journee());
				$conge->setNombre_jours($result->getNombre_jours());
				$conge->setAnnee_reference($annee_debut);
				$conge->setId_type_conge('1');
				$conge->setFerme(1);
				 
				if (!in_array($result->getId('id'), $tableau_id_conge))
				{
					$conge->save();
				}
				
				
				//redirection
				$this->_helper->redirector('afficheradmin');
		}
		if($params['solde']==0 )
		{
			
			$url = '/proposition/edit/id/'.$id;
        	$this->_helper->redirector->gotoUrl($url);
			
		}
		
	}
	
	
		
	
	

}
	
	public function refuserAction()
	{
		//récupére les paramétres de la requéte
		$params = $this->getRequest()->getParams();
		//vérifie que le paramétre id existe
		if(isset($params['id']))
		{
			$id = $params['id'];

			//création du modéle pour le refus
			$proposition = new Default_Model_Proposition();
						
			$result = $proposition->find($id);
			$result->setEtat("KO")->save();
			//redirection
			$this->_helper->redirector('affichercsm');
		}
		
		else
		{
			$this->view->form = $params;
		}
	}
	
	public function afficheradminAction ()
	{
		$proposition = new Default_Model_Proposition;
		$paginator = Zend_Paginator::factory($proposition->fetchAll('Etat = "NC"'));
		$paginator->setItemCountPerPage(10);
		//récupére le numéro de la page à afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));
		//on initialise la valeur PropositionArray de la vue
		$this->view->propositionArray = $paginator;
	}
	
	public function affichercsmAction ()
	{
		$proposition = new Default_Model_Proposition;
		$paginator = Zend_Paginator::factory($proposition->fetchAll($str = array()));
		$paginator->setItemCountPerPage(10);
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));
		$this->view->propositionArray = $paginator;
     		
	}
	public function rederigerversindexAction ()
	{
	   $this->_helper->redirector('index');
		
	}
	public function messageAction()
	{
		$parame = $this->getRequest()->getParams();
		$this->view->id = $parame['id'];
	}

}