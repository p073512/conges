<?php
#region MBA
class PersonneController extends Zend_Controller_Action
{
	// indexAction() ici ...

	public function preDispatch()
	{
		$doctypeHelper = new Zend_View_Helper_Doctype();
		$doctypeHelper->doctype('HTML5');
	}

	public function indexAction()
	{
		//création d'un d'une instance Default_Model_Personne
		$personne = new Default_Model_Personne();

		//création de notre objet Paginator avec comme paramétre la méthode
		//récupérant toutes les entrées dans notre base de données
		$paginator = Zend_Paginator::factory($personne->fetchAll($str =array()));

		//indique le nombre déléments é afficher par page
		$paginator->setItemCountPerPage(20);

		//récupére le numéro de la page é afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		$this->view->personneArray = $paginator;
		// instanciation de la session
	}
	
	
	
	
	
	
    // CreatepAction()  :  creer une personne de l'equipe CSM 
	public function createpAction()
	{
		$this->view->title ='<h4>Ajout ressources Marocaines</h4></br>';
		$request = $this->getRequest();
		$form    = new Default_Form_PersonneMa();

		//MBA : Peuplé les listes déroulantes é partir de la Base de donnée
		$form->setDbOptions('fonction',new Default_Model_Fonction());
		$form->setDbOptions('pole', new Default_Model_Pole());

		if ($this->getRequest()->isPost())
		{
			$data = $request->getPost();
			
			///////////////// récupération de l'url///////////////
			$requete = $this->getRequest();
			if ($requete instanceof Zend_Controller_Request_Http)
			{$baseurl = $requete->getBaseUrl();}
			
			
			if ($form->isValid($request->getPost()))       // formulaire valide 
			{	 //echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;formulaire valide';
			    $personne = new Default_Model_Personne();
		
				$IsExist = $personne->IsExist($data['Nom'], $data['Prenom']);
				
				if($IsExist>0)  // personne existe 
				{
					$this->view->warning = "Désolé la ressource : ". $data['Nom']." ".$data['Prenom']." existe déjà !";
							
					// vider les champs du formulaire
					$form->getElement('Nom')->setValue('');
					$form->getElement('Prenom')->setValue('');
					$form->getElement('fonction')->setValue('');
					$form->getElement('pole')->setValue('');
					$form->getElement('date_entree')->setValue('');
					$form->getElement('date_debut')->setValue('');
					$form->getElement('date_fin')->setValue('');
					$form->getElement('pourcent')->setValue('100');
					$form->getElement('Stage')->setValue('');
				}
				else           // personne inexistante 
				{  
				    if($data['date_entree'] > $data['date_debut'])  // si on a pas saisi une date_fin
					{
						$this->view->error = "La date d'entree doit étre inf&eacute;rieure ou &eacute;gale à la date de debut";
				     	$form->getElement('date_entree')->setView()->addError("");
				        $form->getElement('date_debut')->setView()->addError("");
					}
				     elseif($data['date_fin'] <> '')
				    { 
					     if ($data['date_debut'] > $data['date_fin']) // si date debut > date fin 
						 {$this->view->error = "La date de d&eacute;but doit étre inf&eacute;rieure ou &eacute;gale à la date de fin";
					     $form->getElement('date_debut')->setView()->addError("");
				         $form->getElement('date_fin')->setView()->addError("");}
				         else 
				         {
				         	// remplir l'objet personne avec les informations necessaires 
						    $personne->setNom($data['Nom']);
						    $personne->setPrenom($data['Prenom']);
							//centre de service,modalité et entité figés pour le csm
							$personne->setEntite("2");
							$personne->setModalite("7");
							$personne->setFonction($data['fonction']);  
		                    $personne->setPole($data['pole']);
		                    $personne->setDate_entree($data['date_entree']);
		                    $personne->setDate_debut($data['date_debut']);
		                    $personne->setDate_fin($data['date_fin']);
	                        $personne->setPourcent($data['pourcent']);
		                    $personne->setStage($data['Stage']);
						                    
							try 
							{
								$personne->save();
								$this->view->success = "la ressource : ". $data['Nom']." ".$data['Prenom']." a été crée avec succés  !";
							}
							catch (Zend_Db_Exception $e)
							{
								
								if($e->getMessage())
								$this->view->error = "Création de la ressource a échoué !";
								else
								{    // affichage du message de succès 
								    if($personne->getStage() == '1')
									{$this->view->success = "Création du stagiaire : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}
								    else 
									{$this->view->success = "Création de la ressource : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}  
									
								    // vider les champs du formulaire
									$form->getElement('Nom')->setValue('');
									$form->getElement('Prenom')->setValue('');
									$form->getElement('fonction')->setValue('');
									$form->getElement('pole')->setValue('');
									$form->getElement('date_entree')->setValue('');
									$form->getElement('date_debut')->setValue('');
									$form->getElement('date_fin')->setValue('');
									$form->getElement('pourcent')->setValue('100');
									$form->getElement('Stage')->setValue('');
								}
								$form->populate($data);			
							}
							
						
							  

			            } // fin else 
				    }// fin elseif	
				    else // sinon 
				    {     	
						    // remplir l'objet personne avec les informations necessaires 
						    $personne->setNom($data['Nom']);
						    $personne->setPrenom($data['Prenom']);
							//centre de service,modalité et entité figés pour le csm
							$personne->setEntite("2");
							$personne->setModalite("7");
							$personne->setFonction($data['fonction']);  
		                    $personne->setPole($data['pole']);
		                    $personne->setDate_entree($data['date_entree']);
		                    $personne->setDate_debut($data['date_debut']);
		                    $personne->setDate_fin($data['date_fin']);
	                        $personne->setPourcent($data['pourcent']);
		                    $personne->setStage($data['Stage']);
					                    
							try 
							{
								$personne->save();
								$this->view->success = "la ressource : ". $data['Nom']." ".$data['Prenom']." a été crée avec succés  !";
							}
							catch (Zend_Db_Exception $e)
							{
								if($e->getMessage())
								$this->view->error = "Création de la ressource a échoué !";
								else
								{    // affichage du message de succès 
								    if($personne->getStage() == '1')
									{$this->view->success = "Création du stagiaire : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}
								    else 
									{$this->view->success = "Création de la ressource : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}  
								}
								$form->populate($data);		
					     	}

							    // vider les champs du formulaire
								$form->getElement('Nom')->setValue('');
								$form->getElement('Prenom')->setValue('');
								$form->getElement('fonction')->setValue('');
								$form->getElement('pole')->setValue('');
								$form->getElement('date_entree')->setValue('');
								$form->getElement('date_debut')->setValue('');
								$form->getElement('date_fin')->setValue('');
								$form->getElement('pourcent')->setValue('100');
								$form->getElement('Stage')->setValue('');

					} // fin else 			
				}// fin else 
			} // fin if form valide 
				
			else     /****/// form invalide  
			{   // echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;formulaire invalide !!';
				$personne = new Default_Model_Personne();
				$IsExist = $personne->IsExist($data['Nom'], $data['Prenom']);
				if($IsExist>0)
				{
					$this->view->warning = "Désolé la ressource : ". $data['Nom']." ".$data['Prenom']." existe déjà !";
					
					// vider les champs du formulaire
					$form->getElement('Nom')->setValue('');
					$form->getElement('Prenom')->setValue('');
					$form->getElement('fonction')->setValue('');
					$form->getElement('pole')->setValue('');
					$form->getElement('date_entree')->setValue('');
					$form->getElement('date_debut')->setValue('');
					$form->getElement('date_fin')->setValue('');
					$form->getElement('pourcent')->setValue('100');
					$form->getElement('Stage')->setValue('');
                }
				else 
			 	{
			 	
				    if($data['Nom'] === '')  // si on a pas saisi un Nom
					{$this->view->error = "Veuillez saisir un nom !";}
					elseif($data['Prenom'] == '') // si on a pas saisi un Prenom
					{$this->view->error = "Veuillez saisir un prenom !";}
					elseif($data['fonction'] === 'x')  // si on a pas selectionné une fonction  id = 'x'
					{$this->view->error = "Veuillez selectionner une fonction !";}
					elseif($data['pole'] === 'x')  // si on a pas selectionné un pole  id = 'x'
					{$this->view->error = "Veuillez selectionner un pole !";}
					elseif($data['date_entree'] === '')  // si on a pas saisi une date_d'entree
					{$this->view->error = "Veuillez saisir une date d'entree !";}
					elseif($data['date_debut'] === '')  // si on a pas saisi une date_debut
					{$this->view->error = "Veuillez saisir une date debut !";}
				    elseif($data['date_entree'] > $data['date_debut'])  // si on a pas saisi une date_fin
					{$this->view->error = "La date d'entree doit étre inf&eacute;rieure ou &eacute;gale à la date de debut";
				     $form->getElement('date_entree')->setView()->addError("");
				     $form->getElement('date_debut')->setView()->addError("");}
				    elseif($form->getElement('pourcent')->hasErrors())                    
			        {$this->view->error = "Le pourcentage doit etre compris entre 0 et 100";}

				     elseif($data['date_fin'] <> '')
				    { 
					     if ($data['date_debut'] > $data['date_fin']) // si date debut > date fin 
						 {$this->view->error = "La date de d&eacute;but doit étre inf&eacute;rieure ou &eacute;gale à la date de fin";
					     $form->getElement('date_debut')->setView()->addError("");
				         $form->getElement('date_fin')->setView()->addError("");}
				         else 
				         {
				         
					            // remplir l'objet personne avec les informations necessaires 
							    $personne->setNom($data['Nom']);
							    $personne->setPrenom($data['Prenom']);
								//centre de service,modalité et entité figés pour le csm
								$personne->setEntite("2");
								$personne->setModalite("7");
								$personne->setFonction($data['fonction']);  
			                    $personne->setPole($data['pole']);
			                    $personne->setDate_entree($data['date_entree']);
			                    $personne->setDate_debut($data['date_debut']);
			                    $personne->setDate_fin($data['date_fin']);
		                        $personne->setPourcent($data['pourcent']);
			                    $personne->setStage($data['Stage']);
							                    
								try 
								{
									$personne->save();
									$this->view->success = "la ressource : ". $data['Nom']." ".$data['Prenom']." a été crée avec succés  !";
								}
								catch (Zend_Db_Exception $e)
								{
									
									if($e->getMessage())
									$this->view->error = "Création de la ressource a échoué !";
									else
									{    // affichage du message de succès 
									    if($personne->getStage() == '1')
										{$this->view->success = "Création du stagiaire : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}
									    else 
										{$this->view->success = "Création de la ressource : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}  
										
										// vider les champs du formulaire
										$form->getElement('Nom')->setValue('');
										$form->getElement('Prenom')->setValue('');
										$form->getElement('fonction')->setValue('');
										$form->getElement('pole')->setValue('');
										$form->getElement('date_entree')->setValue('');
										$form->getElement('date_debut')->setValue('');
										$form->getElement('date_fin')->setValue('');
										$form->getElement('pourcent')->setValue('100');
										$form->getElement('Stage')->setValue('');	
									}
									$form->populate($data);		
								}
								
								
			         }
				    }	  
					else
					{$this->view->error = "Formulaire invalide !";}	
					$form->populate($data);	
				}// fin else 
			}// fin else 
		}// fin if requete post

		$this->view->form = $form;
		
	}// fin action createp

	
	
	
	
	
	
	// CreatepfAction()  :  creer une personne de l'equipe FRONT 
	// MTA : modifié le 18/07/2013 
	public function createpfAction()
	{
		$this->view->title ='<h4>Ajout ressources Françaises</h4></br>';
		$request = $this->getRequest();
		$form    = new Default_Form_PersonneFr();

		//MBA : Peuplé les listes déroulantes é partir de la Base de donnée
		$form->setDbOptions('fonction',new Default_Model_Fonction());
		$form->setDbOptions('pole', new Default_Model_Pole());

		// condition sur le champ modalité pour ne pas affiché aucune modalité (propore au csm)
		$where = array('libelle <> ?' => 'Aucune modalite');
		$form->setDbOptions('modalite', new Default_Model_Modalite(),'getId','getLibelle',$where);

		// condition sur le champ cs pour n'affiché que les entité non cs .
		$where = array('cs = ?' => 0);
		$form->setDbOptions('entite', new Default_Model_Entite(),'getId','getLibelle',$where);

		$personne = new Default_Model_Personne();

		if ($this->getRequest()->isPost()) 
		{
			$data = $request->getPost();
		

			///////////////// récupération de l'url///////////////
			$requete = $this->getRequest();
			if ($requete instanceof Zend_Controller_Request_Http)
			{
				$baseurl = $requete->getBaseUrl();
			}

			if ($form->isValid($request->getPost()))  // formulaire valide 
			{
				echo "</br> Form Valide";
			    $personne = new Default_Model_Personne();
		
				$IsExist = $personne->IsExist($data['Nom'], $data['Prenom']);
				
				if($IsExist>0)  // personne existante 
				{
					$this->view->warning = "Désolé la ressource : ". $data['Nom']." ".$data['Prenom']." existe déjà !";
							
					// vider les champs ci dessous 
					$form->getElement('Nom')->setValue('');
					$form->getElement('Prenom')->setValue('');
					$form->getElement('entite')->setValue('');
					$form->getElement('modalite')->setValue('');
					$form->getElement('pole')->setValue('');
					$form->getElement('date_entree')->setValue('');
					$form->getElement('date_debut')->setValue('');
					$form->getElement('date_fin')->setValue('');
					$form->getElement('pourcent')->setValue('100');
					$form->getElement('Stage')->setValue('');
				}
				else            // personne inexistante         
				{

				    if($data['date_entree'] > $data['date_debut'])  // si on a pas saisi une date_fin
					{
						 $this->view->error = "La date d'entree doit étre inf&eacute;rieure ou &eacute;gale à la date de debut";
				    	 $form->getElement('date_entree')->setView()->addError("");
				     	 $form->getElement('date_debut')->setView()->addError("");
					 }
				     elseif($data['date_fin'] <> '')
				    { 
				     	if ($data['date_debut'] > $data['date_fin']) // si date debut > date fin 
						{
							$this->view->error = "La date de d&eacute;but doit étre inf&eacute;rieure ou &eacute;gale à la date de fin";
				    	 	$form->getElement('date_debut')->setView()->addError("");
			        		$form->getElement('date_fin')->setView()->addError("");
						}
						else
						{
						
							// remplir l'objet personne avec les informations necessaires 
						    $personne->setNom($data['Nom']);
						    $personne->setPrenom($data['Prenom']);
							//centre de service,modalité et entité figés pour le csm
					        $personne->setEntite($data['entite']);
							$personne->setModalite($data['modalite']);
							$personne->setFonction($data['fonction']);  
		                    $personne->setPole($data['pole']);
		                    $personne->setDate_entree($data['date_entree']);
		                    $personne->setDate_debut($data['date_debut']);
		                    $personne->setDate_fin($data['date_fin']);
		                    $personne->setPourcent($data['pourcent']);
		                    $personne->setStage($data['Stage']);
	
							try 
						 	{
								$personne->save();
							    // affichage du message de succès 
								if($personne->getStage() == '1')
								{$this->view->success = "Création du stagiaire : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}
							    else 
							    {$this->view->success = "Création de la ressource : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}  
							    
							    // vider les champs ci dessous 
								$form->getElement('Nom')->setValue('');
								$form->getElement('Prenom')->setValue('');
								$form->getElement('entite')->setValue('');
								$form->getElement('modalite')->setValue('');
								$form->getElement('pole')->setValue('');
								$form->getElement('date_entree')->setValue('');
								$form->getElement('date_debut')->setValue('');
								$form->getElement('date_fin')->setValue('');
								$form->getElement('pourcent')->setValue('100');
								$form->getElement('Stage')->setValue('');
							    
							}
							catch (Zend_Db_Exception $e)
							{
									$this->view->error = "Création de la ressource a échoué !";
									$form->populate($data);
							}
				

						    // affichage du message de succès 
						    if($personne->getStage() == '1')
							{$this->view->success = "Création du stagiaire : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}
						    else 
							{$this->view->success = "Création de la ressource : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}  				
					    }		
				    }				   
					else 
					{
					    // remplir l'objet personne avec les informations necessaires 
					    $personne->setNom($data['Nom']);
					    $personne->setPrenom($data['Prenom']);
						//centre de service,modalité et entité figés pour le csm
				        $personne->setEntite($data['entite']);
						$personne->setModalite($data['modalite']);
						$personne->setFonction($data['fonction']);  
	                    $personne->setPole($data['pole']);
	                    $personne->setDate_entree($data['date_entree']);
	                    $personne->setDate_debut($data['date_debut']);
	                    $personne->setDate_fin($data['date_fin']);
	                    $personne->setPourcent($data['pourcent']);
	                    $personne->setStage($data['Stage']);

						try 
						{
							$personne->save();
						    // affichage du message de succès 
							if($personne->getStage() == '1')
							{$this->view->success = "Création du stagiaire : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}
						    else 
						    {$this->view->success = "Création de la ressource : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}  
						    
						    // vider les champs ci dessous 
							$form->getElement('Nom')->setValue('');
							$form->getElement('Prenom')->setValue('');
							$form->getElement('entite')->setValue('');
							$form->getElement('modalite')->setValue('');
							$form->getElement('pole')->setValue('');
							$form->getElement('date_entree')->setValue('');
							$form->getElement('date_debut')->setValue('');
							$form->getElement('date_fin')->setValue('');
							$form->getElement('pourcent')->setValue('100');
							$form->getElement('Stage')->setValue('');
						    
						}
						catch (Zend_Db_Exception $e)
						{
								$this->view->error = "Création de la ressource a échoué !";
								$form->populate($data);
						}
				
					  }

				}
			}
			else   // formulaire invalide
			{   echo "</br> Form Invalide !!";
                $IsExist = $personne->IsExist($data['Nom'], $data['Prenom']);
				
                if($IsExist>0)    // personne existe 
				{
					$this->view->warning = "Désolé la ressource : ". $data['Nom']." ".$data['Prenom']." existe déjà !";
							
					// vider les champs ci dessous 
					$form->getElement('Nom')->setValue('');
					$form->getElement('Prenom')->setValue('');
					$form->getElement('entite')->setValue('');
					$form->getElement('modalite')->setValue('');
					$form->getElement('pole')->setValue('');
					$form->getElement('date_entree')->setValue('');
					$form->getElement('date_debut')->setValue('');
					$form->getElement('date_fin')->setValue('');
					$form->getElement('pourcent')->setValue('100');
					$form->getElement('Stage')->setValue('');
				}
				else              // personne inexistante 
				{
				
				    if($data['Nom'] === '')  // si on a pas saisi un Nom
					{$this->view->error = "Veuillez saisir un nom !";}
					elseif($data['Prenom'] == '') // si on a pas saisi un Prenom
					{$this->view->error = "Veuillez saisir un prenom !";}
				    elseif($data['entite'] === 'x')  // si on a pas selectionné une entite  id = 'x'
					{$this->view->error = "Veuillez selectionner une entit&eacute; !";}
				    elseif($data['modalite'] === 'x')  // si on a pas selectionné une modalité  id = 'x'
					{$this->view->error = "Veuillez selectionner une modalit&eacute; !";}
					elseif($data['fonction'] === 'x')  // si on a pas selectionné une fonction  id = 'x'
					{$this->view->error = "Veuillez selectionner une fonction !";}
					elseif($data['pole'] === 'x')  // si on a pas selectionné un pole  id = 'x'
					{$this->view->error = "Veuillez selectionner un pole !";}
					elseif($data['date_entree'] === '')  // si on a pas saisi une date_d'entree
					{$this->view->error = "Veuillez saisir une date d'entree !";}
					elseif($data['date_debut'] === '')  // si on a pas saisi une date_debut
					{$this->view->error = "Veuillez saisir une date debut !";}
				    elseif($data['date_entree'] > $data['date_debut'])  // si on a pas saisi une date_fin
					{$this->view->error = "La date d'entree doit étre inf&eacute;rieure ou &eacute;gale à la date de debut";
				     $form->getElement('date_entree')->setView()->addError("");
				     $form->getElement('date_debut')->setView()->addError("");}
				    elseif($form->getElement('pourcent')->hasErrors())                    
			        {$this->view->error = "Le pourcentage doit etre compris entre 0 et 100";}
				     elseif($data['date_fin'] <> '')
				    { 
				    	 if ($data['date_debut'] > $data['date_fin']) // si date debut > date fin 
					 	{	
					 		$this->view->error = "La date de d&eacute;but doit étre inf&eacute;rieure ou &eacute;gale à la date de fin";
				     		$form->getElement('date_debut')->setView()->addError("");
			         		$form->getElement('date_fin')->setView()->addError("");
					 	}
					 	else
					 	{
						 	// remplir l'objet personne avec les informations necessaires 
						    $personne->setNom($data['Nom']);
						    $personne->setPrenom($data['Prenom']);
							//centre de service,modalité et entité figés pour le csm
					        $personne->setEntite($data['entite']);
							$personne->setModalite($data['modalite']);
							$personne->setFonction($data['fonction']);  
		                    $personne->setPole($data['pole']);
		                    $personne->setDate_entree($data['date_entree']);
		                    $personne->setDate_debut($data['date_debut']);
		                    $personne->setDate_fin($data['date_fin']);
		                    $personne->setPourcent($data['pourcent']);
		                    $personne->setStage($data['Stage']);
	
					 	    try
					 	    {
								$personne->save();
							    // affichage du message de succès 
								if($personne->getStage() == '1')
								{$this->view->success = "Création du stagiaire : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}
							    else 
							    {$this->view->success = "Création de la ressource : ".$data['Nom']." ".$data['Prenom']. " avec succès !";}  
							    
							    // vider les champs ci dessous 
								$form->getElement('Nom')->setValue('');
								$form->getElement('Prenom')->setValue('');
								$form->getElement('entite')->setValue('');
								$form->getElement('modalite')->setValue('');
								$form->getElement('pole')->setValue('');
								$form->getElement('date_entree')->setValue('');
								$form->getElement('date_debut')->setValue('');
								$form->getElement('date_fin')->setValue('');
								$form->getElement('pourcent')->setValue('100');
								$form->getElement('Stage')->setValue('');
							    
							}
							catch (Zend_Db_Exception $e)
							{
									$this->view->error = "Création de la ressource a échoué !";
									$form->populate($data);
							}
					 	
					 	}
				    }	
			        else
					{$this->view->error = "Formulaire invalide !";}	
					$form->populate($data);	
				    
			     }// fin else	     
	       }// fin else
     }// fin if requete post
	$this->_helper->viewRenderer('createp');
	$this->view->form = $form;

}// fin action createpf



	// editAction()  :  modifier une personne de l'equipe FRONT ou CSM 
	// MTA : modifié le 23/07/2013 

	public function editAction()
	{   
		$id = $this->_getParam('id');
		$personne = new Default_Model_Personne();
		$personne = $personne->find($id);

	    /* Génération de formulaire ressource Marocaine */
        
		//******// ENTITE MAROCAINE //******//
		if( $personne->getEntite()->getCs() == "1")    //*******************************************************************************************
		{   
			$form    = new Default_Form_PersonneMa();
			//MBA : Peuplé les listes déroulantes é partir de la Base de donnée
			$form->setDbOptions('fonction',new Default_Model_Fonction());
			$form->setDbOptions('pole', new Default_Model_Pole());
			$this->view->title ='Modification ressource Marocaine';			

            // sauvegarder les données a modifiées 
			$PreData['Nom'] = $personne->getNom();
			$PreData['Prenom'] = $personne->getPrenom();
			$PreData['fonction']=  intval($personne->getFonction()->getId());
			$PreData['pole'] = intval($personne->getPole()->getId());
			$PreData['date_debut'] = date('Y-m-d',strtotime($personne->getDate_debut()));
			$PreData['date_entree'] = date('Y-m-d',strtotime($personne->getDate_entree()));
			$PreData['date_fin'] = date('Y-m-d',strtotime($personne->getDate_fin()));
			$PreData['pourcent'] = intval($personne->getPourcent());
			$PreData['Stage'] = $personne->getStage();

			// si la date retourné est 1970-01-01 on vide le champs 
			if($PreData['date_fin'] == '1970-01-01')
			$PreData['date_fin'] = '';
						
			// remplir le formulaire avec ces données PreData
			$form->populate($PreData);

			$this->_helper->viewRenderer('createp');
			$this->view->form = $form;
			
			// renommer le boutton valider => Modifier
		    $form->getElement('creer')->setLabel('Modifier');  
			
			if($this->getRequest()->isPost())
			{  
				//récupération des données envoyées par le formulaire
				$data = $this->getRequest()->getPost();
            
				///////////////// récupération de l'url///////////////
			    $requete = $this->getRequest();
			    if ($requete instanceof Zend_Controller_Request_Http)
			    {$baseurl = $requete->getBaseUrl();}
                //////////////////////////////////////////////////////
			  
				//vérifie que les données répondent aux conditions des validateurs
				if($form->isValid($data))
				{   
				     if($data['Nom'] === '')  // si on a pas saisi un Nom
					 {$this->view->error = "Veuillez saisir un nom !";}
					 elseif($data['Prenom'] == '') // si on a pas saisi un Prenom
					 {$this->view->error = "Veuillez saisir un prenom !";}
					 elseif($data['fonction'] === 'x')  // si on a pas selectionné une fonction  id = 'x'
					 {$this->view->error = "Veuillez selectionner une fonction !";}
					 elseif($data['pole'] === 'x')  // si on a pas selectionné un pole  id = 'x'
					 {$this->view->error = "Veuillez selectionner un pole !";}
					 elseif($data['date_entree'] === '')  // si on a pas saisi une date_d'entree
					 {$this->view->error = "Veuillez saisir une date d'entree !";}
					 elseif($data['date_debut'] === '')  // si on a pas saisi une date_debut
					 {$this->view->error = "Veuillez saisir une date debut !";}
				     elseif($data['date_entree'] > $data['date_debut'])  // si on a pas saisi une date_fin
					 {$this->view->error = "La date d'entree doit étre inf&eacute;rieure ou &eacute;gale à la date de debut";
				     $form->getElement('date_entree')->setView()->addError("");
				     $form->getElement('date_debut')->setView()->addError("");}
				     elseif($form->getElement('pourcent')->hasErrors())                    
			        {$this->view->error = "Le pourcentage doit etre compris entre 0 et 100";}
				     elseif($data['date_fin'] <> '')
				     { 
				        if ($data['date_debut'] > $data['date_fin']) // si date debut > date fin 
					    {
					    	$this->view->error = "La date de d&eacute;but doit étre inf&eacute;rieure ou &eacute;gale à la date de fin";
				        	$form->getElement('date_debut')->setView()->addError("");
			            	$form->getElement('date_fin')->setView()->addError("");
					    }
					    else
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
									$this->view->warning = "Aucun champ n'a &eacute;t&eacute; modifi&eacute; !";
								}
								else
								{		
										$data['id'] = $personne->getId();
										$data['date_entree'] = date('Y-m-d',strtotime($data['date_entree']));
										$data['date_debut'] = date('Y-m-d',strtotime($data['date_debut']));
										$data['date_fin'] = date('Y-m-d',strtotime($data['date_fin']));
										$data['entite'] = $personne->getEntite();
										$data['modalite'] = $personne->getModalite();
				                        var_dump($data);
										$personne = new Default_Model_Personne($data);
				           
										try 
										{	
											$personne->save();
										}
										catch (Zend_Db_Exception $e)
										{
											if($form->getElement('fonction')->getValue() == 'x')
											$form->getElement('fonction')->addError("erreur");
				
											if($form->getElement('pole')->getValue() == 'x')
											$form->getElement('pole')->addError("erreur");
											$this->view->error = "Erreur d'insertion : ".$e->getMessage();
											$form->populate($data);
										}
				
										$this->view->success = "Les informations de la ressource : ".$data['Nom']." ".$data['Prenom']. " ont été modifiées avec succès !";
										header("Refresh:1.5;URL=".$baseurl."/personne/index");              // URL dynamique	
				
							    } // fin else
						   } // fin else
				      }// fin elseif
					  else 
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
										$this->view->warning = "Aucun champ n'a &eacute;t&eacute; modifi&eacute; !";
									}
									else
									{		
											$data['id'] = $personne->getId();
											$data['date_entree'] = date('Y-m-d',strtotime($data['date_entree']));
											$data['date_debut'] = date('Y-m-d',strtotime($data['date_debut']));
											$data['date_fin'] = date('Y-m-d',strtotime($data['date_fin']));
											$data['entite'] = $personne->getEntite();
											$data['modalite'] = $personne->getModalite();
					
											$personne = new Default_Model_Personne($data);
					                      
											try 
											{	
												$personne->save();
											}
											catch (Zend_Db_Exception $e)
											{
												if($form->getElement('fonction')->getValue() == 'x')
												$form->getElement('fonction')->addError("erreur");
					
												if($form->getElement('pole')->getValue() == 'x')
												$form->getElement('pole')->addError("erreur");
												$this->view->error = "Erreur d'insertion : ".$e->getMessage();
												$form->populate($data);
					
											}
					
											$this->view->success = "Les informations de la ressource : ".$data['Nom']." ".$data['Prenom']. " ont été modifiées avec succès !";
											header("Refresh:1.5;URL=".$baseurl."/personne/index");              // URL dynamique	
					
								     } // fin else
						} // fin else 
				} // fin forme valide 
				else     // Formulaire invalide                                          
				{     		    

				    if($data['Nom'] === '')  // si on a pas saisi un Nom
					{$this->view->error = "Veuillez saisir un nom !";}
					elseif($data['Prenom'] == '') // si on a pas saisi un Prenom
					{$this->view->error = "Veuillez saisir un prenom !";}
					elseif($data['fonction'] === 'x')  // si on a pas selectionné une fonction  id = 'x'
					{$this->view->error = "Veuillez selectionner une fonction !";}
					elseif($data['pole'] === 'x')  // si on a pas selectionné un pole  id = 'x'
					{$this->view->error = "Veuillez selectionner un pole !";}
					elseif($data['date_entree'] === '')  // si on a pas saisi une date_d'entree
					{$this->view->error = "Veuillez saisir une date d'entree !";}
					elseif($data['date_debut'] === '')  // si on a pas saisi une date_debut
					{$this->view->error = "Veuillez saisir une date debut !";}
				    elseif($data['date_entree'] > $data['date_debut'])  // si on a pas saisi une date_fin
					{$this->view->error = "La date d'entree doit étre inf&eacute;rieure ou &eacute;gale à la date de debut";
				     $form->getElement('date_entree')->setView()->addError("");
				     $form->getElement('date_debut')->setView()->addError("");}
		    		elseif($form->getElement('pourcent')->hasErrors())                    
			        {$this->view->error = "Le pourcentage doit etre compris entre 0 et 100";}
				     elseif($data['date_fin'] <> '')
				    { 
					      if ($data['date_debut'] > $data['date_fin']) // si date debut > date fin 
						  {
						  	$this->view->error = "La date de d&eacute;but doit étre inf&eacute;rieure ou &eacute;gale à la date de fin";
					        $form->getElement('date_debut')->setView()->addError("");
				            $form->getElement('date_fin')->setView()->addError("");
						  }
				   	      else
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
									$this->view->warning = "Aucun champ n'a &eacute;t&eacute; modifi&eacute; !";
								}
								else
								{		
										$data['id'] = $personne->getId();
										$data['date_entree'] = date('Y-m-d',strtotime($data['date_entree']));
										$data['date_debut'] = date('Y-m-d',strtotime($data['date_debut']));
										$data['date_fin'] = date('Y-m-d',strtotime($data['date_fin']));
										$data['entite'] = $personne->getEntite();
										$data['modalite'] = $personne->getModalite();
				
										$personne = new Default_Model_Personne($data);
				           
										try 
										{	
											$personne->save();
										}
										catch (Zend_Db_Exception $e)
										{
											if($form->getElement('fonction')->getValue() == 'x')
											$form->getElement('fonction')->addError("erreur");
				
											if($form->getElement('pole')->getValue() == 'x')
											$form->getElement('pole')->addError("erreur");
											$this->view->error = "Erreur d'insertion : ".$e->getMessage();
											$form->populate($data);
				
										}
				
										$this->view->success = "Les informations de la ressource : ".$data['Nom']." ".$data['Prenom']. " ont été modifiées avec succès !";
										header("Refresh:1.5;URL=".$baseurl."/personne/index");              // URL dynamique	
				
								} // fin else
						  } // fin else
					  }	// fin elseif 		    		
					else // sinon 
					{     	
					 $this->view->error = "Formulaire invalide !";}
					 $form->populate($data);
			     	}
			     	
				$this->_helper->viewRenderer('createp');
				$this->view->form = $form;

			}// fin formulaire envoyé
		} // fin if entité marocaine
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		 //******************//  ENTITE FRANCAISE  //******************//
		elseif( $personne->getEntite()->getCs() == "0")    
		{
			$form    = new Default_Form_PersonneFr();

			//MBA : Peuplé les listes déroulantes é partir de la Base de donnée
			$form->setDbOptions('fonction',new Default_Model_Fonction());
			$form->setDbOptions('pole', new Default_Model_Pole());

			// condition sur le champ modalité pour ne pas affiché aucune modalité (propore au csm)
			$where = array('libelle <> ?' => 'Aucune modalite');
			$form->setDbOptions('modalite', new Default_Model_Modalite(),'getId','getLibelle',$where);

			// condition sur le champ cs pour n'affiché que les entité non cs .
			$where = array('cs = ?' => 0);
			$form->setDbOptions('entite', new Default_Model_Entite(),'getId','getLibelle',$where);

			$this->view->title ='Modification ressource Française';

			$PreData['Nom'] = $personne->getNom();
			$PreData['Prenom'] = $personne->getPrenom();
			$PreData['entite'] = intval($personne->getEntite()->getId());
			$PreData['modalite'] = intval($personne->getModalite()->getId());
			$PreData['fonction']= intval($personne->getFonction()->getId());
			$PreData['pole'] = intval($personne->getPole()->getId());
			$PreData['date_entree'] = date('Y-m-d',strtotime($personne->getDate_entree()));
			$PreData['date_debut'] = date('Y-m-d',strtotime($personne->getDate_debut()));
			$PreData['date_fin'] = date('Y-m-d',strtotime($personne->getDate_fin()));
			$PreData['pourcent'] = intval($personne->getPourcent());
			$PreData['Stage'] = $personne->getStage();
     
			// si la date retourné est 1970-01-01 on vide le champs 
			if($PreData['date_fin'] == '1970-01-01')
			$PreData['date_fin'] = '';
			
		    // remplir le formulaire avec ces données PreData
			$form->populate($PreData);
			
			$this->_helper->viewRenderer('createp');
			$this->view->form = $form;
			
			// renommer le boutton valider => Modifier
		    $form->getElement('creer')->setLabel('Modifier');  
			
		    if($this->getRequest()->isPost())
			{
				//récupération des données envoyées par le formulaire
				$data = $this->getRequest()->getPost();

				// récupération de l'url
			    $requete = $this->getRequest();
			    if ($requete instanceof Zend_Controller_Request_Http)
			    {$baseurl = $requete->getBaseUrl();}
               
                
			    //vérifie que les données répondent aux conditions des validateurs
				if($form->isValid($data))   // formulaire valide 
				{   
			   
				      if($data['Nom'] === '')  // si on a pas saisi un Nom
					 {$this->view->error = "Veuillez saisir un nom !";}
					 elseif($data['Prenom'] == '') // si on a pas saisi un Prenom
					 {$this->view->error = "Veuillez saisir un prenom !";}
					 elseif($data['entite'] === 'x')  // si on a pas selectionné une entite  id = 'x'
					 {$this->view->error = "Veuillez selectionner une entit&eacute; !";}
				     elseif($data['modalite'] === 'x')  // si on a pas selectionné une modalité  id = 'x'
					 {$this->view->error = "Veuillez selectionner une modalit&eacute; !";}
					 elseif($data['fonction'] === 'x')  // si on a pas selectionné une fonction  id = 'x'
					 {$this->view->error = "Veuillez selectionner une fonction !";}
					 elseif($data['pole'] === 'x')  // si on a pas selectionné un pole  id = 'x'
					 {$this->view->error = "Veuillez selectionner un pole !";}
					 elseif($data['date_entree'] === '')  // si on a pas saisi une date_d'entree
					 {$this->view->error = "Veuillez saisir une date d'entree !";}
					 elseif($data['date_debut'] === '')  // si on a pas saisi une date_debut
					 {$this->view->error = "Veuillez saisir une date debut !";}
					 elseif($data['date_entree'] > $data['date_debut'])  // si on a pas saisi une date_fin
					 {$this->view->error = "La date d'entree doit étre inf&eacute;rieure ou &eacute;gale à la date de debut";
				     $form->getElement('date_entree')->setView()->addError("");
				     $form->getElement('date_debut')->setView()->addError("");}
				     elseif($form->getElement('pourcent')->hasErrors())                    
			         {$this->view->error = "Le pourcentage doit etre compris entre 0 et 100";}
			         elseif($data['date_fin'] <> '')
				     {
				     	if ($data['date_debut'] > $data['date_fin']) // si date debut > date fin 
					    {
					    	$this->view->error = "La date de d&eacute;but doit étre inf&eacute;rieure ou &eacute;gale à la date de fin";
				        	$form->getElement('date_debut')->setView()->addError("");
			            	$form->getElement('date_fin')->setView()->addError("");
					    }
					    else
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
									$this->view->warning = "Aucun champ n'a &eacute;t&eacute; modifi&eacute; !";
								}
								else
								{	 
									    $data['id'] = $personne->getId();
										$data['date_entree'] = date('Y-m-d',strtotime($data['date_entree']));
										$data['date_debut'] = date('Y-m-d',strtotime($data['date_debut']));
										$data['date_fin'] = date('Y-m-d',strtotime($data['date_fin']));
										$data['entite'] = $personne->getEntite();
										$data['modalite'] = $personne->getModalite();
				             
										$personne = new Default_Model_Personne($data);
				           
										try 
										{	
											$personne->save();
										}
										catch (Zend_Db_Exception $e)
										{
											if($form->getElement('fonction')->getValue() == 'x')
											$form->getElement('fonction')->addError("erreur");
				
											if($form->getElement('pole')->getValue() == 'x')
											$form->getElement('pole')->addError("erreur");
											$this->view->error = "Erreur d'insertion : ".$e->getMessage();
											$form->populate($data);
										}
				
										$this->view->success = "Les informations de la ressource : ".$data['Nom']." ".$data['Prenom']. " ont été modifiées avec succès !";
										header("Refresh:1.5;URL=".$baseurl."/personne/index");   
					              
								}// Fin else 
					      }// Fin else 	
				     }// Fin elseif 
				     else 
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
									$this->view->warning = "Aucun champ n'a &eacute;t&eacute; modifi&eacute; !";
								}
								else
								{	 
									    $data['id'] = $personne->getId();
										$data['date_entree'] = date('Y-m-d',strtotime($data['date_entree']));
										$data['date_debut'] = date('Y-m-d',strtotime($data['date_debut']));
										$data['date_fin'] = date('Y-m-d',strtotime($data['date_fin']));
										$data['entite'] = $personne->getEntite();
										$data['modalite'] = $personne->getModalite();
				                          echo "</br> data 2: ";
										var_dump($data);
										$personne = new Default_Model_Personne($data);
				           
										try 
										{	
											$personne->save();
										}
										catch (Zend_Db_Exception $e)
										{
											if($form->getElement('fonction')->getValue() == 'x')
											$form->getElement('fonction')->addError("erreur");
				
											if($form->getElement('pole')->getValue() == 'x')
											$form->getElement('pole')->addError("erreur");
											$this->view->error = "Erreur d'insertion : ".$e->getMessage();
											$form->populate($data);
										}
				
										$this->view->success = "Les informations de la ressource : ".$data['Nom']." ".$data['Prenom']. " ont été modifiées avec succès !";
										header("Refresh:1.5;URL=".$baseurl."/personne/index");   
															              
								}// Fin else 		     
				     }// Fin else     
				}
				else                        // formulaire invalide 
				{
				    echo "je suis la";
				      if($data['Nom'] === '')  // si on a pas saisi un Nom
					 {$this->view->error = "Veuillez saisir un nom !";}
					 elseif($data['Prenom'] == '') // si on a pas saisi un Prenom
					 {$this->view->error = "Veuillez saisir un prenom !";}
					 elseif($data['entite'] === 'x')  // si on a pas selectionné une entite  id = 'x'
					 {$this->view->error = "Veuillez selectionner une entit&eacute; !";}
				     elseif($data['modalite'] === 'x')  // si on a pas selectionné une modalité  id = 'x'
					 {$this->view->error = "Veuillez selectionner une modalit&eacute; !";}
					 elseif($data['fonction'] === 'x')  // si on a pas selectionné une fonction  id = 'x'
					 {$this->view->error = "Veuillez selectionner une fonction !";}
					 elseif($data['pole'] === 'x')  // si on a pas selectionné un pole  id = 'x'
					 {$this->view->error = "Veuillez selectionner un pole !";}
					 elseif($data['date_entree'] === '')  // si on a pas saisi une date_d'entree
					 {$this->view->error = "Veuillez saisir une date d'entree !";}
					 elseif($data['date_debut'] === '')  // si on a pas saisi une date_debut
					 {$this->view->error = "Veuillez saisir une date debut !";}
					 elseif($data['date_entree'] > $data['date_debut'])  // si on a pas saisi une date_fin
					 {$this->view->error = "La date d'entree doit étre inf&eacute;rieure ou &eacute;gale à la date de debut";
				     $form->getElement('date_entree')->setView()->addError("");
				     $form->getElement('date_debut')->setView()->addError("");}
				     elseif($form->getElement('pourcent')->hasErrors())                    
			         {$this->view->error = "Le pourcentage doit etre compris entre 0 et 100";}
			         elseif($data['date_fin'] <> '')
				     {
				     	if ($data['date_debut'] > $data['date_fin']) // si date debut > date fin 
					    {
					    	$this->view->error = "La date de d&eacute;but doit étre inf&eacute;rieure ou &eacute;gale à la date de fin";
				        	$form->getElement('date_debut')->setView()->addError("");
			            	$form->getElement('date_fin')->setView()->addError("");
					    }
					    else
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
									$this->view->warning = "Aucun champ n'a &eacute;t&eacute; modifi&eacute; !";
								}
								else
								{	 
									    $data['id'] = $personne->getId();
										$data['date_entree'] = date('Y-m-d',strtotime($data['date_entree']));
										$data['date_debut'] = date('Y-m-d',strtotime($data['date_debut']));
										$data['date_fin'] = date('Y-m-d',strtotime($data['date_fin']));
										$data['entite'] = $personne->getEntite();
										$data['modalite'] = $personne->getModalite();
				             
										$personne = new Default_Model_Personne($data);
				           
										try 
										{	
											$personne->save();
										}
										catch (Zend_Db_Exception $e)
										{
											if($form->getElement('fonction')->getValue() == 'x')
											$form->getElement('fonction')->addError("erreur");
				
											if($form->getElement('pole')->getValue() == 'x')
											$form->getElement('pole')->addError("erreur");
											$this->view->error = "Erreur d'insertion : ".$e->getMessage();
											$form->populate($data);
										}
				
										$this->view->success = "Les informations de la ressource : ".$data['Nom']." ".$data['Prenom']. " ont été modifiées avec succès !";
										header("Refresh:1.5;URL=".$baseurl."/personne/index");   
					              
								}// Fin else 	     
					      }// Fin else 
				     }// Fin elseif 

				     else    // autre cas que les if et else 
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
									$this->view->warning = "Aucun champ n'a &eacute;t&eacute; modifi&eacute; !";
								}
								else
								{	 
									    $data['id'] = $personne->getId();
										$data['date_entree'] = date('Y-m-d',strtotime($data['date_entree']));
										$data['date_debut'] = date('Y-m-d',strtotime($data['date_debut']));
										$data['date_fin'] = date('Y-m-d',strtotime($data['date_fin']));
										$data['entite'] = $personne->getEntite();
										$data['modalite'] = $personne->getModalite();
				                         // echo "</br> data 2: ";
										//var_dump($data);
										$personne = new Default_Model_Personne($data);
				           
										try 
										{	
											$personne->save();
										}
										catch (Zend_Db_Exception $e)
										{
											if($form->getElement('fonction')->getValue() == 'x')
											$form->getElement('fonction')->addError("erreur");
				
											if($form->getElement('pole')->getValue() == 'x')
											$form->getElement('pole')->addError("erreur");
											$this->view->error = "Erreur d'insertion : ".$e->getMessage();
											$form->populate($data);
										}
				
										$this->view->success = "Les informations de la ressource : ".$data['Nom']." ".$data['Prenom']. " ont été modifiées avec succès !";
										header("Refresh:1.5;URL=".$baseurl."/personne/index");   
					              
								}// Fin else 
				     }// Fin else 
				     
				}
			   			    
			}
			
		}// Fin Modifier Front 
		
}// Fin Action modifier 
	
	
	
	
	
	
	
	
	
	
	public function deleteAction()
	{

		//é la reception d'une requete ajax

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