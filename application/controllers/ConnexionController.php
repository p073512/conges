<?php
class ConnexionController extends Zend_Controller_Action
{
	private $_auth;

	public function init()
	{
		//récupération de l'instance d'authentification
		$this->_auth = Zend_Auth::getInstance();
		// Appel de l'aide comme méthode sur le gestionnaire d'aides:
		$layout = $this->_helper->layout();
		$layout->disableLayout();
		
   
	}

	public function indexAction()
	{
		//création et affichage dans la vue du formulaire
		$connexionForm = new Default_Form_Connexion();
		$connexionForm->setAction($this->view->url(array('controller' => 'connexion', 'action' => 'connexion'), 'default', true));
		$this->view->form = $connexionForm;
	
		
	}

	public function connexionAction()
	{
		//création du fomulaire
		$form = new Default_Form_Connexion();
		//indique l'action qui va traiter le formulaire
		$form->setAction($this->view->url(array('controller' => 'connexion', 'action' => 'connexion'), 'default', true));
		

		//assigne le formulaire à la vue
		$this->view->form = $form;

		//vérification si la page a bien été appelée à partir d'un formulaire
		if($this->_request->isPost())
		{
			//enregistrement des données envoyées à partir du formulaire dans un tableau
			$data = $this->_request->getPost();

			//validation du formulaire
			if($form->isValid($data))
			{
				//Zend_Debug::dump($data, $label = "Formulaire de connexion valide", $echo = true);
				$profil = $data['profil'];
				$mot_passe =  $data['mot_passe'];

				//création d'un adpatateur d'authentification utilisant une base de données
				//le premier argument correspond à l'adptateur par défaut
				//le second correspond à la table qui est utilisée pour l'authentification
				//le troisième indique la colonne utilisée pour représenter l'identité (le profil)
				//le quatrième argument indique la colonne utilisée pour représenter le crédit (le password)
				$authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table_Abstract::getDefaultAdapter(),
															'profil',		//table 'profil' 
															'login',		//champ identité 
															'mot_passe');	//champ crédit

				//préparation de la requête d'authentification en indiquant l'identité et le crédit
				$authAdapter->setIdentity($profil);
				$authAdapter->setCredential($mot_passe);

				//exécution de la requête d'authentification et enregistrement du résultat
				$result = $this->_auth->authenticate($authAdapter);

				//si l'authentification a réussi
				if($result->isValid())
				{
					Zend_Debug::dump($data, $label = "Résultat de la connexion valide", $echo = true);
					//stockage de l'identité sous forme d'objet
					//le permier argument permet d'indiquer les valeurs que l'on veut enregistrer (null indique que l'on enreegistre l'entièreté de l'objet)
					//le second argument permet d'indiquer les valeurs que l'on ne souhaite pas enregistrer
					$this->_auth->getStorage()->write($res = $authAdapter->getResultRowObject(null, 'mot_passe'));

					//permet de regénérer l'identifiant de session
					Zend_Session::regenerateId();

					//redirection
					$this->_helper->_redirector('calendriermensuel', 'calendrier');
				}
				else
				{
					//si erreur rencontrée, le formulaire est rechargé
					
					echo "mot de passe incorrecte";
					///$this->_helper->_redirector('index', 'connexion');
				
				}
			}
			else
			{
				//si erreur rencontrée, le formulaire est rechargé
				
				echo "mot de passe incorrecte";
				//$this->_helper->_redirector('index', 'connexion');
				
			}
		}
		else
		{
			//redirection si la page n'a pas été appelée à partir d'un formulaire
			//$this->_redirect($this->view->url(array('controller' => 'profil'), 'default', true));
			
			echo "mot de passe incorrecte";	
			//$this->_helper->redirector('index', 'connexion');
			
		}
	}

	public function deconnexionAction()
	{
		//réinitialisation de l'instance d'authentification et destruction de la session
		Zend_Auth::getInstance()->clearIdentity();
		Zend_Session::destroy();
		$this->_helper->redirector('index', 'connexion');
	}
}