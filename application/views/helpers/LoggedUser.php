<?php
class Zend_View_Helper_LoggedUser
{
	protected $_view;

	function setView($view)
	{
		$this->_view = $view;
	}

	function loggedUser()
	{
		$auth = Zend_Auth::getInstance();
		if($auth->hasIdentity())
		{
			//création du lien logout à partir de l'aide de vue url
			$logoutUrl = $this->_view->url(array('controller' =>'login', 'action' => 'logout'), 'default', true);
			//récupère l'identité de l'utilisateur
			$user = $auth->getIdentity();
			$username = $this->_view->escape($user->login);
			$role = $this->_view->escape($user->role);
			//chaine qui sera affichée si l'utilisateur est connecté
			$link = 'Welcome, ' . $username .  ' | Vous avez le role ' . $role . ' | <a href="' . $logoutUrl . '">Log out</a>';
		}
		else
		{
			//création du lien loin à partir de l'aide de vue url
			$loginUrl = $this->_view->url(array('controller' => 'login'), null, true);
			//chaine qui sera affichée si l'utilisateur n'est pas connecté
			$link = '<a href="' . $loginUrl . '">Log in</a>';
		}

		return $link;
	}
}