<?php
#region MBA
class TpersonneController extends Zend_Controller_Action
{
    // indexAction() ici ...
 
	public function preDispatch(){
		
    	    $doctypeHelper = new Zend_View_Helper_Doctype();
            $doctypeHelper->doctype('HTML5');
    
    	$this->_helper->layout->setLayout('mylayout');
	}
    public function createpAction()
    {
    	
        $request = $this->getRequest();
        $form    = new Default_Form_TPersonne();
        
        //MBA : Peuplé les listes déroulantes à partir de la Base de donnée
        $form->setDbOptions('fonctions',new Default_Model_Fonction());
        $form->setDbOptions('pole', new Default_Model_Pole());
       
		//$form->getElement('fonctions')->setOptions(array('MultiOptions' => Default_Model_Fonction::getFonctions(new Default_Model_Pole()) ));
		
        if ($this->getRequest()->isPost()) {
        	
            $data = $request->getPost();
        	if ($form->isValid($request->getPost())) {
            	
            	$personne = new Default_Model_Personne();
            	$personne->setNom($data['Nom']);
            	$personne->setPrenom($data['Prenom']);
            	$personne->setDate_entree($data['date_entree']);
            	$personne->setId_pole($data['pole']);
            	$personne->setId_fonction($data['fonctions']);
            	$personne->setPourcent($data['pourcentage']);
            	$personne->setStage($data['Stage']);
            	$personne->setDate_debut($data['date_debut']);
            	$personne->setDate_fin('00/00/0000');
            	
            	/*
            	 * centre de service,modalité et entité figés pour le csm
            	 */
            	
            	$entite = $personne->getEntite()->find((int) "2");
            	
            	
            	$personne->setId_entite($entite->getId());
            	$personne->setCentre_service($entite->getCs());
            	$personne->setId_modalite("7");
            	var_dump($data);
            	try {
            			$personne->save();
            	}
                catch (Zend_Db_Exception $e){
                	    
                        $form->getElement('fonctions')->addError("erreur");
                        $form->getElement('pole')->addError("erreur");
				        $this->view->error = "Erreur d'insertion : ".$e->getMessage();
				     	$form->populate($data); 
                     
                }
                 
            	 $this->view->success = $data['Nom'];
            
            }
            else {
            	var_dump($data);
            	
            	$form->populate($data); 
            }  
        }
      
        $this->view->form = $form;
    }
}
#endregion MBA