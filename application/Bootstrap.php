<?php

//les méthodes préfixées de '_init' sont automatiquement appelées lors de
//l'appel de la méthode bootstrap() de l'interface Zend_Application_Bootstrap_Bootstrapper
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{



	//bootstrap le doctype des vues
	protected function _initDoctype()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->doctype('XHTML1_STRICT');
	}

	protected function _initAutoloadRessource()
	{
		//configuration de l'Autoload
		$ressourceLoader = new Zend_Loader_Autoloader_Resource(array(
		'namespace' => 'Default',
		'basePath'  => dirname(__FILE__),
		));

		//permet d'indiquer les répertoires dans lesquels se trouveront nos classes:
		//notamment, l'ACL et le plugin
		$ressourceLoader->addResourceType('form', 'forms/', 'Form')
		->addResourceType('acl', 'acls/', 'Acl')
		->addResourceType('model', 'models/', 'Model')
		->addResourceType('plugin', 'controllers/plugins/', 'Controller_Plugin')
		->addResourceType('helper', 'controllers/helpers/', 'Controller_Helpers')
		->addResourceType('session', 'sessions/', 'Session');
		return $ressourceLoader;
	}

	protected function _initHelpers()
	{
		Zend_Controller_Action_HelperBroker::addHelper(new Default_Controller_Helpers_Validation());
	}
	
	protected function _initJQuery()
	{
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');

		// d'initailiser le jquery sur toutes les vues
		$view->jQuery()->enable();
		$view->jQuery()->uiEnable();

	}

	protected function _initAcl()
	{
		//Création d'une instance de notre ACL
		$acl = new Default_Acl_MyAcl();

		$plugin_acl = new Default_Controller_Plugin_Acl();

		//enregistrement du plugin de manière à ce qu'il soit exécuté
		Zend_Controller_Front::getInstance()->registerPlugin(new Default_Controller_Plugin_Acl());

		//permet de définir l'acl par défaut à l'aide de vue, de cette manière
		//l'ACL est accessible dans les vues
		Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);

		//vérifie si une identité existe et applique le rôle
		$auth = Zend_Auth::getInstance();
		$role = (!$auth->hasIdentity()) ? 'guest' : $auth->getIdentity()->login;
	}

	/*
	 * initialisation de la vue layout
	 */
	protected function _initView()
	{
		// Initialize view
		$view = new Zend_View();
		$view->doctype('XHTML1_STRICT');
		$view->headTitle('Gestion de conge');
		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');
		// Add it to the ViewRenderer
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view);
		//initialisation de layout
		Zend_Layout::startMvc(array('layoutPath'=>'./application/layouts'));
		// Return it, so that it can be stored by the bootstrap
		return $view;
	}

	protected function _initSidebar()
	{
		$this->bootstrap('View');
		$view = $this->getResource('View');

		$view->placeholder('sidebar');
	}

	/**
	 * Initialize session
	 *
	 * @return Zend_Session_Namespace
	 */
	protected function _initSession()
	{
		// On initialise la session
		Zend_Session::start();
		$mois = new Zend_Session_Namespace('salut',false);
		$date=date('D/d/m/Y');
		list($dcourt,$day, $m, $y) = explode("/", $date);
		$mois->mois = (int)$m;// IL FAUT PROPGRAMMER UNE FONCTION AU NIVEAU DE BOOSTTRAP POUR INITIALISER LES SESSIONS
		$mois->annee = $y;
		$_SESSION['salut']['mois']= (int)$m;
		return $mois;
	}
	
	protected function _initTimezone()
	{
		date_default_timezone_set("Europe/Paris");
	}

	/**
	 * Initialize log
	 *
	 * @return ?
	 */
	protected function _initLog()
	{
//		require_once('Zend/Log.php');
//		require_once('Zend/Log/Writer/Stream.php');

		$logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('php://output');
		$logger->addWriter($writer);
//		$logger->log('sample message!', Zend_Log::INFO);

	}

}