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
	
			//$this->view permet d'accéder à la vue qui sera utilisée par l'action
			//on initialise la valeur usersArray de la vue
			//(cf. application/views/scripts/users/index.phtml)
			$this->view->personneArray = $paginator;
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
            		$this->view->error = "Personne existe déjà !";
            	}
            	else 
            	{
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
	            		$this->view->error = "Personne existe déjà !";
	            	}
	            	else 
	            	{
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
			if( $personne->getEntite()->find($personne->getId_entite())->getCs() == "1")
			  {//If entité marocaine
				
			  	$form    = new Default_Form_PersonneMa();
				//MBA : Peuplé les listes déroulantes à partir de la Base de donnée
		        $form->setDbOptions('fonction',new Default_Model_Fonction());
		        $form->setDbOptions('pole', new Default_Model_Pole());
				$this->view->title ='Modification ressource Marocaine';
				
				$PreData['Nom'] = $personne->getNom();
				$PreData['Prenom'] = $personne->getPrenom();
				$PreData['fonction']=  intval($personne->getId_fonction());
				$PreData['pole'] = intval($personne->getId_pole());
				$PreData['date_debut'] = date('Y-m-d',strtotime($personne->getDate_debut()));
				$PreData['date_entree'] = date('Y-m-d',strtotime($personne->getDate_entree()));
			    $PreData['pourcentage'] = intval($personne->getPourcent());
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
					  	    $i = 1;
					   // vérifie si les données ont subit une modification
					        foreach($PreData as $k=>$v)
					        {
					        	if((string)$PreData[$k] != (string)$data[$k])
					        	{
					        		$i*=0;
					        	}
					        	
					        }
					      	
					    if($i == 1)
			        	   {
			        	 	$this->view->error = "Vous n'avez modifié aucun champ";
			        	     var_dump($data);
			        	     var_dump($PreData);
			        	 	
			         	   }
			         	else 
			               {
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
			elseif( $personne->getEntite()->find($personne->getId_entite())->getCs() == "0")
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
					$PreData['fonction']= $personne->getId_fonction();
					$PreData['pole'] = $personne->getId_pole();
					$PreData['modalite'] = $personne->getId_modalite();
					$PreData['entite'] = $personne->getId_Entite();
					$PreData['date_debut'] = date('Y-m-d',strtotime($personne->getDate_debut()));
					$PreData['date_entree'] = date('Y-m-d',strtotime($personne->getDate_entree()));
					$PreData['pourcentage'] = $personne->getPourcent();
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
					       $i = 1;
					   // vérifie si les données on subit une modification
					        foreach($PreData as $k=>$v)
					        {
					        	if((string)$PreData[$k] != (string)$data[$k])
					        	{
					        		$i*=0;
					        	}
					        	
					        }
					      	
						     if($i == 1)
				        	   {
				        	 	$this->view->error = "Vous n'avez modifié aucun champ";
				        	     var_dump($data);
				        	     var_dump($PreData);
				        	 	
				         	   }
				         	else 
				               {
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
		public function deleteAction()
			{
				//récupére les paramètres de la requête
				$params = $this->getRequest()->getParams();
		
				//vérifie que le paramètre id existe
				if(isset($params['id']))
				{
					$id = $params['id'];
		
					//création du modèle pour la suppression
					$personne = new Default_Model_Personne();
					//appel de la fcontion de suppression avec en argument,
					//la clause where qui sera appliquée
					try {
					$result = $personne->delete("id=$id");
					}
					catch (Zend_Db_Exception $e)
					{
					
						
					//redirection
					
				}
				$this->view->success = 'La suppression de la ressource a été bien effectuée !';
				$this->_helper->redirector('index');
				}
				else
				{
					$this->view->error = 'Suppression impossible id manquant !';
				$this->_helper->redirector('index');			}
			}
}
#endregion MBA