<?php
class SoldeController extends Zend_Controller_Action
{
	//action par défaut
	protected $_annee;
	public function indexAction()
	{
		//création d'une instance Default_Model_Personne
		$personne = new Default_Model_Personne();
		
		$personnes = $personne->fetchAll($str=array(),array('centre_service=?' => 0));
		$t_personnes = array();
		foreach ($personnes as $p) {
			$t_personnes[$p->getId()] = $p->getPrenom().' '.$p->getNom();
		}
		
		$solde = new Default_Model_Solde();
		$soldes = $solde->fetchAll($str =array());
		$t_soldes = array();
		foreach ($soldes as $solde) {
			$t_soldes[] = array('id_ressource' => $solde->getPersonne()->getId(),
								'ressource' => $t_personnes[$solde->getPersonne()->getId()],
								'annee' => $solde->getAnnee_reference(),
								'cp' => $solde->getTotal_cp(),
								'q1' => $solde->getTotal_q1(),
								'q2' => $solde->getTotal_q2());
		}

		//création de notre objet Paginator avec comme paramètre la méthode
		//récupérant toutes les entrées dans notre base de données
		$paginator = Zend_Paginator::factory($t_soldes);
		//indique le nombre déléments à afficher par page
		$paginator->setItemCountPerPage(20);
		//récupère le numéro de la page à afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'accéder à la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		$this->view->soldeArray = $paginator;
	}

public function initSoldePersonneAnneeAction()
    {
    	$this->view->title = "Initialiser le solde d'une ressource sur une année";
        $request = $this->getRequest();
        $form = new Default_Form_SoldePersonneAnnee();
        $personne = new Default_Model_Personne();
             
        //Peuplé les listes déroulantes à partir de la Base de donnée
		$where = array('id_entite <> ?' => '2');
		$form->setDbOptions('personne',new Default_Model_Personne(),'getId','getNomPrenom',$where);
		
        if ($this->getRequest()->isPost()) {
            $data = $request->getPost();
        	if ($form->isValid($request->getPost())) {
            	$solde = new Default_Model_Solde();
            	
            	$personne = new Default_Model_Personne();
				
				$droits_conges = array("CP" => 1,"CPA" => 2,"Q1" => 3);
				$_solde = new Default_Controller_Helpers_Solde();
				$droits_conges = $_solde->calculer_droits_a_conges($personne->find($data['personne']),$data['annee_reference']);
				$cp = $droits_conges['CP']+$droits_conges['CPA'];
				$q1 = $droits_conges['Q1'];	

				$solde->setAnnee_reference($data['annee_reference']);
            	$solde->setPersonne($data['personne']);
            	
            	$solde->setTotal_cp();
            	$solde->setTotal_q1($q1);
            	$solde->setTotal_q2($data['q2']);
            	
            	try {
            		$solde->save();
            		 
            	}
            	catch (Zend_Db_Exception $e){
            		if($form->getElement('personne')->getValue() == 'x')
            		$form->getElement('personne')->addError("erreur");
            		
            		$this->view->error = "Erreur d'insertion : ".$e->getMessage();
            		$form->populate($data);
            	}

            	$this->view->success = $data['personne'];
            }
        }
        else {
        	$data = array();
        	$form->populate($data);
        }
        $this->view->form = $form;
    }
	
/*
	 * @author PTRI
	 * Typiquement le genre de service qui devrait se trouver dans une couche logique m�tier
	 * on ne devrait trouver qu'une action pour appeler ce service
	 */
	public function ficheRhAction()
	{
		
			$form = new Default_Form_FicheRhForm();
			$request = $this->getRequest();
			$form->setDbOptions('personne', new Default_Model_Personne(), 'getId', 'getNomPrenom');
			$this->view->form = $form;
			
		 if ($request->isPost())
		 {
	     $ressource = new Default_Model_Personne();
		 $data = $request->getPost();
		 if($data['personne'] == 'x')
		 {
		 	$this->view->error = 'Veuillez saisir une ressource !';
		
		 return;
		 }
		else
		{
			
		
	     $ressource = $ressource->fetchAll("id =".$data['personne']."");
		 $ressource = $ressource['0'];
			
	
		
		$annee_reference = $data['annee'];
		
		$resultat = array();
		$droits = array();
		$reliquat = array();
		$consomme = array();
		$solde_courant = array();
		$solde_prev = array();
	   
		// R�cup�rer les droits de la ressource
		$solde = new Default_Model_Solde();
		
		$solde = $solde->find($annee_reference,$ressource->getId());
	
		if ($solde->getPersonne()->id != null) {
			$droits['CP'] = $solde->getTotal_cp();
			$droits['Q1'] = $solde->getTotal_q1();
			$droits['Q2'] = $solde->getTotal_q2();
		}
		else {
			//throw new Exception("Initialiser les soldes de ".$ressource->getPrenom().' '.$ressource->getNom());
		    $this->view->error = 'Pas de données solde : Initialiser les soldes de :'.$ressource->getPrenom().' '.$ressource->getNom();
		}
		
		$_solde = new Default_Controller_Helpers_Solde();
		// Reliquats
		$reliquat = $_solde->calculer_reliquat($ressource,$annee_reference);
		
		// Conso
		$consomme = $_solde->calculer_consomme($ressource,$annee_reference);
		
		// Solde courant
		$solde_courant = $_solde->calculer_solde($ressource,$annee_reference,$droits,$consomme);
		
		// Solde pr�visionnel
		$solde_prev = $_solde->calculer_solde($ressource,$annee_reference,$droits,false,false);
		$resultat['ressource'] = $ressource->getNom().' '.$ressource->getPrenom();
		$resultat['droits'] = $droits;
		$resultat['reliquat'] = $reliquat;
		$resultat['consomme'] = $consomme;
		$resultat['solde_courant'] = $solde_courant;
		$resultat['solde_prev'] = $solde_prev;
		

        $form->populate($data);
		$this->view->soldeArray = $resultat;
		
			
        return;
		}
		}
		else 
		{  
			$this->view->soldeArray = null;
			
		}

		
	}
	
	public function sommeCongesAction()
		{
			
			$form = new Default_Form_SommesCongeForm();
			$request = $this->getRequest();
			$form->setDbOptions('personne', new Default_Model_Personne(), 'getId', 'getNomPrenom');
			$this->view->form = $form;
			
			if ($request->isPost())
		{
			$data = $request->getPost();
			
			// D�finition de mes param�tres d'entr�e
			$ressource = new Default_Model_Personne();
			if($data['personne'] == 'x')
		 {
		 	$this->view->error = 'Veuillez saisir une ressource !';
		
		 return;
		 }
		else
		{
			
			$ressource = $ressource->fetchAll("id =".$data['personne']."");
			$ressource= $ressource['0'];
			
			$anne_reference = $data['annee'];
			$_solde = new Default_Controller_Helpers_Solde();
	
			var_dump($_solde->calculer_droits_a_conges($ressource,$anne_reference));
			var_dump($_solde->calculer_reliquat($ressource,$anne_reference));
	
			var_dump($_solde->calculer_consomme($ressource,$anne_reference));
			
			
			$form->populate($data);
		}
		}
			
		}
    
    
	/**
	public function createAction()
	{

		
		//création du fomulaire
		$form = new Default_Form_Solde();
		//indique l'action qui va traiter le formulaire
		$form->setAction($this->view->url(array('controller' => 'solde', 'action' => 'create'), 'default', true));
	//	$form->submit_sl->setLabel('Initialiser');
		$this->view->form = $form;
		$this->view->title = "Initialiser Les Soldes";
		$data =array();
		//si la page est POSTée = formulaire envoyé
		if($this->_request->isPost())
		{
			//récupération des données envoyées par le formulaire
			$data = $this->_request->getPost();

			//vérifie que les données répondent aux conditions des validateurs
			if($form->isValid($data))
			{
				
				$solde = new Default_Model_Solde();
				$var = (int)$form->getValue('Annee');
				$solde1 = $solde->fetchall2('annee_reference ='.$var);
				
				if (!count($solde1))
				{
			
					$personne = new Default_Model_Personne();
					$personne = $personne->fetchall($str =array());
					foreach ($personne as $p)
					{
						
						$date_entree = $p->getDate_entree();
						$modalite = $p->getModalite()->getId();
						
						$solde->setPersonne($p);
						$solde->setTotal_cp($date_entree);
						$solde->setTotal_q1($modalite);
						$solde->setTotal_q2(0);
						$solde->setAnnee_reference($form->getValue('Annee'));
						$solde->save();
					}
					
					$this->_annee =$form->getValue('Annee');
					$this->_helper->redirector('index');
				}
				
				else
				{
					echo "solde est deja declarer pour cet annee";
					$form->populate($data);
				}
			}
			else
				{
					
					$form->populate($data);
				}
		}
	}

	
	


	public function deleteAction()
	{
		//récupére les paramètres de la requête
		$params = $this->getRequest()->getParams();

		//vérifie que le paramètre id existe
		if(isset($params['id']))
		{
			$id = $params['id'];

			//création du modèle pour la suppression
			$personne = new Default_Model_Solde();
			//appel de la fcontion de suppression avec en argument,
			//la clause where qui sera appliquée
			$result = $personne->delete("id=$id");

			//redirection
			$this->_helper->redirector('index');
		}
		else
		{
			$this->view->form = 'Impossible delete: id missing !';
		}
	}
**/
}