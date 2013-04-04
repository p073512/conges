<?php
class FonctionController extends Zend_Controller_Action
{
	//action par défaut
	public function indexAction()
	{
		//création d'un d'une instance Default_Model_Users
		$fonction = new Default_Model_Fonction();

		//$this->view permet d'accéder à la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		//la valeur correspond à un tableau d'objets de type Default_Model_Users récupérés par la méthode fetchAll()
		//$this->view->usersArray = $users->fetchAll();

		//création de notre objet Paginator avec comme paramètre la méthode
		//récupérant toutes les entrées dans notre base de données
		$paginator = Zend_Paginator::factory($fonction->fetchAll($str =array()));
		//indique le nombre déléments à afficher par page
		$paginator->setItemCountPerPage(10);
		//récupère le numéro de la page à afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'accéder à la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		$this->view->congeArray = $paginator;
	}

	public function createAction()
	{
		//création du fomulaire
		$form = new Default_Form_Fonction();
		//indique l'action qui va traiter le formulaire
		$form->setAction($this->view->url(array('controller' => 'conge', 'action' => 'create'), 'default', true));
		$form->submit->setLabel('Creer');

		//assigne le formulaire à la vue
		$this->view->form = $form;

		//si la page est POSTée = formulaire envoyé
		if($this->_request->isPost())
		{
			//récupération des données envoyées par le formulaire
			$data = $this->_request->getPost();

			//vérifie que les données répondent aux conditions des validateurs
			if($form->isValid($data))
			{
				//création et initialisation d'un objet Default_Model_Users
				//qui sera enregistré dans la base de données
				$fonction = new Default_Model_Fonction();
				$fonction->setLibelle($form->getValue('libelle'));

				$fonction->save();

				//redirection
				$this->_helper->redirector('index');
			}
			else
			{
				//si erreur rencontrée, le formulaire est rempli avec les données
				//envoyées précédemment
				$form->populate($data);
			}
		}
	}

	public function editAction()
	{
		//création du fomulaire
		$form = new Default_Form_Fonction();
		//indique l'action qui va traiter le formulaire
		$form->setAction($this->view->url(array('controller' => 'conge', 'action' => 'edit'), 'default', true));
		$form->submit->setLabel('Modifier');

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
				
				//création et initialisation d'un objet Default_Model_Fonction
				//qui sera enregistré dans la base de données
				$fonction = new Default_Model_Fonction();
				$fonction->setId($form->getValue('id'));
				$fonction->setLibelle($form->getValue('libelle'));
				$fonction->save();

				
				//redirection
				$this->_helper->redirector('index');
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
				$fonction = new Default_Model_Fonction();
				$fonction = $fonction->find($id);

				//assignation des valeurs de l'entrée dans un tableau
				//tableau utilisé pour la méthode populate() qui va remplir le champs du formulaire
				//avec les valeurs du tableau
				$data[] = array();
				$data['id'] = $fonction->getId();
				$data['libelle'] = $fonction->getLibelle();
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
			$fonction = new Default_Model_Fonction();
			//appel de la fcontion de suppression avec en argument,
			//la clause where qui sera appliquée
			$result = $fonction->delete("id=$id");

			//redirection
			$this->_helper->redirector('index');
		}
		else
		{
			$this->view->form = 'Impossible delete: id missing !';
		}
	}
	/*public function rederigerversindexAction ()
	{
	   $this->_helper->redirector('index');
		
	}*/
	
	
}