<?php
class CongeController extends Zend_Controller_Action
{
	  public function preDispatch() 
	  {
	    	    $doctypeHelper = new Zend_View_Helper_Doctype();
	            $doctypeHelper->doctype('HTML5');
	    		$this->_helper->layout->setLayout('mylayout');      
	  }
	
	//::::::::::::// ACTION INDEX //::::::::::::://
	public function indexAction()
	{
		//création d'un d'une instance Default_Model_Users
		$conge = new Default_Model_Conge();

		//$this->view permet d'accéder à la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		//la valeur correspond à un tableau d'objets de type Default_Model_Users récupérés par la méthode fetchAll()
		//$this->view->usersArray = $users->fetchAll();

		//création de notre objet Paginator avec comme paramétre la méthode
		//récupérant toutes les entrées dans notre base de données
		$paginator = Zend_Paginator::factory($conge->fetchAll($str =array()));
		//indique le nombre déléments à afficher par page
		$paginator->setItemCountPerPage(20);
		//récupére le numéro de la page à afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'accéder à la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		
		
		$this->view->congeArray = $paginator;
		
	}
    
	//:::::::::::::// ACTION CREER //::::::::::::://
	//MTA : OK 
	public function creerAction()
	{  
		//création du fomulaire
		$form = new Default_Form_Conge();
		//indique l'action qui va traiter le formulaire
		//$form->setAction($this->view->url(array('controller' => 'conge', 'action' => 'creer'), 'default', true));

        //assigne le formulaire é la vue
		$this->view->form = $form;
		$this->view->title = "Deposer un conge";
		
	     
								
		// remplir la list Année de reference par Annee , Annee + 1 , Annee - 1
	
	    $date_tmp = getdate(); // recuperer date system
	    $annee = (string) $date_tmp['year']; // extraire l'année 
	    // replir la list par annee-1 , annee , annee+1
	    $list_annee = array((string)$annee-1=>(string)$annee-1,$annee=>$annee,(string)$annee+1=>(string)$annee+1);
		$anneeref = $form->getElement('AnneeRef');
		$anneeref->setMultiOptions($list_annee);
		
								
					
					

		// remplir le select par les ressources front 
        $where = array('id_entite <> ?' => '2');
	    $form->setDbOptions('Ressource',new Default_Model_Personne(),'getId','getNomPrenom',$where);
		
	    
	     // remplir le type de conge  
	     $form->setDbOptions('TypeConge',new Default_Model_TypeConge(),'getId','getCode');

		 $this->_helper->viewRenderer('creer');  // creer proposition
	   
	     $data = array();   // tableau temporaire 

	    // requete POST 
		if($this->_request->isPost())   
		{
			
			// récupération des données envoyés par le formulaire
			$data = $this->_request->getPost();
            
			$personne = new Default_Model_Personne();
			$id_personne = $data['Ressource']; // id personne 

	        $pers = $personne->find($id_personne);   // retourne l'objet personne ayant l'id "$id_personne"
			
			
			//Vérifie si les données répondent aux conditions de validateurs 
			
			if($form->isValid($data)) // formulaire valide 
			{
	
	            if($data['Ressource'] === 'x')     // si on a pas selectionné une ressource  id = 'x'
				{
				   $this->view->error = "Veuillez selectionner une ressource !";
				}
				elseif($data['TypeConge'] === 'x')     // si on a pas selectionné un type de conge
				{
				   $this->view->error = "Veuillez selectionner un Type de conge !";
				}
				elseif($data['AnneeRef'] == null)
				{
				   $this->view->error = "l'annee de reference doit être non nulle !";
				}
				elseif ($data['Debut'] > $data['Fin'])
				{	
					$this->view->error = "La date de début doit être inférieure ou égale à la date de fin";	//création et initialisation d'un objet Default_Model_Users
				}
				else 
				{   
				    //qui sera enregistré dans la base de données
					$conge = new Default_Model_Conge();
					$conge->setId_personne($data['Ressource']);
					$conge->setId_type_conge($data['TypeConge']);
					$conge->setDate_debut($data['Debut']);
					$conge->setDate_fin($data['Fin']);
					$conge->setMi_debut_journee($data['DebutMidi']);
					$conge->setMi_fin_journee($data['FinMidi']);
					$conge->setNombre_jours();
					$conge->setAnnee_reference($data['AnneeRef']);
					$conge->setFerme($data['Ferme']);

					try 
					{
						$conge->save();
						
						$this->view->success = "Création du congé pour Mr/Mme : ".$pers->getNomPrenom();	
						
						// vider le formulaire pour crée un autre congé
						$form->getElement('Ressource')->setValue('');
						$form->getElement('TypeConge')->setValue('');
						$form->getElement('Debut')->setValue('');
						$form->getElement('Fin')->setValue('');
						$form->getElement('DebutMidi')->setValue('');
						$form->getElement('FinMidi')->setValue('');
						$form->getElement('AnneeRef')->setValue('');
						$form->getElement('Ferme')->setValue('');
						
						
						
						//redirection
                       // $this->_helper->Redirector('afficher');  // afficher conge
				
					} 
					catch (Exception $e) 
					{
						//$this->view->error = $e->getMessage();
						 $this->view->error = "Création du congé pour Mr/Mme : ".$pers->getNomPrenom()." à échoué !";	
					}
					
					
				}	


	      }
	      else  // forme invalide 
	      {
	      
	            if($data['Ressource'] === 'x')     // si on a pas selectionné une ressource  id = 'x'
				{
				   $this->view->error = "Veuillez selectionner une ressource !";
				}    
			    elseif($data['Debut'] == null )
			    {
			        $this->view->error = "Veuillez saisir une date de debut !";
			 
			    }
				elseif($data['TypeConge'] === 'x')     // si on a pas selectionné un type de conge
				{
				   $this->view->error = "Veuillez selectionner un type de conge !";
				}
			    elseif($data['Fin'] == null )
					  	 	
			    	$this->view->error = "Veuillez saisir une date de fin !";

			    elseif ($data['Debut'] > $data['Fin'])
				{	
					$this->view->error = "La date de début doit être inférieure ou égale à la date de fin";	//création et initialisation d'un objet Default_Model_Users
				}
				
			    elseif($data['AnneeRef'] == null)
				{
				   $this->view->error = "l'annee de reference doit être non nulle !";
				}

	            $form->populate($data);
	      
	      }
	      
		}
	}
	
	
	//:::::::::::::// ACTION MODIFIER //::::::::::::://
	//MTA  : OK
	public function modifierAction()
	{
		$this->_helper->viewRenderer('creer'); // creer conge
		//création du fomulaire
		$form = new Default_Form_Conge();
		//indique l'action qui va traiter le formulaire
		$form->Valider->setLabel('Modifier');
		//assigne le formulaire à la vue
		$this->view->form = $form;
		$this->view->title = "Modifier Conge"; //MTA

  		 $conge = new Default_Model_Conge();
         $personne = new Default_Model_Personne();
		
         //récupération des données envoyées par le formulaire
		 $data_id =  $this->getRequest()->getParams();

       
          
        // recupere l'id personne qui a posé le conge
         $cong = $conge->find($data_id['id']);
	     $id_personne =  $cong->getId_personne();  // id personne 

	     $pers = $personne->find($id_personne);   // retourne l'objet personne ayant l'id "$id_personne"
	          
 		 $id_type_conge = $cong->getId_type_conge(); // id_type_conge
         
 		 
 		 // remplir la list Année de reference par Annee , Annee + 1 , Annee - 1
 		 $date_debut = $cong->getDate_debut();  // recuperer date debut 
	     $annee = substr($date_debut, 0,4);     // extraire l'année 

 		 $list_annee = array((string)$annee-1=>(string)$annee-1,$annee=>$annee,(string)$annee+1=>(string)$annee+1);
 		 $anneeref = $form->getElement('AnneeRef');
 		
 		 $anneeref->setMultiOptions($list_annee);
 
 		 
 		 // stocker les anciennes valeurs du formulaire 
		 $PreData['Debut']=  $conge->getDate_debut();
		 $PreData['DebutMidi'] = $conge->getMi_debut_journee();
		 $PreData['Fin'] = $conge->getDate_fin();
		 $PreData['FinMidi'] = $conge->getMi_fin_journee();    
		 $PreData['AnneeRef'] = $conge->getAnnee_reference();
		 $PreData['TypeConge'] = $conge->getId_type_conge();
		 $PreData['Ferme'] = $conge->getFerme(); 

		 
		 // stocker les nouvelles valeurs du formulaire 
	     $data = array();
	     $data['_date_debut'] = $form->getElement('Debut')->getValue();
	     $data['_mi_debut_journee'] = $form->getElement('DebutMidi')->getValue();
	     $data['_date_fin'] = $form->getElement('Fin')->getValue();
	     $data['_mi_fin_journee'] = $form->getElement('FinMidi')->getValue();
	     $data['_annee_reference'] = $form->getElement('AnneeRef')->getValue();
	     $data['_id_type_conge'] = $form->getElement('TypeConge')->getValue();
	     $data['_ferme'] = $form->getElement('Ferme')->getValue();
		 
	      // remplie le select avec les types de conge qui existent
		  $form->setDbOptions('TypeConge',new Default_Model_TypeConge(),'getId','getCode');
	     
	     
		 // remplie le select avec le  nom et prenom de la personne ayant id personne  
	     $where = array('id = ?' => $id_personne);
		 $form->setDbOptions('Ressource',new Default_Model_Personne(),'getId','getNomPrenom',$where);

		 //placeholder modifié 
		 $form->getElement('Debut')->setAttrib('placeholder', 'Saisissez une date debut ...');
		 $form->getElement('Fin')->setAttrib('placeholder', 'Saisissez une date fin ...'); 
		 		 
		 // remplir le formulaire par les données recupérer 
		 $form->getElement('Ressource')->setValue($id_personne);
		 $form->getElement('Debut')->setValue($PreData['Debut']);
		 $form->getElement('Fin')->setValue($PreData['Fin']);
		 $form->getElement('DebutMidi')->setValue($PreData['DebutMidi']);
		 $form->getElement('FinMidi')->setValue($PreData['FinMidi']);
		 $form->getElement('AnneeRef')->setValue($PreData['AnneeRef']);
		 $form->getElement('TypeConge')->setValue($id_type_conge); 
		 $form->getElement('Ferme')->setValue($PreData['Ferme']);


	  	  //si la page est POSTée = formulaire envoyé
		  if($this->getRequest()->isPost())
		  { 
		 		//récupération des données envoyées par le formulaire
		  	    $data = $this->_request->getPost();

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
					 
					 if($data['Ressource'] === 'x')     // si on a pas selectionné une ressource  id = 'x'
					{
						$this->view->error = "Veuillez selectionner une ressource !";
					}
					elseif($data['TypeConge'] === 'x')     // si on a pas selectionné un type conge  id = 'x'
					{
						$this->view->error = "Veuillez selectionner un type congé !";
					}
					elseif($i == 1)  // pas de modification effectué 
				    {
				        $this->view->warning = "Aucun champs n'a été modifié !";
				    }
					elseif ($data['Debut'] > $data['Fin'])  // date debut > date fin 
						$this->view->error = "La date de début doit être inférieure ou égale à la date de fin";
				    else 
			        {       
			
				        // remplir l'objet conge par les valeurs modifiées     
			            $conge ->setId($data_id['id']);
			            $conge->setId_proposition($cong->getId_proposition());
			            $conge->setId_personne($id_personne);
			            $conge->setDate_debut($data['Debut']);
					    $conge->setDate_fin($data['Fin']);
					    $conge->setMi_debut_journee($data['DebutMidi']);
					    $conge->setMi_fin_journee($data['FinMidi']);
					    $conge->setAnnee_reference($data['AnneeRef']);
					    $conge->setNombre_jours();
					    $conge->setId_type_conge($data['TypeConge']);
					    $conge->setFerme($data['Ferme']);

					    $this->view->title = "Modification du congé";
					   // $this->view->success = "Modification du congé de Mr/Mme : ".$pers->getNomPrenom();	
						try 
					 	{
								 $conge->save();
								
								 //redirection
                    	   		 $this->_helper->Redirector('afficher');  // afficher conge
						} 
						catch (Exception $e) 
						{
								$this->view->error = $e->getMessage();
						}
				
			        }
			}
		}
    }

	
	//:::::::::::::// ACTION SUPPRIMER //::::::::::::://
	// MTA : OK 

	public function supprimerAction()
	{
		
		
		 if($this->getRequest()->isXmlHttpRequest())
		 {     
		 	
		 	 //récupére les paramétres de la requéte Ajax 
		 	$data = $this->getRequest()->getPost();
			$id = $data['id'];   
		        
			//création du modéle pour la suppression
			$conge = new Default_Model_Conge();

			try 
			{     //appel de la fcontion de suppression avec en argument,
				  //la clause where qui sera appliquée
				  $result = $conge->delete("id=$id");   
			}
			catch (Zend_Db_Exception $e)
			{
					// en cas d'erreur envoi de reponse avec code erreur [500]
					$content = array("status"=>"500","result"=> $result);
	       			$this->view->error= "Erreur";
	       		    $this->_helper->json($content);
	       				      
	       			echo $content;
			}
				        	 //en cas de succès envoie de reponse avec code succès [200]
					         $this->view->success = "Le congé a bien été supprimer !";
				        	 $content = array("status"=>"200","result"=> "1");
	       					
	                         // envoi de reponse en format Json
	       		       		 $this->_helper->json($content);

				//redirection
				$this->_helper->viewRenderer('afficher');

		}
		
	}

	public function afficherAction()
	{
		$conge = new Default_Model_Conge();
		$paginator = Zend_Paginator::factory($conge->fetchAll($str = array()));
		$paginator->setItemCountPerPage(10);
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));
		$this->view->congeArray = $paginator;
     		
	}	
}
