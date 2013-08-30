<?php

//un controller doit hériter de la classe Zend_Controller_Action,
//il doit respecter la nomenclature [ControllerName]Controller
class IndexController extends Zend_Controller_Action
{
	
	function preDispatch()
	{
	
          $doctypeHelper = new Zend_View_Helper_Doctype();
          $doctypeHelper->doctype('HTML5');
        
     
		
		$auth = Zend_Auth::getInstance();

		if (!$auth->hasIdentity()) 
		{
			$this->_redirect('connexion/index');
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
  $this->_helper->redirector('afficher','proposition') ;      
}
}
