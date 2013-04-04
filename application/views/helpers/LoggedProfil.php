<?php
class Zend_View_Helper_LoggedProfil
{
	protected $_view;

	function setView($view)
	{
		$this->_view = $view;
	}

	function loggedProfil()
	{
		$auth = Zend_Auth::getInstance();
		if($auth->hasIdentity())
		{
			//création du lien logout à partir de l'aide de vue url
			$logoutUrl = $this->_view->url(array('controller' =>'connexion', 'action' => 'deconnexion'), 'default', true);
			//récupère l'identité de l'utilisateur
			$user = $auth->getIdentity();
			//$username = $this->_view->escape($user->login);
			$profil = $this->_view->escape($user->login);
			//chaine qui sera affichée si l'utilisateur est connecté
			$link = 'Bonjour, ' . '  Vous avez le profil ' . $profil . '  <a href="' . $logoutUrl . '">Deconnexion</a>';
		}
		else
		{
			//création du lien loin à partir de l'aide de vue url
			$loginUrl = $this->_view->url(array('controller' => 'connexion'), null, true);
			//chaine qui sera affichée si l'utilisateur n'est pas connecté
			$link = '<a href="' . $loginUrl . '">Connexion</a>';
		}

		return $link;
	}
}