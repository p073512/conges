<?php
class PropositionController extends Zend_Controller_Action
{
	
     public function preDispatch() /* MTA : Mohamed khalil Takafi */
    {
		
    	    $doctypeHelper = new Zend_View_Helper_Doctype();
            $doctypeHelper->doctype('HTML5');
    		$this->_helper->layout->setLayout('mylayout');
	}
 
	public function indexAction()
	{
		// on ajoute le filtre sur la vue des propositions
		
		$proposition = new Default_Model_Proposition;
		//$this->view->propositionArray =$proposition->fetchAll('Etat = "NV"');

		//création de notre objet Paginator avec comme paramétre la méthode
		//récupérant toutes les entrées dans notre base de donn�es
		$paginator = Zend_Paginator::factory($proposition->fetchAll($str=array()));
		//indique le nombre d�l�ments � afficher par page
		$paginator->setItemCountPerPage(20);
		//r�cup�re le num�ro de la page � afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'acc�der � la vue qui sera utilis�e par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		$this->view->propositionArray = $paginator;
		
		/*//cr�ation d'un d'une instance Default_Model_Users
		$proposition = new Default_Model_Proposition();

		//$this->view permet d'acc�der � la vue qui sera utilis�e par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		//la valeur correspond � un tableau d'objets de type Default_Model_Users r�cup�r�s par la m�thode fetchAll($str)
		//$this->view->PropositionArray = $propositon->fetchAll($str);

		//cr�ation de notre objet Paginator avec comme param�tre la m�thode
		//r�cup�rant toutes les entr�es dans notre base de donn�es
		$paginator = Zend_Paginator::factory($proposition->fetchAll($str=array()));
		//indique le nombre d�l�ments � afficher par page
		$paginator->setItemCountPerPage(10);
		//r�cup�re le num�ro de la page � afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'acc�der � la vue qui sera utilis�e par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		$this->view->propositionArray = $paginator;*/
	}

	public function createpropositionAction()   /* MTA : Mohamed khalil Takafi */
	{
		//cr�ation du fomulaire
		$form = new Default_Form_Proposition();
		//indique l'action qui va traiter le formulaire
		$form->setAction($this->view->url(array('controller' => 'proposition', 'action' => 'create'), 'default', true));
		
		$data = array();
		//assigne le formulaire � la vue
		$this->view->form = $form;
		$this->view->title = "Creer Proposition";
		
		$where = array('centre_service = ?' => '1');
		$form->setDbOptions('NomPrenom',new Default_Model_Personne(),'getId','getNomPrenom',$where);
		
		 $this->_helper->viewRenderer('createproposition');
	     $this->view->form = $form;
		//si la page est POST�e = formulaire envoy�
		if($this->_request->isPost())   
		{
			//r�cup�ration des donn�es envoy�es par le formulaire
			
			$data = $this->_request->getPost();

			//v�rifie que les donn�es r�pondent aux conditions des validateurs
			if($form->isValid($data))  
			{
				//cr�ation et initialisation d'un objet Default_Model_Proposition
				//qui sera enregistr� dans la base de donn�es
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
			//si erreur rencontr�e, le formulaire est rempli avec les donn�es
			//envoy�es pr�c�demment
			$form->populate($data);
		}
	
	}

	public function editAction()
	{

		//cr�ation du fomulaire
		$form = new Default_Form_Proposition();
		//indique l'action qui va traiter le formulaire
		$form->setAction($this->view->url(array('controller' => 'proposition', 'action' => 'edit'), 'default', true));
		$form->submit_pro->setLabel('Modifier');

		//assigne le formulaire � la vue
		$this->view->form = $form;

		//si la page est POST�e = formulaire envoy�
		if($this->getRequest()->isPost())
		{
			//r�cup�ration des donn�es envoy�es par le formulaire
			$data = $this->getRequest()->getPost();

			//v�rifie que les donn�es r�pondent aux conditions des validateurs
			if($form->isValid($data))
			{
				
				//cr�ation et initialisation d'un objet Default_Model_Proposition
				//qui sera enregistr� dans la base de donn�es
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
				//si erreur rencontr�e, le formulaire est rempli avec les donn�es
				//envoy�es pr�c�demment
				$form->populate($data);
			}
		}
		else
		{
			//r�cup�ration de l'id pass� en param�tre
			$id = $this->_getParam('id', 0);

			if($id > 0)
			{
				//r�cup�ration de l'entr�e
				$proposition = new Default_Model_Proposition();
				$proposition = $proposition->find($id);

				//assignation des valeurs de l'entr�e dans un tableau
				//tableau utilis� pour la m�thode populate() qui va remplir le champs du formulaire
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
		//r�cup�re les param�tres de la requ�te
		
		$params = $this->getRequest()->getParams();

	

		//v�rifie que le param�tre id existe
		if(isset($params['id']))
		{
			
			$id = $params['id'];
			//cr�ation du mod�le pour la suppression
			$proposition = new Default_Model_Proposition();
			//appel de la fcontion de suppression avec en argument,
			//la clause where qui sera appliqu�e
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
		 * cette fonction permet � l'admin de valiser les propositions et les enregistr� dans 
		 * la table :conge
		 */
	
	public function accepterAction()
	{
		//r�cup�re les param�tres de la requ�te
		
		$params = $this->getRequest()->getParams();
		//v�rifie que le param�tre id existe
		
		
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
		//r�cup�re les param�tres de la requ�te
		$params = $this->getRequest()->getParams();
		//v�rifie que le param�tre id existe
		if(isset($params['id']))
		{
			$id = $params['id'];

			//cr�ation du mod�le pour le refus
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
		//r�cup�re le num�ro de la page � afficher
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