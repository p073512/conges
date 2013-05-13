
<?php
class PropositionController extends Zend_Controller_Action
{   
     
	
     public function preDispatch() 
    {
    	    $doctypeHelper = new Zend_View_Helper_Doctype();
            $doctypeHelper->doctype('HTML5');
    		$this->_helper->layout->setLayout('mylayout');
    	
	}
 
	//:::::::::::::// ACTION INDEX //::::::::::::://
	public function indexAction()
	{ 
	  
		// on ajoute le filtre sur la vue des propositions
		
		$proposition = new Default_Model_Proposition;
		//$this->view->propositionArray =$proposition->fetchAll('Etat = "NV"');

		//création de notre objet Paginator avec comme paramétre la méthode
		//récupérant toutes les entrées dans notre base de données
		$paginator = Zend_Paginator::factory($proposition->fetchAll($str=array()));
		//indique le nombre déléments é afficher par page
		$paginator->setItemCountPerPage(20);
		//récupére le numéro de la page é afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'accéder é la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		$this->view->propositionArray = $paginator;
		
		/*//création d'un d'une instance Default_Model_Users
		$proposition = new Default_Model_Proposition();

		//$this->view permet d'accéder é la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		//la valeur correspond é un tableau d'objets de type Default_Model_Users récupérés par la méthode fetchAll($str)
		//$this->view->PropositionArray = $propositon->fetchAll($str);

		//création de notre objet Paginator avec comme paramétre la méthode
		//récupérant toutes les entrées dans notre base de données
		$paginator = Zend_Paginator::factory($proposition->fetchAll($str=array()));
		//indique le nombre déléments é afficher par page
		$paginator->setItemCountPerPage(10);
		//récupére le numéro de la page é afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'accéder é la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		$this->view->propositionArray = $paginator;*/
	}


	//:::::::::::::// ACTION CREER//::::::::::::://
	public function creerAction()   
	{   
		//création du fomulaire
		$form = new Default_Form_Proposition();
		//indique l'action qui va traiter le formulaire
		//$form->setAction($this->view->url(array('controller' => 'proposition', 'action' => 'create'), 'default', true));

		$data = array();
		//assigne le formulaire é la vue
		$this->view->form = $form;
		$this->view->title = "Creer une Proposition";
	
	
        $where = array('id_entite= ?' => '2');
	    $form->setDbOptions('Ressource',new Default_Model_Personne(),'getId','getNomPrenom',$where);

	    

		 $this->_helper->viewRenderer('creer');  // creer proposition
	     $this->view->form = $form;

		//si la page est POSTée = formulaire envoyé
		if($this->_request->isPost())   
		{    
		    
			//récupération des données envoyées par le formulaire
			
			$data = $this->_request->getPost();
			
            $personne = new Default_Model_Personne();
			$id_personne = $data['Ressource']; // id personne 
	        $pers = $personne->find($id_personne);   // retourne l'objet personne ayant l'id "$id_personne"
			
			//vérifie que les données répondent aux conditions des validateurs
			if($form->isValid($data))  // form valide 
			{
				if($data['Ressource'] === 'x')     // si on a pas selectionné une ressource  id = 'x'
				{
				   $this->view->error = "Veuillez selectionner une ressource !";
				}
				elseif ($data['Debut'] > $data['Fin'])
					// MTA : modification du message echo "......."
					$this->view->error = "La date de début doit être inférieure ou égale à la date de fin";
				else       
				{ 
				//création et initialisation d'un objet Default_Model_Proposition
				//qui sera enregistré dans la base de données
	
				    $proposition = new Default_Model_Proposition();
			    	$proposition->setId_personne($data['Ressource']);		
					$proposition->setDate_debut($data['Debut']);
					$proposition->setDate_fin($data['Fin']);
					$proposition->setMi_debut_journee($data['DebutMidi']);
					$proposition->setMi_fin_journee($data['FinMidi']);
					$proposition->setNombre_jours();
					$proposition->setEtat('NC');
                    	
					try 
					{	
						$proposition->save();
           
						$this->view->success = "Création de la proposition pour Mr/Mme : ".$pers->getNomPrenom();	
						// vider le formulaire pour crée une autre proposition 
						$form->getElement('Ressource')->setValue('');
						$form->getElement('Debut')->setValue('');
						$form->getElement('Fin')->setValue('');
						$form->getElement('DebutMidi')->setValue('');
						$form->getElement('FinMidi')->setValue('');
						
					
						//redirection vers afficher csm 	
						//$this->_helper->redirector('affichercsm');
							 
				
					} 
					catch (Exception $e) 
					{
						//$this->view->error = $e->getMessage();
						 $this->view->error = "Création de la proposition pour Mr/Mme : ".$pers->getNomPrenom()." à échoué !";	
					}
					
					}

			}
			else  // form invalide 
			{   
				$form->populate($data);
				if($data['Ressource'] === 'x')     // si on a pas selectionné une ressource  id = 'x'
				{
				   $this->view->error = "Veuillez selectionner une ressource !";
				}
				elseif($data['Debut'] == null || $data['Fin'] == null )
				{       
					   if($data['Debut'] == null )
					   		$this->view->error = "Veuillez saisir une date de debut !";
					   elseif($data['Fin'] == null )
					  	 	$this->view->error = "Veuillez saisir une date de fin !";
				}
				elseif ($data['Debut'] > $data['Fin'])
					
					$this->view->error = "La date de début doit être inférieure ou égale à la date de fin";
				else
				
				$this->view->error = "Formulaire invalide !";
			}
		
		}
		else 
		{
		       
			//si erreur rencontrée, le formulaire est rempli avec les données
			//envoyées précédemment
			
			$form->populate($data);
			
		}
		
}


//:::::::::::::// ACTION MODIFIER //::::::::::::://
	public function modifierAction()
	{
        $this->_helper->viewRenderer('creer'); // creer proposition
		//création du fomulaire
		$form = new Default_Form_Proposition();
		//indique l'action qui va traiter le formulaire
		//$form->setAction($this->view->url(array('controller' => 'proposition', 'action' => 'edit'), 'default', true));
		$form->Valider->setLabel('Modifier');
		//assigne le formulaire à la vue
		$this->view->form = $form;
		$this->view->title = "Modifier Proposition"; //MTA
		
		//récupération des données envoyées par le formulaire
		$data_id =  $this->getRequest()->getParams();

         $proposition = new Default_Model_Proposition();
         $personne = new Default_Model_Personne();
         
         // recupere l'id personne qui a posé la proposition  
         $prop = $proposition->find($data_id['id']);
	     $id_personne =  $prop->getId_personne();  // id personne 
	  
	     $pers = $personne->find($id_personne);   // retourne l'objet personne ayant l'id "$id_personne"
	     
	     
	    // stocker les anciennes valeurs du formulaire 
		 $PreData['Debut']=  $proposition->getDate_debut();
		 $PreData['DebutMidi'] = $proposition->getMi_debut_journee();
		 $PreData['Fin'] = $proposition->getDate_fin();
		 $PreData['FinMidi'] = $proposition->getMi_fin_journee();
	     
	     // stocker les nouvelles valeurs du formulaire 
	     $data = array();
	     $data['_date_debut'] = $form->getElement('Debut')->getValue();
	     $data['_mi_debut_journee'] = $form->getElement('DebutMidi')->getValue();
	     $data['_date_fin'] = $form->getElement('Fin')->getValue();
	     $data['_mi_fin_journee'] = $form->getElement('FinMidi')->getValue();

	     // remplie le select avec le  nom et prenom de la personne ayant id personne  
	     $where = array('id = ?' => $id_personne);
		 $form->setDbOptions('Ressource',new Default_Model_Personne(),'getId','getNomPrenom',$where);

		 
		 // placeholder 
		 $form->getElement('Debut')->setAttrib('placeholder', 'Saisissez une date debut ...');
		 $form->getElement('Fin')->setAttrib('placeholder', 'Saisissez une date fin ...');
		 
		 
		// remplir le formulaire par les données recupérer 
		$form->getElement('Ressource')->setValue($id_personne);
		$form->getElement('Debut')->setValue($PreData['Debut']);
		$form->getElement('Fin')->setValue($PreData['Fin']);
		$form->getElement('DebutMidi')->setValue($PreData['DebutMidi']);
		$form->getElement('FinMidi')->setValue($PreData['FinMidi']);
		

		//si la page est POSTée = formulaire envoyé
		if($this->getRequest()->isPost())
		{ 
			//récupération des données envoyées par le formulaire
			$data =  $this->getRequest()->getParams();
            
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
							elseif($i == 1)
				        	{
				        	 	 $this->view->warning = "Aucun champs n'a été modifié !";
				         	}
						    elseif ($data['Debut'] > $data['Fin'])
							$this->view->error = "La date de début doit être inférieure ou égale à la date de fin";
				         	else 
			                {       
			
				        	     // remplir l'objet proposition par les valeurs modifiées     
			                	 $proposition->setId($data_id['id']);
			                     $proposition->setId_personne($id_personne);
			                	 $proposition->setDate_debut($data['Debut']);
					             $proposition->setDate_fin($data['Fin']);
					             $proposition->setMi_debut_journee($data['DebutMidi']);
					             $proposition->setMi_fin_journee($data['FinMidi']);
					             $proposition->setNombre_jours();
					             $proposition->setEtat('NC');
					             
					             $this->view->title = "Modification de la proposition";
					             //$this->view->title = "Modification de la proposition de Mr/Mme : ".$pers->getNomPrenom();	

								 $proposition->save();  // insérer dans la base 
							     $this->view->success = " La proposition a été modifié avec succès !";
							
								 //redirection vers afficher csm 	
								 // $this->_helper->redirector('affichercsm');
							     
			                }
			}

		}
	}
	

	//:::::::::::::// ACTION DELETE //::::::::::::://
	public function supprimerAction()
	{
		
		
		 if($this->getRequest()->isXmlHttpRequest())
		 {     
		 	
		 	   //récupére les paramétres de la requéte Ajax 
		 	$data = $this->getRequest()->getPost();
			$id = $data['id'];   
		        
			//création du modéle pour la suppression
			$proposition = new Default_Model_Proposition();

			try 
			{     //appel de la fcontion de suppression avec en argument,
				  //la clause where qui sera appliquée
				  $result = $proposition->delete("id=$id");   
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
					         $this->view->success = "La proposition a bien été supprimer !";
				        	 $content = array("status"=>"200","result"=> "1");
	       					
	                         // envoi de reponse en format Json
	       		       		 $this->_helper->json($content);

				//redirection
				//$this->_helper->viewRenderer('affichercsm');

		}

}
		/*
		 * cette fonction permet é l'admin de valider les propositions et les enregistré dans 
		 * la table :conge
		 */
	
	
	//:::::::::::::// ACTION Valider //::::::::::::://
	public function validerAction()
	{
		//récupére les paramétres de la requéte	
		$params = $this->getRequest()->getParams();

		$proposition = new Default_Model_Proposition();
		$result = $proposition->find($params['id']);

        if(isset($params['id']))
		{   
						// sauvegarder les données recus de la requete 
				    	$id_proposition = $proposition->getId(); 
				    	$id_personne = $proposition->getId_personne(); 
				    	$date_debut =$proposition->getDate_debut(); 
				    	$date_fin = $proposition->getDate_fin();
				    	$debut_midi = $proposition->getMi_debut_journee();
				    	$fin_midi = $proposition->getMi_fin_journee();
				    	
				    	// mettre l'etat de la proposition à OK
				    	$etat = $proposition->setEtat("OK")->save();  
			            
				    	// extraire l'année de reference depuis la date de debut 
				    	$time = strtotime($date_debut);
		                $annee_ref = date('Y',$time);
		                $id_type_conge = '1';
		                $ferme = '1';

		    			// apres la validation de la proposition sera enregister dans la table conge
				
						$conge = new Default_Model_Conge();
						$resultat_id_conge = $conge->fetchAll(NULL);
						$tableau_id_conge = array();
						$index =0;
						foreach($resultat_id_conge as $c)
						{
							$tableau_id_conge[$index] = $c->getId_proposition();
							$index++;
						}
						
						
							// remplir l'objet conge avec les informations necessaires 
							$conge->setId_proposition($id_proposition);
			                $conge->setId_personne($id_personne);
			                $conge->setDate_debut($date_debut);
			                $conge->setDate_fin($date_fin);
			                $conge->setMi_debut_journee($debut_midi);
			                $conge->setMi_fin_journee($fin_midi);
			                $conge->setNombre_jours();
			                $conge->setAnnee_reference($annee_ref);
			                $conge->setId_type_conge($id_type_conge);
			                $conge->setFerme($ferme); 	
				
			            if (!in_array($result->getId('id'), $tableau_id_conge))
						{
							$conge->save();
						}

				    	//redirection
				        $this->_helper->redirector('affichercsm');

	  
		 }  
 }

 	//:::::::::::::// ACTION REFUSER //::::::::::::://
	public function refuserAction()
	{
		//récupére les paramétres de la requéte
		$params = $this->getRequest()->getParams();
		//vérifie que le paramétre id existe
		if(isset($params['id']))
		{
			$id = $params['id'];

			//création du modéle pour le refus
			$proposition = new Default_Model_Proposition();
						
			$result = $proposition->find($id);
			$result->setEtat("KO")->save();
			//redirection
			$this->_helper->redirector('affichercsm');
			
		}
		
		else
		{
			$this->view->form = $params;
		}
	}
	
	
    //:::::::::::::// ACTION AFFICHERADMIN //::::::::::::://
	public function afficheradminAction ()
	{
		$proposition = new Default_Model_Proposition;
		$paginator = Zend_Paginator::factory($proposition->fetchAll('Etat = "NC"'));
		$paginator->setItemCountPerPage(10);
		//récupére le numéro de la page à afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));
		//on initialise la valeur PropositionArray de la vue
		$this->view->propositionArray = $paginator;
	}
	//:::::::::::::// ACTION AFFICHERCSM //::::::::::::://
	public function affichercsmAction ()
	{
		$proposition = new Default_Model_Proposition;
		$paginator = Zend_Paginator::factory($proposition->fetchAll($str = array()));
		$paginator->setItemCountPerPage(10);
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));
		$this->view->propositionArray = $paginator;
     		
	}
	
	
    //:::::::::::::// ACTION REDIRIGERVERSINDEX //::::::::::::://
	public function rederigerversindexAction ()
	{
	   $this->_helper->redirector('index');
		
	}
	
	
	//:::::::::::::// ACTION MESSAGE //::::::::::::://
	public function messageAction()
	{
		$parame = $this->getRequest()->getParams();
		$this->view->id = $parame['id'];
	}

}
