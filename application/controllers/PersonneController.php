<?php
#region MBA
class PersonneController extends Zend_Controller_Action
{
    // indexAction() ici ...
 
	public function preDispatch(){
		
    	    $doctypeHelper = new Zend_View_Helper_Doctype();
            $doctypeHelper->doctype('HTML5');
    
        	$this->_helper->layout->setLayout('mylayout');
    	
    	   
    	
	}
	
	public function indexAction()
		{  
		
			 //création d'un d'une instance Default_Model_Personne
			$personne = new Default_Model_Personne();
			
	
			//création de notre objet Paginator avec comme paramètre la méthode
			//récupérant toutes les entrées dans notre base de données
			$paginator = Zend_Paginator::factory($personne->fetchAll($str =array()));
			
			//indique le nombre déléments à afficher par page
			$paginator->setItemCountPerPage(20);
			
			//récupère le numéro de la page à afficher
			$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));
			
			$this->view->personneArray = $paginator;
			// instanciation de la session
			
		}
		
    public function createpAction()
    {
    	$this->view->title ='Ajout ressources Marocaines';
        $request = $this->getRequest();
        $form    = new Default_Form_PersonneMa();
        
        //MBA : Peuplé les listes déroulantes à partir de la Base de donnée
        $form->setDbOptions('fonction',new Default_Model_Fonction());
        $form->setDbOptions('pole', new Default_Model_Pole());
       
	
		
        if ($this->getRequest()->isPost()) {
        	
            $data = $request->getPost();
        	if ($form->isValid($request->getPost())) {
            	
            	$personne = new Default_Model_Personne();
            	
            	$IsExist = $personne->IsExist($data['Nom'], $data['Prenom']);
            	if($IsExist>0)
            	{
            		$this->view->error = " " . $data['Nom']." ".$data['Prenom']." existe déjà !";
            	}
            	else 
            	{
            		
            	$data['date_entree'] = date('Y-m-d',strtotime($data['date_entree']));
                $data['date_debut'] = date('Y-m-d',strtotime($data['date_debut']));
                $data['date_fin'] = date('Y-m-d',strtotime('00/00/0000'));
	               
	            $personne = new Default_Model_Personne($data);
            	
            	/*
            	 * centre de service,modalité et entité figés pour le csm
            	 */
            	
            	$personne->setEntite("2");
            	$personne->setModalite("7");
            	
            	
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
                
            	 $this->view->success = $data['Nom'] . " a été bien créé <a href='".$this->_helper->url->url(array('controller' => 'personne', 'action' => 'index'), 'default', true)."'>Afficher la table Personnels </a>";
            
                }
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
	        $form    = new Default_Form_PersonneFr();
	        
	        //MBA : Peuplé les listes déroulantes à partir de la Base de donnée
	        $form->setDbOptions('fonction',new Default_Model_Fonction());
	        $form->setDbOptions('pole', new Default_Model_Pole());
	        
	        // condition sur le champ modalité pour ne pas affiché aucune modalité (propore au csm)
	        $where = array(
	        'libelle <> ?' => 'Aucune modalite');
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
	            	
	        	$IsExist = $personne->IsExist($data['Nom'], $data['Prenom']);
	            	if($IsExist  > 0)
	            	{
	            		$this->view->error = " ".$data['Nom']." ".$data['Prenom']." existe déjà !";
	            	}
	            	else 
	            	{
	            		
	                $data['date_entree'] = date('Y-m-d',strtotime($data['date_entree']));
	                $data['date_debut'] = date('Y-m-d',strtotime($data['date_debut']));
	                $data['date_fin'] = date('Y-m-d',strtotime('00/00/0000'));
	                $personne = new Default_Model_Personne($data);
	                
	            	
	            	
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
	                 
	            	 $this->view->success = $data['Nom'] . " a été bien créé <a href='".$this->_helper->url->url(array('controller' => 'personne', 'action' => 'index'), 'default', true)."'>Afficher la table Personnels </a>";
	            
	            
	        	    }
	        	}
	            else {
	            	
	            	var_dump($data);
	            	$form->populate($data); 
	            }  
	        }
	      $this->_helper->viewRenderer('createp');
	        $this->view->form = $form;
	    }
    
	public function editAction()
		{
			$id = $this->_getParam('id');
			$personne = new Default_Model_Personne();
			$personne = $personne->find($id);
			
			
			/*
			 * Génération de formulaire ressource Marocaine
			 */
			if( $personne->getEntite()->getCs() == "1")
			  {//If entité marocaine
				
			  	$form    = new Default_Form_PersonneMa();
				//MBA : Peuplé les listes déroulantes à partir de la Base de donnée
		        $form->setDbOptions('fonction',new Default_Model_Fonction());
		        $form->setDbOptions('pole', new Default_Model_Pole());
				$this->view->title ='Modification ressource Marocaine';
				
				$PreData['Nom'] = $personne->getNom();
				$PreData['Prenom'] = $personne->getPrenom();
				$PreData['fonction']=  intval($personne->getFonction()->getId());
				$PreData['pole'] = intval($personne->getPole()->getId());
				$PreData['date_debut'] = date('Y-m-d',strtotime($personne->getDate_debut()));
				$PreData['date_entree'] = date('Y-m-d',strtotime($personne->getDate_entree()));
			    $PreData['pourcent'] = intval($personne->getPourcent());
				$PreData['Stage'] = $personne->getStage();
				
				$form->populate($PreData);
				
				$this->_helper->viewRenderer('createp');
				$this->view->form = $form;
				
				if($this->getRequest()->isPost())
		        {
				//récupération des données envoyées par le formulaire
				     $data = $this->getRequest()->getPost();
	              //vérifie que les données répondent aux conditions des validateurs
				     if($form->isValid($data))
					 {
					  	    $isArrayEquals = true;
					   // vérifie si les données ont subit une modification
					        foreach($PreData as $k=>$v)
					        {
					        	if((string)$PreData[$k] != (string)$data[$k])
					        	{
					        		
					        		$isArrayEquals = false ;
					        	}
					        	
					        }
					      	
					    if($isArrayEquals == true)
			        	   {
			        	 	$this->view->info = "Vous n'avez modifié aucun champ";
			        	    
			         	   }
			         	else 
			               {
			               	
			               	 $data['id'] = $personne->getId();
			               	 $data['date_entree'] = date('Y-m-d',strtotime($data['date_entree']));
				             $data['date_debut'] = date('Y-m-d',strtotime($data['date_debut']));
				             $data['date_fin'] = date('Y-m-d',strtotime('00/00/0000'));
				             $data['entite'] = $personne->getEntite();
				             $data['modalite'] = $personne->getModalite();
				            
				              $personne = new Default_Model_Personne($data);
			               	
		                           
		
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
						                
						            	 $this->view->success = $data['Nom'] . " a été bien modifié <a href='".$this->_helper->url->url(array('controller' => 'personne', 'action' => 'index'), 'default', true)."'>Afficher la table Personnels </a>";
									
								  } // fin modification personne
		            } // fin formulaire valide
		            else
		            {
		            	$form->populate($data); 
		            	
		            }
				
				
				$this->_helper->viewRenderer('createp');
				$this->view->form = $form;
				
		        	}// fin formulaire envoyé
			  } // fin if entité marocaine
			elseif( $personne->getEntite()->getCs() == "0")
			{
				
				$form    = new Default_Form_PersonneFr();

				//MBA : Peuplé les listes déroulantes à partir de la Base de donnée
			        $form->setDbOptions('fonction',new Default_Model_Fonction());
			        $form->setDbOptions('pole', new Default_Model_Pole());
			        
			        // condition sur le champ modalité pour ne pas affiché aucune modalité (propore au csm)
			        $where = array(
			        'libelle <> ?' => 'Aucune modalite');
			        $form->setDbOptions('modalite', new Default_Model_Modalite(),'getId','getLibelle',$where);
			
			        // condition sur le champ cs pour n'affiché que les entité non cs .
			        $where = array(
			        'cs = ?' => 0);
			        $form->setDbOptions('entite', new Default_Model_Entite(),'getId','getLibelle',$where);
	        
				$this->view->title ='Modification ressource Française';
				
					$PreData['Nom'] = $personne->getNom();
					$PreData['Prenom'] = $personne->getPrenom();
					$PreData['fonction']= $personne->getFonction()->getId();
					$PreData['pole'] = $personne->getPole()->getId();
					$PreData['modalite'] = $personne->getModalite()->getId();
					$PreData['entite'] = $personne->getEntite()->getId();
					$PreData['date_debut'] = date('Y-m-d',strtotime($personne->getDate_debut()));
					$PreData['date_entree'] = date('Y-m-d',strtotime($personne->getDate_entree()));
					$PreData['pourcent'] = $personne->getPourcent();
					$PreData['Stage'] = $personne->getStage();
				
				$form->populate($PreData);
				$this->_helper->viewRenderer('createp');
				$this->view->form = $form;
				
			if($this->getRequest()->isPost())
		        	{
				//récupération des données envoyées par le formulaire
				     $data = $this->getRequest()->getPost();
	              //vérifie que les données répondent aux conditions des validateurs
				     if($form->isValid($data))
				      {
					       $isArrayEquals = true;
					   // vérifie si les données ont subit une modification
					        foreach($PreData as $k=>$v)
					        {
					        	if((string)$PreData[$k] != (string)$data[$k])
					        	{
					        		$isArrayEquals = false;
					        	}
					        	
					        }
					      	
						     if($isArrayEquals == true)
				        	   {
				        	 	$this->view->info = "Vous n'avez modifié aucun champ";
				        	   
				         	   }
				         	else 
				               {
				               	
				                    $data['id'] = $personne->getId();
					               	$data['date_entree'] = date('Y-m-d',strtotime($data['date_entree']));
					                $data['date_debut'] = date('Y-m-d',strtotime($data['date_debut']));
					                $data['date_fin'] = date('Y-m-d',strtotime('00/00/0000'));
					            
					                $personne = new Default_Model_Personne($data);
					          
	            	
				            	try {
				            			$personne->save();
				            	    }
				                catch (Zend_Db_Exception $e)
				                {
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
	                 
	                    $this->view->success = $data['Nom']. " a été bien modifié <a href='".$this->_helper->url->url(array('controller' => 'personne', 'action' => 'index'), 'default', true)."'>Afficher la table Personnels </a>";
						
						
					     	  }// fin modification personne
		          
				
			}// fin formulaire valide
			else 
			{
				$form->populate($data); 
			}
				
				
				$this->_helper->viewRenderer('createp');
				$this->view->form = $form;
			} // fin formulaire envoyé
			
		}// fin entité française

		}
		public function deleteAction() {
			
			//à la reception d'une requête ajax
		
			if($this->getRequest()->isXmlHttpRequest())
			{
				//récupération des données envoyé par ajax
				$data = $this->getRequest()->getPost();
				$id = $data['id'];
				
				$personne = new Default_Model_Personne();
					//appel de la fcontion de suppression avec en argument,
					//la clause where qui sera appliquée
					try {
					      $result = $personne->delete("id=$id");
					      
				    	}
					catch (Zend_Db_Exception $e)
					   {
					    // en cas d'erreur envoi de reponse avec code erreur [500]
					       $content = array("status"=>"500","result"=> $result);
       					  $this->view->error= "Erreur";
       				      $this->_helper->json($content);
       				      
       				       echo $content;
			        	}
			        	//en cas de succés envoie de reponse avec code succés [200]
				         $this->view->success = "Suppression a été effectué";
			        	 $content = array("status"=>"200","result"=> "1");
       					
                        // envoi de reponse en format Json
       		       		$this->_helper->json($content);
       
                         
        
       
        
				
			}
			
			
		}
		
			
}
#endregion MBA
