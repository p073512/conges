<?php

//un controller doit hériter de la classe Zend_Controller_Action,
//il doit respecter la nomenclature [ControllerName]Controller
class IndexController extends Zend_Controller_Action
{
	
	function preDispatch()
	{
		$auth = Zend_Auth::getInstance();

		if (!$auth->hasIdentity()) {
			$this->_redirect('login/index');
		}
	}

	function init()
	{
		$this->initView();
		//Zend_Loader::loadClass('Album');
		$this->view->baseUrl = $this->_request->getBaseUrl();
		$this->view->role = Zend_Auth::getInstance()->getIdentity();
	}
	
	//une action doit respecter la nomenclature [actionName]Action
	public function indexAction()
	{
		//action appelée par défaut lors de l'affichage de l'index
		//la vue application/views/scripts/index/index.phtml est
		//automatiquement utilisée
	}

}

