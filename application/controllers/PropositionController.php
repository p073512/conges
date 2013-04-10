<?php
class PropositionController extends Zend_Controller_Action
{
 
	public function indexAction()
	{
		// on ajoute le filtre sur la vue des propositions
		
		$proposition = new Default_Model_Proposition;
		//$this->view->propositionArray =$proposition->fetchAll('Etat = "NV"');

		//création de notre objet Paginator avec comme paramètre la méthode
		//récupérant toutes les entrées dans notre base de données
		$paginator = Zend_Paginator::factory($proposition->fetchAll($str=array()));
		//indique le nombre déléments à afficher par page
		$paginator->setItemCountPerPage(20);
		//récupère le numéro de la page à afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'accéder à la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		$this->view->propositionArray = $paginator;
		
		/*//création d'un d'une instance Default_Model_Users
		$proposition = new Default_Model_Proposition();

		//$this->view permet d'accéder à la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		//la valeur correspond à un tableau d'objets de type Default_Model_Users récupérés par la méthode fetchAll($str)
		//$this->view->PropositionArray = $propositon->fetchAll($str);

		//création de notre objet Paginator avec comme paramètre la méthode
		//récupérant toutes les entrées dans notre base de données
		$paginator = Zend_Paginator::factory($proposition->fetchAll($str=array()));
		//indique le nombre déléments à afficher par page
		$paginator->setItemCountPerPage(10);
		//récupère le numéro de la page à afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'accéder à la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		$this->view->propositionArray = $paginator;*/
	}

	public function createAction()   /* MTA : Mohamed khalil Takafi */
	{
		//création du fomulaire
		$form = new Default_Form_Proposition();
		//indique l'action qui va traiter le formulaire
		$form->setAction($this->view->url(array('controller' => 'proposition', 'action' => 'create'), 'default', true));
		$form->submit_pro->setLabel('Valider');
		$data = array();
		//assigne le formulaire à la vue
		$this->view->form = $form;
		$this->view->title = "Creer Proposition";
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
				$proposition->setId_personne($form->getValue('id_personne_pro'));
				$proposition->setDate_debut($form->getValue('date_debut_pro'),'yy-mm-dd');
				$proposition->setDate_fin($form->getValue('date_fin_pro'),'yy-mm-dd');
				$proposition->setMi_debut_journee($form->getValue('mi_debut_journee_pro'));
				$proposition->setMi_fin_journee($form->getValue('mi_fin_journee_pro'));
				$proposition->setNombre_jours();
				$proposition->setEtat('NC');
				
				/*
				 * Gestion du chevauchement
				 * on appelle le helper pour verifierl'existance des proposition avant 
				 * l'enregistrement dans la base
				 */
				if($this->_helper->validation->verifierConges($form->getValue('id_personne_pro'),$form->getValue('date_debut_pro'),$form->getValue('date_fin_pro'),$form->getValue('mi_debut_journee_pro'),$form->getValue('mi_fin_journee_pro'),1,1)&& $this->_helper->validation->verifierPropositions($form->getValue('id_personne_pro'),$form->getValue('date_debut_pro'),$form->getValue('date_fin_pro'),$form->getValue('mi_debut_journee_pro'),$form->getValue('mi_fin_journee_pro')))
				{
					$proposition->save();
					//redirection
					$this->_helper->redirector('index');
					
				}
				else   /* MTA : Mohamed khalil Takafi */ 
				{
					$form->populate($data);
					if ($form->getValue('date_debut_pro') > $form->getValue('date_fin_pro'))
					// MTA : modification du message echo "......."
					echo "<strong><em><span style='background-color:rgb(255,0,0)'> La date de début doit être inférieure ou égale à la date de fin</span></em></strong>";
				}
			}
			else 
			{
				$form->populate($data);
				echo "<strong><em><span style='background-color:rgb(255,0,0)'> proposition ou conge deja demande </span></em></strong>";
			}
		}
		else 
		{
			//si erreur rencontrée, le formulaire est rempli avec les données
			//envoyées précédemment
			$form->populate($data);
		}
	}

	public function editAction()
	{
		//création du fomulaire
		$form = new Default_Form_Proposition();
		//indique l'action qui va traiter le formulaire
		$form->setAction($this->view->url(array('controller' => 'proposition', 'action' => 'edit'), 'default', true));
		$form->submit_pro->setLabel('Modifier');

		//assigne le formulaire à la vue
		$this->view->form = $form;

		//si la page est POSTée = formulaire envoyé
		if($this->getRequest()->isPost())
		{
			//récupération des données envoyées par le formulaire
			$data = $this->getRequest()->getPost();

			//vérifie que les données répondent aux conditions des validateurs
			if($form->isValid($data))
			{
				
				//création et initialisation d'un objet Default_Model_Proposition
				//qui sera enregistré dans la base de données
				$proposition = new Default_Model_Proposition();
				$proposition->setId($form->getValue('id'));
				$proposition->setId_personne($form->getValue('id_personne_pro'));
				
				//$date_debut = new Zend_Date;
				//$date_debut->set($form->getValue('date_debut_pro'),'yy-mm-dd');
				$proposition->setDate_debut($form->getValue('date_debut_pro'),'yy-mm-dd');
				$proposition->setDate_fin($form->getValue('date_fin_pro'),'yy-mm-dd');
				$proposition->setMi_debut_journee($form->getValue('mi_debut_journee_pro'));
				$proposition->setMi_fin_journee($form->getValue('mi_fin_journee_pro'));
				$proposition->setNombre_jours();
				//$proposition->setAnnee_reference($form->getValue('annee_reference'));
				$proposition->setEtat('NC');
					// regarde le probleme de l'anne de reference
						$personne = new Default_Model_Personne();
						$result_set_personnes = $personne->find($form->getValue('id_personne_pro'));
				$this->view->title = "Modification de la proposition de Mr/Mme : ".$result_set_personnes->getNom()." ".$result_set_personnes->getPrenom();	
				
			
				if($this->_helper->validation->verifierConges($form->getValue('id_personne_pro'),$form->getValue('date_debut_pro'),$form->getValue('date_fin_pro'),$form->getValue('mi_debut_journee_pro'),$form->getValue('mi_fin_journee_pro')))
				{
					$proposition->save();
					//redirection
					$this->_helper->redirector('index');
				}
				else 
				{
					$form->populate($data);
					echo "<strong><em><span style='background-color:rgb(255,0,0)'> conge deja demande</span></em></strong>";
				}
			}
			else
			{
				//si erreur rencontrée, le formulaire est rempli avec les données
				//envoyées précédemment
				$form->populate($data);
			}
		}
		else
		{
			//récupération de l'id passé en paramètre
			$id = $this->_getParam('id', 0);

			if($id > 0)
			{
				//récupération de l'entrée
				$proposition = new Default_Model_Proposition();
				$proposition = $proposition->find($id);

				//assignation des valeurs de l'entrée dans un tableau
				//tableau utilisé pour la méthode populate() qui va remplir le champs du formulaire
				//avec les valeurs du tableau
				$data[] = array();
				$data['id'] = $proposition->getId();
				$data['id_personne'] = $proposition->getId_personne();
				$data['date_debut'] = $proposition->getDate_debut();
				$data['mi_debut_journee'] = $proposition->getMi_debut_journee();
				$data['date_fin'] = $proposition->getDate_fin();
				$data['mi_fin_journee'] = $proposition->getMi_fin_journee();
				$data['nombre_jours'] = $proposition->getNombre_jours();
			//	$data['nombre_jours'] = $proposition->getAnnee_reference();
				$data['etat'] = $proposition->getEtat();
				$personne = new Default_Model_Personne();
				$result_set_personnes = $personne->find($id);
				$this->view->title = "Modification de la proposition de Mr/Mme : ".$result_set_personnes->getNom()." ".$result_set_personnes->getPrenom();
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
			$proposition = new Default_Model_Proposition();
			//appel de la fcontion de suppression avec en argument,
			//la clause where qui sera appliquée
			$result = $proposition->delete("id=$id");

			//redirection
			$this->_helper->redirector('index');
		}
		else
		{
			$this->view->form = 'Impossible delete: id missing !';
		}
	}
		/*
		 * cette fonction permet à l'admin de valiser les propositions et les enregistré dans 
		 * la table :conge
		 */
	
	public function accepterAction()
	{
		//récupére les paramètres de la requête
		
		$params = $this->getRequest()->getParams();
		//vérifie que le paramètre id existe
		
		
	if(isset($params['id']))
	{
			$id = $params['id'];
		
		if ($params['solde']==2)
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
		//récupére les paramètres de la requête
		$params = $this->getRequest()->getParams();
		//vérifie que le paramètre id existe
		if(isset($params['id']))
		{
			$id = $params['id'];

			//création du modèle pour le refus
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
		//récupère le numéro de la page à afficher
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