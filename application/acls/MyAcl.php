<?php
class Default_Acl_MyAcl extends Zend_Acl
{
	//constructeur
	public function __construct()
	{
		$this->_initRessources();
		$this->_initRoles();
		$this->_initRights();

		//Zend_Registry permet de gérer une collection de valeurs qui
		//sont peuvent être accessibles n'importe où dans notre application
		//ont peut comparer son fonctionnement à une variable globale
		Zend_Registry::set('My_Acl', $this);
	}

	protected function _initRessources()
	{
		//création des ressources
		//une ressource correspond à un élément pour lequel l'accès est contrôlé
		//ici, nous créons une ressource par contrôleur, ce qui signifie
		//que nous allons contrôler l'accès à nos contrôleurs
		//la méthode addRessource() permet d'ajouter les ressources à l'ACL
		$this->addResource(new Zend_Acl_Resource('index'));
		$this->addResource(new Zend_Acl_Resource('error'));
		$this->addResource(new Zend_Acl_Resource('connexion'));
		$this->addResource(new Zend_Acl_Resource('login'));
		$this->addResource(new Zend_Acl_Resource('users'));
		$this->addResource(new Zend_Acl_Resource('test'));
	}

	protected function _initRoles()
	{
		//création des rôles
		//un rôle est un objet qui demande l'accès aux ressources
		//nous allons, ici, utiliser 3 rôles:
		//  - guest: compte invité avec des droits limités
		//  - reader: simple accès en lecture
		//  - admin: accès total au site (lecture écriture
		$guest = new Zend_Acl_Role('guest');
		$equipe = new Zend_Acl_Role('equipe');
		$csm = new Zend_Acl_Role('csm');
		$admin = new Zend_Acl_Role('admin');

		//ajout des rôles à l'ACL avec la méthode addRole()
		//le premier argument est le rôle à ajouter à l'ACL
		//le second argument permet d'indiquer l'héritage du groupe parent
		//reader va hériter des droits de guest
		//admin va hériter des droits de reader
		$this->addRole($guest);
		$this->addRole($equipe, $guest);
		$this->addRole($csm, $equipe);
		$this->addRole($admin, $csm);
	}

	protected function _initRights()
	{
		//définition des règles
		//la méthode allow permet d'indiquer les permissions de chaque rôle
		//le premier argument permet de définir le rôl pour qui la régle est écrite
		//le second argument permet d'indiquer les contrôleurs
		//le troisième indique les actions du contrôleur
		//à noter qu'il aussi possible de refuser un accès grâce à la fonction deny()
		$this->allow('guest', array('index','error','connexion','login','test'));
		/*
		$this->allow('equipe', 'users', 'index');
		$this->allow('csm');
		$this->allow('admin', 'users');
		*/
		$this->allow('equipe');
		$this->allow('csm');
		$this->allow('admin');
	}
}