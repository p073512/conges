<?php
class SoldeController extends Zend_Controller_Action
{
	//action par défaut
	protected $_annee;
	public function indexAction()
	{
		//création d'un d'une instance Default_Model_Solde
		$solde= new Default_Model_Solde();

		//$this->view permet d'accéder à la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		//la valeur correspond à un tableau d'objets de type Default_Model_Solde récupérés par la méthode fetchAll()
		//$this->view->usersArray = $personne->fetchAll();
		$var = (int)$this->_annee;
		//création de notre objet Paginator avec comme paramètre la méthode
		//récupérant toutes les entrées dans notre base de données
		
		//récupérant toutes les entrées dans notre base de données
		$paginator = Zend_Paginator::factory($solde->fetchAll('annee_reference='.$var));
		//indique le nombre déléments à afficher par page
		$paginator->setItemCountPerPage(20);
		//récupère le numéro de la page à afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'accéder à la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		$this->view->soldeArray = $paginator;
	}

	public function createAction()
	{

		
		//création du fomulaire
		$form = new Default_Form_Solde();
		//indique l'action qui va traiter le formulaire
		$form->setAction($this->view->url(array('controller' => 'solde', 'action' => 'create'), 'default', true));
		$form->submit_sl->setLabel('Initiliser');
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
				$var = (int)$form->getValue('annee_reference_sl');
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
						$solde->setAnnee_reference($form->getValue('annee_reference_sl'));
						$solde->save();
					}
					
					$this->_annee =$form->getValue('annee_reference_sl');
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

}