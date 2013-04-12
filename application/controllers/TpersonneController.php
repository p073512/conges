<?php
#region MBA
class TpersonneController extends Zend_Controller_Action
{
    // indexAction() ici ...
 
    public function createpAction()
    {
        $request = $this->getRequest();
        $form    = new Default_Form_TPersonne();
        
        //MBA : Peuplé les listes déroulantes à partir de la Base de donnée
        $form->setDbOptions('fonctions',new Default_Model_Fonction());
        $form->setDbOptions('pole', new Default_Model_Pole());
       
		//$form->getElement('fonctions')->setOptions(array('MultiOptions' => Default_Model_Fonction::getFonctions(new Default_Model_Pole()) ));
		
        if ($this->getRequest()->isPost()) {
        	
            if ($form->isValid($request->getPost())) {
            	
                return $this->_helper->redirector('createp');
            }
        }
 
        $this->view->form = $form;
    }
}
#endregion