
<?php
class PropositionController extends Zend_Controller_Action
{   
     
	
     public function preDispatch() 
    {
    	    $doctypeHelper = new Zend_View_Helper_Doctype();
            $doctypeHelper->doctype('HTML5');
    
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
		
	
	}


	//:::::::::::::// ACTION CREER//::::::::::::://
	public function creerAction()   
	{   
		//création du fomulaire
		$form = new Default_Form_Proposition();
		//indique l'action qui va traiter le formulaire
		//$form->setAction($this->view->url(array('controller' => 'proposition', 'action' => 'create'), 'default', true));

		//assigne le formulaire é la vue
		$this->view->form = $form;
		$this->view->title = "<h4>Créer une Proposition :</h4><br/>";

		// creer proposition    
		$this->_helper->viewRenderer('creer');  
	    $this->view->form = $form;
	    
	    
        // remplir le select avec les ressources CSM lors du chargement de la page 
        $where = array('id_entite= ?' => '2');
	    $form->setDbOptions('Ressource',new Default_Model_Personne(),'getId','getNomPrenom',$where);

	    $data = array();
		//si la page est POSTée = formulaire envoyé
		if($this->_request->isPost())   
		{    
			//récupération des données envoyées par le formulaire
			$data = $this->_request->getPost();

            $personne = new Default_Model_Personne();
			$id_personne = $data['Ressource'];       // id personne 
	        $pers = $personne->find($id_personne);   // retourne l'objet personne ayant l'id "$id_personne"
	        
	   
			//vérifie que les données répondent aux conditions des validateurs
			if($form->isValid($data))  // form valide 
			{
				if($data['Ressource'] === 'x')  // si on a pas selectionné une ressource  id = 'x'
				{
				   $this->view->error = "Veuillez selectionner une ressource !";
				}
				elseif ($data['Debut'] > $data['Fin']) // si date debut > date fin 
				{	
					$this->view->error = "La date de début doit étre inférieure ou égale é la date de fin";
				}	
				// si on a coché dans une journée les deux cases debut_midi et fin_midi 
			    elseif(($data['Debut'] == $data['Fin']) && ($data['DebutMidi'] == 1 && $data['FinMidi'] == 1))
				{
				     $this->view->error = "Sur un meme jour vous ne pouvez selectionner que 'Debut midi' ou 'Fin midi' !";
				     $form->getElement('DebutMidi')->setValue('0');
				     $form->getElement('FinMidi')->setValue('0');
				}
				else // sinon 
				{     
								  

					try 
					{          
						      //création et initialisation d'un objet Default_Model_Proposition
			    		      //qui sera enregistré dans la base de données
	    		                $proposition = new Default_Model_Proposition();   
	    		  
                                $outils = new Default_Controller_Helpers_outils();   
                                     
	    		              //************** gerer les datetimes en fonction des demis journées *****************************// 
			     			    $date = $outils->makeDatetime($data['Debut'],$data['Fin'],$data['DebutMidi'],$data['FinMidi']); 
			     	          //***********************************************************************************************// 		   		        

	    		              //************** Normaliser date debut et date fin ***************//
				        	    $tab = $outils->normaliser_date($date[0],$date[1],true);          // maroc = true   
	                  	      //****************************************************************//    
	    		             
				        	    if($tab == null)
				        	    {
				        	       $this->view->warning = $pers->getNomPrenom()." a posé une proposition sur une periode non ouvrable date debut : ".$date[0]." date fin :".$date[1];  
				        	    }
				        	    else 
				        	    {
							    	$proposition->setId_personne($data['Ressource']);	
							        $proposition->setDate_debut($tab[0]);   // date_debut normaliser 
									$proposition->setDate_fin($tab[1]);     // date_fin normaliser 
									$proposition->setMi_debut_journee($data['DebutMidi']);
									$proposition->setMi_fin_journee($data['FinMidi']);

									//////////////////////////////////calcul nombre de jours/////////////////////////// 
								 	$proposition->calcul_periode_proposition($tab[0],$tab[1]);                              
									///////////////////////////////////////////////////////////////////////////////////

									$proposition->setEtat('NC');
	                                
							      //****************/// Gestion des chevauchements de propositions ///****************//			
								    $p = new Default_Model_DbTable_Proposition();
								    $res_p = $p->propositions_en_double($proposition->getId_personne(),$proposition->getDate_debut(),$proposition->getDate_fin(),$proposition->getMi_debut_journee(),$proposition->getMi_fin_journee(), null);
		                          //****************************************************************************//	
									
								  //****************/// Gestion des chevauchements de congés ///****************//			
								    $c = new Default_Model_DbTable_Conge();
								    $res_c = $c->conges_en_double($proposition->getId_personne(),$proposition->getDate_debut(),$proposition->getDate_fin(),$proposition->getMi_debut_journee(),$proposition->getMi_fin_journee(), null);
		                          //****************************************************************************//	    
								    
								    
					                // pour l'affichage de  " du : 2013-05-06 à Midi"     au lieu de  " du : 2013-02-06 12:00:00 " 
									$Arr =  $outils->makeMidi($proposition->getDate_debut(),$proposition->getDate_fin());  	
	    
						          // proposition n'existe pas dans la base de donnée 
		                    	    if($res_p == null)
		                   			{    
		                   				// si le congé n'existe pas 
		                   				if($res_c == null)
		                   		    	{   
		                   		    		 // oui 
										      $proposition->save();
										    
										      $this->view->success = "Cr&eacute;ation d'une proposition pour :   ".$pers->getNomPrenom()." 	&nbsp;&nbsp;&nbsp; du :   ".$Arr[0]."  ".$Arr[1]."	  &nbsp;&nbsp;&nbsp; au :   ".$Arr[2]."   ".$Arr[3];             
	                                        
		                   				    // vider le formulaire pour crée une autre proposition
										    $form->getElement('Ressource')->setValue('');
											$form->getElement('Debut')->setValue('');
											$form->getElement('Fin')->setValue('');
											$form->getElement('DebutMidi')->setValue('');
											$form->getElement('FinMidi')->setValue('');	
		                   			    }
		                   			    // si le congé existe 
									    elseif($res_c <> null)
								        {   
								        	  // non 
		                   				     $this->view->warning = "La proposition de :".$pers->getNomPrenom()." est d&eacute;j&agrave; pass&eacute;e en cong&eacute; !";
								        }	 
		                   			}
									elseif($res_p <> null)    // chevauchement existe 
								    {     
								    	
								        if(count($res_p) == 1)  // doublon = 1 
										{  
											
							                $Arr =  $outils->makeMidi($res_p[0]['date_debut'],$res_p[0]['date_fin']);
											$this->view->warning = $pers->getNomPrenom()." &nbsp;&nbsp;a d&eacute;j&agrave; pos&eacute; une proposition sur la p&eacute;riode &nbsp;&nbsp;&nbsp; du :  ".$Arr[0]."  ".$Arr[1]."	&nbsp;&nbsp;&nbsp; au :  ".$Arr[2]."  ".$Arr[3]." &nbsp;!";    
										
										} 
										else                     // doublons > 1 
										{   
											
											// Responsable sur l'affichage de la periode total des propositions ( 'date_debut' de la "1ere proposition"     et 'date_fin' de la "derniere proposition" )
											// remplir un tableau par toute les dates (debut et fin) de toute les propositions de cette personne 
											// trié le tableau rempli et afficher la premiere et la derniere valeur 
											$j = 0;
											for($i=0; $i < count($res_p); $i++ )
											{
										   		$t[$j] = $res_p[$i]['date_debut'];
										    	$t[$j+1] = $res_p[$i]['date_fin'];
										    	$j = $j+2;
											}
								   		    sort($t);   // trié ASC par default 
	                                        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
											$Arr =  $outils->makeMidi($t[0],$t[count($t)-1]);
										    $this->view->warning = $pers->getNomPrenom()."&nbsp;&nbsp; &agrave; d&eacute;ja pos&eacute; &nbsp;&nbsp;".count($res_p)." &nbsp; propositions sur la p&eacute;riode &nbsp;&nbsp;&nbsp; du : ".$Arr[0]."  ".$Arr[1]." &nbsp;&nbsp;&nbsp; au :  ".$Arr[2]."  ".$Arr[3]." &nbsp;!";
										
										}
								    	    
								    }
				        	     } // else if tab null
					}
					catch (Exception $e) 
					{
						 // echo  $e->getMessage();
						 $this->view->error = "Création de la proposition pour  : ".$pers->getNomPrenom()." a échoué !";	
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
					   {	
					   	    $this->view->error = "Veuillez saisir une date de debut !";
					   }	
					   elseif($data['Fin'] == null )
					   {	 	
					   	    $this->view->error = "Veuillez saisir une date de fin !";
					   }
				}
				elseif ($data['Debut'] > $data['Fin'])
					
					$this->view->error = "La date de début doit étre inférieure ou égale é la date de fin";
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
		//assigne le formulaire é la vue
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
		 $PreData['Debut']=  substr($proposition->getDate_debut(),0,10);  // extraire la datedebut du datetime
		 $PreData['DebutMidi'] = $proposition->getMi_debut_journee();
		 $PreData['Fin']=  substr($proposition->getDate_fin(),0,10);      // extraire la datefin du datetime
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
		$form->getElement('Debut')->setValue(substr($PreData['Debut'],0,10)); // afficher la datedebut du datetime
		$form->getElement('Fin')->setValue(substr($PreData['Fin'],0,10));     // afficher la datefin du datetime
		$form->getElement('DebutMidi')->setValue($PreData['DebutMidi']);
		$form->getElement('FinMidi')->setValue($PreData['FinMidi']);
		

		//si la page est POSTée = formulaire envoyé
		if($this->getRequest()->isPost())
		{ 
			//récupération des données envoyées par le formulaire
			$data =  $this->getRequest()->getParams();
			
			// récupération de l'url 
			$requete = $this->getRequest();
			if ($requete instanceof Zend_Controller_Request_Http)
			{ 
				$baseurl = $requete->getBaseUrl();
			}

            
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
							elseif(($data['Debut'] == $data['Fin']) && ($data['DebutMidi'] == 1 && $data['FinMidi'] == 1))
							{
							   $this->view->error = "Sur un meme jour vous ne pouvez selectionner que ' Debut midi ' ou ' Fin midi '  !";
						       $form->getElement('DebutMidi')->setValue($PreData['DebutMidi']);  
							   $form->getElement('FinMidi')->setValue($PreData['FinMidi']);
							}
							elseif($i == 1)
				        	{
				        	 	 $this->view->warning = "Aucun champ n'a été modifié !";
				         	}
						    elseif ($data['Debut'] > $data['Fin'])
							$this->view->error = "La date de début doit étre inférieure ou égale é la date de fin";
				         	else 
			                {       
                                       $this->view->title = "Modification de la proposition";
                                       $outils = new Default_Controller_Helpers_outils();
                                     
									try 
						 			{       
						 		        
			    		                //************** gerer les datetimes en fonction des demis journées *****************************// 
					     			        $date =  $outils->makeDatetime($data['Debut'],$data['Fin'],$data['DebutMidi'],$data['FinMidi']); 
					     	            //***********************************************************************************************// 	
						 				
							 		    //************** Normaliser date debut et date fin ***************//
					        	   	    	$tab = $outils->normaliser_date($date[0],$date[1],true);      // maroc = true 
		                  	    	    //****************************************************************//
			
						        	    // remplir l'objet proposition par les valeurs modifiées     
					                	 $proposition->setId($data_id['id']);
					                     $proposition->setId_personne($id_personne);
					                	 $proposition->setDate_debut($tab[0]);
							             $proposition->setDate_fin($tab[1]);
							             $proposition->setMi_debut_journee($data['DebutMidi']);
							             $proposition->setMi_fin_journee($data['FinMidi']);
							             ////////////////calcul nombre de jours/////////////////////
							 			 $proposition->calcul_periode_proposition($tab[0],$tab[1]);                              
										 ///////////////////////////////////////////////////////////
							             $proposition->setEtat('NC');
							           
							            //****************/// Gestion des chevauchements de propositions ///****************//	
												
									     $p = new Default_Model_DbTable_Proposition();
										 $res_p = $p->propositions_en_double($proposition->getId_personne(),$proposition->getDate_debut(),$proposition->getDate_fin(),$proposition->getId());
						                
										 
						 			    //****************/// Gestion des chevauchements de congés ///****************//			
							               $c = new Default_Model_DbTable_Conge();
							               $res_c = $c->conges_en_double($proposition->getId_personne(),$proposition->getDate_debut(),$proposition->getDate_fin(), null);
	                                    //****************************************************************************//	    
							    
							            // proposition n'existe pas dans la base de donnée 
			                    	    if($res_p == null)
			                   			{    
			                   				// si le congé n'existe pas 
			                   				if($res_c == null)
			                   		    	{   // oui 
											    $proposition->save();
			                   				    $this->view->success = " La proposition a été modifié avec succés !";
										        header("Refresh:1.5;URL=".$baseurl."/proposition/afficher");              // URL dynamique 
		
			                   			    }
			                   			    // si le congé existe 
										    elseif($res_c <> null)
									        {   
									        	  // non 
			                   				     $this->view->warning = "Avec cette modification vous touchez un congé existant !";
			                   				     // remplir le formulaire par les données recupérer 
												$form->getElement('Ressource')->setValue($id_personne);
												$form->getElement('Debut')->setValue(substr($PreData['Debut'],0,10)); // afficher la datedebut du datetime
												$form->getElement('Fin')->setValue(substr($PreData['Fin'],0,10));     // afficher la datefin du datetime
												$form->getElement('DebutMidi')->setValue($PreData['DebutMidi']);
												$form->getElement('FinMidi')->setValue($PreData['FinMidi']);
			                   				     
									        }	 
			                   			}
			                   			// si la proposition existe
										elseif($res_p <> null)
									    {    
											$this->view->warning = "Avec cette modification vous touchez une proposition existante !";
											// remplir le formulaire par les données recupérer 
											$form->getElement('Ressource')->setValue($id_personne);
											$form->getElement('Debut')->setValue(substr($PreData['Debut'],0,10)); // afficher la datedebut du datetime
											$form->getElement('Fin')->setValue(substr($PreData['Fin'],0,10));     // afficher la datefin du datetime
											$form->getElement('DebutMidi')->setValue($PreData['DebutMidi']);
											$form->getElement('FinMidi')->setValue($PreData['FinMidi']);
									    }
		 
				                }
								catch (Exception $e) 
								{
										$this->view->error = "Modification de la proposition pour : ".$pers->getNomPrenom()." a échoué !";	
								}
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
				        	 //en cas de succés envoie de reponse avec code succés [200]
					         $this->view->success = "La proposition a bien été supprimer !";
				        	 $content = array("status"=>"200","result"=> "1");
	       					
	                         // envoi de reponse en format Json
	       		       		 $this->_helper->json($content);
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
				    	
				    	// mettre l'etat de la proposition é OK
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
			              	
			                $conge->calcul_periode_conge(new DateTime($date_debut),new DateTime($date_fin));
			                
			                $conge->setAnnee_reference($annee_ref);
			                $conge->setId_type_conge($id_type_conge);
			                $conge->setFerme($ferme); 	
				
			            if (!in_array($result->getId('id'), $tableau_id_conge))
						{
							$conge->save();
						}

				    	//redirection
				        $this->_helper->redirector('afficher');
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
			$this->_helper->redirector('afficher');
			
		}
		else
		{
			$this->view->form = $params;
		}
	}
	
	
    //:::::::::::::// ACTION AFFICHERADMIN //::::::::::::://
	/*public function afficherAction ()
	{
		$proposition = new Default_Model_Proposition;
		$paginator = Zend_Paginator::factory($proposition->fetchAll('Etat = "NC"'));
		$paginator->setItemCountPerPage(10);
		//récupére le numéro de la page é afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));
		//on initialise la valeur PropositionArray de la vue
		$this->view->propositionArray = $paginator;
	}
	*/
	//:::::::::::::// ACTION AFFICHERCSM //::::::::::::://
	public function afficherAction ()
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
