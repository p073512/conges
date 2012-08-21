<?php
class Default_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
	//tableau associatif reprenant les infos utilisées pour le lien
	//si l'utilisateur n'est pas authentifié
	private $_noauth = array(
		'module' => 'default',
		'controller' => 'login',
		'action' => 'index'
	);
 
	//tableau associatif reprenant les infos utilisées pour le lien
	//si l'utilisateur est authentifié mais qu'il n'a pas les droits d'accès
	private $_noacl = array(
		'module' => 'default',
		'controller' => 'error',
		'action' => 'deny'
	);
 
	//la méthode événementielle preDispatch(), définie par ZF, est exécutée
	//avant qu'une action ne soit distribuée
	public function preDispatch(Zend_Controller_Request_Abstract $request)
	{
		$acl = null;
		$role = null;
 
		//vérification de l'enregistrement de l'ACL (cf. application/acls/MyAcl.php)
		if(Zend_Registry::isRegistered('My_Acl'))
		{
			//récupération de l'ACL
			$acl = Zend_Registry::get('My_Acl');
		}
		else
		{
			throw new Zend_Controller_Exception("Acl not defined !");
		}
 
		//récupération de l'instance d'identification
		//la classe Zend_Auth permet de définir les adaptateurs d'authentification
		//un adpatateur permet de définir le service d'authentification,
		//dans notre cas, une base de données
		$auth = Zend_Auth::getInstance();
 
		//permet de vérifier si une identité est correctement identifiée
		if($auth->hasIdentity())
		{
			//récupération du role (via la database)
			// PTRI - notre login fait office de role (equipe, admin ou csm)
			$role = $auth->getIdentity()->role;
		}
		else
		{
			//si l'utilisateur n'est pas authentifié
			//on définit le rôle par défaut, guest
			// PTRI - le role guest ne doit être autorisé qu'à visualiser la page de login (aucun droit)
			$role = 'guest';
		}
 
		//récupération du module, contrôleur et action demandés par la requête
		//comme nous avons utilisé les contrôleurs comme ressource,
		//nous stockons le contrôleur demandé dans la requête dans la variable $ressource
		$module = $request->getModuleName();
		$controller = $ressource = $request->getControllerName();
		$action = $request->getActionName();
 
		//vérification que le contrôleur est définit dans l'ACL
		if(!$acl->has($controller))
		{
			$ressource = null;
		}
 
		//si l'accès n'est pas permis, nous allons modifier la requête
		//en modifiant le module, le contrôleur et l'action
		if(!$acl->isAllowed($role, $ressource, $action))
		{
			//si pas authentifié
			if(!$auth->hasIdentity())
			{
				//$request->setParam('redirect', $module . '/' . $controller . '/' . $action);
				$module = $this->_noauth['module'];
				$controller = $this->_noauth['controller'];
				$action = $this->_noauth['action'];
			}
			else
			{
				//si pas autorisé
				$module = $this->_noacl['module'];
				$controller = $this->_noacl['controller'];
				$action = $this->_noacl['action'];
			}
		}
 
		//définition des du module, du contrôleur et de l'action
		//qui sera maintenant routée
		$request->setModuleName($module);
		$request->setControllerName($controller);
		$request->setActionName($action);
	}
}