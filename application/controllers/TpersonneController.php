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
    	$this->view->title ='Ajout ressources Marocaines';
        $request = $this->getRequest();
        $form    = new Default_Form_TPersonne();
        
        //MBA : Peuplé les listes déroulantes à partir de la Base de donnée
        $form->setDbOptions('fonction',new Default_Model_Fonction());
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
            	$personne->setId_fonction($data['fonction']);
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
            	
            	try {
            			$personne->save();
            	}
                catch (Zend_Db_Exception $e){
                	  if($form->getElement('fonction')->getValue() == 'x') 
                      $form->getElement('fonction')->addError("erreur");

                      if($form->getElement('pole')->getValue() == 'x') 
                      $form->getElement('pole')->addError("erreur");
				        $this->view->error = "Erreur d'insertion : ".$e->getMessage();
				     	$form->populate($data); 
                     
                }
                 
            	 $this->view->success = $data['Nom'];
            
            }
            else {
            	
            	
            	$form->populate($data); 
            }  
        }
      
        $this->view->form = $form;
    }
    
public function createpfAction()
    {
    	$this->view->title ='Ajout ressources Françaises';
        $request = $this->getRequest();
        $form    = new Default_Form_TFPersonne();
        
        //MBA : Peuplé les listes déroulantes à partir de la Base de donnée
        $form->setDbOptions('fonction',new Default_Model_Fonction());
        $form->setDbOptions('pole', new Default_Model_Pole());
        
        // condition sur le champ modalité pour ne pas affiché aucune modalité (propore au csm)
        $where = array(
        'libelle != ?' => 'Aucune modalite');
        $form->setDbOptions('modalite', new Default_Model_Modalite(),'getId','getLibelle',$where);

        // condition sur le champ cs pour n'affiché que les entité non cs .
        $where = array(
        'cs = ?' => 0);
        $form->setDbOptions('entite', new Default_Model_Entite(),'getId','getLibelle',$where);
        $personne = new Default_Model_Personne();
       
      
       
		//$form->getElement('fonctions')->setOptions(array('MultiOptions' => Default_Model_Fonction::getFonctions(new Default_Model_Pole()) ));
		
        if ($this->getRequest()->isPost()) {
        	
            $data = $request->getPost();
        	if ($form->isValid($request->getPost())) {
            	
            	
            	$personne->setNom($data['Nom']);
            	$personne->setPrenom($data['Prenom']);
            	$personne->setDate_entree($data['date_entree']);
	              $entite = $personne->getEntite()->find((int) $data['entite']);
	               $personne->setId_entite($entite->getId());
	               $personne->setCentre_service($entite->getCs());
            	$personne->setId_modalite($data['modalite']);
            	$personne->setId_pole($data['pole']);
            	$personne->setId_fonction($data['fonction']);
            	$personne->setPourcent($data['pourcentage']);
            	$personne->setStage($data['Stage']);
            	$personne->setDate_debut($data['date_debut']);
            	$personne->setDate_fin('00/00/0000');
            	
            	/*
            	 * centre de service,modalité et entité figés pour le csm
            	 */
            	
            
            	
            	try {
            			$personne->save();
            	}
                catch (Zend_Db_Exception $e){
                	  if($form->getElement('fonction')->getValue() == 'x') 
                      $form->getElement('fonction')->addError("erreur");
                      
                       if($form->getElement('modalite')->getValue() == 'x') 
                      $form->getElement('modalite')->addError("erreur");
                      
                	  if($form->getElement('entite')->getValue() == 'x') 
                      $form->getElement('entite')->addError("erreur");
                      
                      if($form->getElement('pole')->getValue() == 'x')
                      $form->getElement('pole')->addError("erreur");
                      
				        $this->view->error = "Erreur d'insertion : ".$e->getMessage();
				     	$form->populate($data); 
                      var_dump($data);
                }
                 
            	 $this->view->success = $data['Nom'];
            
            }
            else {
            	
            	var_dump($data);
            	$form->populate($data); 
            }  
        }
      $this->_helper->viewRenderer('createp');
        $this->view->form = $form;
    }
}
#endregion MBA