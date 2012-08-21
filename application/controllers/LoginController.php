<?php
class LoginController extends Zend_Controller_Action
{
	private $_auth;

	public function init()
	{
		//récupération de l'instance d'authentification
		$this->_auth = Zend_Auth::getInstance();
	}

	public function indexAction()
	{
		//création et affichage dans la vue du formulaire
		$loginForm = new Default_Form_Login();
		$loginForm->setAction($this->view->url(array('controller' => 'login', 'action' => 'login'), 'default', true));
		$this->view->loginForm = $loginForm;
	}

	public function loginAction()
	{
		//récupération de la requête
		$request = $this->_request;

		//vérification si la page a bien été appelée à partir d'un formulaire
		if($request->isPost())
		{
			$loginForm = new Default_Form_Login();
			//enregistrement des données envoyées à partir du formulaire dans un tableau
			$data = $request->getPost();

			//validation du formulaire
			if($loginForm->isValid($data))
			{
				$login = $data['login'];
				$password =  $data['password'];

				//création d'un adpatateur d'authentification utilisant une base de données
				//le premier argument correspond à l'adptateur par défaut
				//le second correspond à la table qui est utilisée pour l'authentification
				//le troisième indique la colonne utilisée pour représenter l'identité (le login)
				//le quatrième argument indique la colonne utilisée pour représenter le crédit (le password)
				$authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table_Abstract::getDefaultAdapter(), 'users', 'login', 'password');

				//création de validateurs permettant de vérifier si certaines exigences sont respectées
				//par le login et le password
				//Zend_Validate_NotEmpty(): permet de vérifier que la valeur n'est pas vide
				//Zend_Validate_StringLength(4) vérifie la taille minimum d'une chaine
				$validatorLogin = new Zend_Validate();
				$validatorLogin->addValidator(new Zend_Validate_NotEmpty());
				$validatorLogin->addValidator(new Zend_Validate_StringLength(4));
				$validatorPassword = new Zend_Validate();
				$validatorPassword->addValidator(new Zend_Validate_NotEmpty());
				$validatorPassword->addValidator(new Zend_Validate_StringLength(4));

				//Vérification que le login et le password respectent les validateurs
				if($validatorLogin->isValid($login) && $validatorPassword->isValid($password))
				{
					//préparation de la requête d'authentification en indiquant l'identité et le crédit
					$authAdapter->setIdentity($login);
					$authAdapter->setCredential($password);

					//exécution de la requête d'authentification et enregistrement du résultat
					$result = $this->_auth->authenticate($authAdapter);

					//si l'authentification a réussi
					if($result->isValid())
					{
						//stockage de l'identité sous forme d'objet
						//le permier argument permet d'indiquer les valeurs que l'on veut enregistrer (null indique que l'on enreegistre l'entièreté de l'objet)
						//le second argument permet d'indiquer les valeurs que l'on ne souhaite pas enregistrer
						$this->_auth->getStorage()->write($res = $authAdapter->getResultRowObject(null, 'password'));

						//permet de regénérer l'identifiant de session
						Zend_Session::regenerateId();

						//redirection
						$this->_helper->_redirector('index', 'users');
					}
				}
			}
		}
		else
		{
			//redirection si la page n'a pas été appelée à partir d'un formulaire
			$this->_redirect($this->view->url(array('controller' => 'login'), 'default', true));
		}
	}

	public function logoutAction()
	{
		//réinitialisation de l'instance d'authentification et destruction de la session
		Zend_Auth::getInstance()->clearIdentity();
		Zend_Session::destroy();
		$this->_helper->redirector('index', 'index');
	}
}