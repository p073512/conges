<?php
class CongeController extends Zend_Controller_Action
{
    public function preDispatch() 
    {
	    $doctypeHelper = new Zend_View_Helper_Doctype();
        $doctypeHelper->doctype('HTML5');	
       
    }
	
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::// ACTION INDEX //::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
	  
	public function indexAction()
	{
		//création d'un d'une instance Default_Model_Users
		$conge = new Default_Model_Conge();

		//$this->view permet d'accéder Ã  la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		//la valeur correspond Ã  un tableau d'objets de type Default_Model_Users récupérés par la méthode fetchAll()
		//$this->view->usersArray = $users->fetchAll();

		//création de notre objet Paginator avec comme paramétre la méthode
		//récupérant toutes les entrées dans notre base de données
		$paginator = Zend_Paginator::factory($conge->fetchAll($str =array()));
		//indique le nombre déléments Ã  afficher par page
		$paginator->setItemCountPerPage(20);
		//récupére le numéro de la page Ã  afficher
		$paginator->setCurrentPageNumber($this->getRequest()->getParam('page'));

		//$this->view permet d'accéder Ã  la vue qui sera utilisée par l'action
		//on initialise la valeur usersArray de la vue
		//(cf. application/views/scripts/users/index.phtml)
		
		$this->view->congeArray = $paginator;
	}

	
	
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::// ACTION CREER //::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://

	public function creerAction()
	{   
		// detecter la version du navigateur :) Ã  utiliser 
		
		// $userAgent = new Zend_Http_UserAgent();
		// $r = $userAgent->getDevice()->getBrowserVersion();
		// var_dump($r);
	

		// création du fomulaire
		 $form = new Default_Form_Conge();
		 
		//indique l'action qui va traiter le formulaire
		//$form->setAction($this->view->url(array('controller' => 'conge', 'action' => 'creer'), 'default', true));

        // assigne le formulaire Ã  la vue
		$this->view->form = $form;
		$this->view->title = "<h4>Deposer un congé :</h4><br/>";


		// remplir la list Année de reference par Annee , Annee + 1 , Annee - 1
	    $date_tmp = getdate(); // recuperer date system
	    $annee = (string) $date_tmp['year']; // extraire l'année 
	    
	    // remplir la list par annee-1 , annee , annee+1
	    $list_annee = array((string)$annee-1=>(string)$annee-1,$annee=>$annee,(string)$annee+1=>(string)$annee+1);
		$anneeref = $form->getElement('AnneeRef');
		$anneeref->setValue($annee);
		$anneeref->setMultiOptions($list_annee);
		

		// remplir le select par les ressources front 
        $where = array('id_entite <> ?' => '2');
	    $form->setDbOptions('Ressource',new Default_Model_Personne(),'getId','getNomPrenom',$where);
		
	    
	    // remplir le type de conge  
	     $form->setDbOptions('TypeConge',new Default_Model_TypeConge(),'getId','getCode');

		 $this->_helper->viewRenderer('creer');  // creer conge

	    // requete POST 
		if($this->_request->isPost())   
		{
			
			// récupération des données envoyés par le formulaire
			$data = $this->_request->getPost();
			$personne = new Default_Model_Personne();
			$id_personne = $data['Ressource'];       // id personne 

	         $pers = $personne->find($id_personne);   // retourne l'objet personne ayant l'id "$id_personne"
			
	         $outils = new Default_Controller_Helpers_outils(); 
			
			// Vérifie si les données répondent aux conditions de validateurs 
			if($form->isValid($data)) // formulaire valide 
			{
	            if($data['Ressource'] === 'x')     // si on a pas selectionné une ressource  id = 'x'
				{$this->view->error = "Veuillez selectionner une ressource !";}
				
				elseif($data['TypeConge'] === 'x')     // si on a pas selectionné un type de conge
				{$this->view->error = "Veuillez selectionner un Type de conge !";}
				
				elseif ($data['Debut'] > $data['Fin'])   // si la date_debut > date_fin          
				{$this->view->error = "La date de d&eacutebut doit &ecirc;tre inf&eacuterieure ou &eacute;gale &agrave la date de fin";}
				
				// si on a coché dans une journée les deux cases debut_midi et fin_midi 
				elseif(($data['Debut'] == $data['Fin']) && ($data['DebutMidi'] == 1 && $data['FinMidi'] == 1)) 
				{   
				    $this->view->error = "Sur un meme jour vous ne pouvez selectionner que ' Debut midi ' ou ' Fin midi ' !";
				    $form->getElement('DebutMidi')->setValue('0');
				    $form->getElement('FinMidi')->setValue('0');
				}
			    else   // sinon 
				{   
	                  try
	                  {     
						  	 $conge = new Default_Model_Conge();  
						  	  	 
						  	//************** gerer les datetimes en fonction des demis journées *****************************// 
			     			    $date = $outils->makeDatetime($data['Debut'],$data['Fin'],$data['DebutMidi'],$data['FinMidi']); 
			     	        //***********************************************************************************************// 		 

							//************** Normaliser date debut et date fin ***************//
				        	    $tab = $outils->normaliser_date($date[0],$date[1],false);     // (maroc = false)  ==> france = true 
	                  	    //****************************************************************//

	                            if($tab == null)
				        	    {
				        	       $this->view->warning = $pers->getNomPrenom()." a posé un congé sur une periode non ouvrable du : ".$date[0]." au :".$date[1];  
				        	    }    
				        	    else 
				        	    {
				        	    
					  		            // remplir l'objet $conge 
										$conge->setId_personne($data['Ressource']);
										$conge->setId_type_conge($data['TypeConge']);
										$conge->setDate_debut($tab[0]);   // date_debut normaliser 
										$conge->setDate_fin($tab[1]);     // date_fin normaliser 
										$conge->setMi_debut_journee($data['DebutMidi']);
										$conge->setMi_fin_journee($data['FinMidi']);
		
									    //////////////////////////////////calcul nombre de jours/////////////////////////// 
										$conge->calcul_periode_conge(new DateTime($tab[0]),new DateTime($tab[1]));
										///////////////////////////////////////////////////////////////////////////////////
										
										$conge->setAnnee_reference($data['AnneeRef']);
									    $conge->setFerme($data['Ferme']);
									    
									    
									 
								    //***********************************/// Gestion des chevauchements de congés ///*********************************//			
										$c = new Default_Model_DbTable_Conge();

										$res = $c->conges_en_double($conge->getId_personne(),$conge->getDate_debut(),$conge->getDate_fin(), null);
									
									    // pour l'affichage de  du : 2013-05-06 Ã  Midi     au lieu de  du : 2013-02-06 12:00:00 
										$Arr =  $outils->makeMidi($conge->getDate_debut(),$conge->getDate_fin());
		             
											    
										if($res == null)   // pas de chevachement de congés
										{      
											    $r =  $outils->authorized($pers,$conge);

					                   		  	  if($r[0] == true)
					                   		  	  {
						                   		         // oui 
													     $conge->save();
														    
												         // affichage du message de succÃ¨s 
											             $this->view->success = "Cr&eacute;ation du cong&eacute; pour :   ".$pers->getNomPrenom()." 	 &nbsp;&nbsp;&nbsp; du :   ".$Arr[0]."  ".$Arr[1]."	  &nbsp;&nbsp;&nbsp; au :   ".$Arr[2]."   ".$Arr[3];                                 
					                                        
						                   		         // vider le formulaire pour crée un autre congé
													     $form->getElement('Ressource')->setValue('');
														 $form->getElement('TypeConge')->setValue('');
														 $form->getElement('Debut')->setValue('');
														 $form->getElement('Fin')->setValue('');
														 $form->getElement('DebutMidi')->setValue('');
														 $form->getElement('FinMidi')->setValue('');
														 $form->getElement('Ferme')->setValue(''); 
					                   		  	  } 
												  else 
												  {     //return array($bool,$personne->getDate_debut(),$personne_datefin,$obj_dd,$obj_df);
									  									  	     
												  	     if($r[3] < $r[1])    // date_debut_conge  < date_debut_projet
												         {
												         	$this->view->error = "La ressource :  ".$pers->getNomPrenom()."  a d&eacute;but&eacute; le :  ".$pers->getDate_debut()."  et ne peux pas pos&eacute; un cong&eacute; avant cette date !";
												         }
												          elseif($res[4] > $res[2])  // date_fin_conge  >  date_fin_projet
									         			  {
										             			 if($res[2] > date("Y-m-d"))  // date_fin_projet  >  date_aujourdhui 
										                         {
													             	$this->view->error = "La ressource :  ".$pers->getNomPrenom()."   a quitt&eacute; le projet le :  ".$pers->getDate_fin()."   impossible de lui cr&eacute;er un cong&eacute; !";
													             }	
													             else                        // date_fin_projet  <=  date_aujourdhui 
													             {
													             	$this->view->error = "La ressource :  ".$pers->getNomPrenom()."  va quit&eacute; le projet le :  ".$pers->getDate_fin()."  et ne peux pas pos&eacute; un cong&eacute; du : ".$r[3]." au :".$r[4]." !";
													             }	
												         }
												         else
												        {   
												        	if($r[2] == "-" || $r[2] == "01/01/1970" || $r[2] == "1970-01-01"  || $r[2] == "0000-00-00" || $r[2] == "00-00-0000")
												        		$this->view->error = " La ressource :  ".$pers->getNomPrenom()."  a d&eacute;but&eacute; le :  ".$pers->getDate_debut()."  aucun cong&eacute; avant cette date n'est acc&eacute;pt&eacute;e !";
												        	else
												        		$this->view->error = " La ressource :  ".$pers->getNomPrenom()."  a d&eacute;but&eacute; le :  ".$pers->getDate_debut()."  et fini le : ".$r[2]." aucun cong&eacute; hors cette p&eacute;riode n'est acc&eacute;pt&eacute;e !";
												        } 
						
												  }
	
										}
										else   // chevauchement existe
										{   
										    
											if(count($res) == 1)  // doublon = 1 
											{
								                $Arr =  $outils->makeMidi($res[0]['date_debut'],$res[0]['date_fin']);
												$this->view->warning = $pers->getNomPrenom()."&nbsp;&nbsp;a d&eacute;j&agrave; pos&eacute; un cong&eacute; sur la p&eacute;riode &nbsp;&nbsp;&nbsp; du :  ".$Arr[0]."  ".$Arr[1]."	&nbsp;&nbsp;&nbsp; au :  ".$Arr[2]."  ".$Arr[3]." &nbsp;!";    
											
											} 
											else           // doublons > 1 
											{   
												// Responsable sur l'affichage de la periode total des congés ( 'date_debut' du "1er congé"     et 'date_fin' du "dernier congé" )
												// remplir un tableau par toute les dates (debut et fin) de tout les congés de cette personne 
												// trié le tableau rempli et afficher la premiere et la derniere valeur 
												$j = 0;
												for($i=0; $i < count($res); $i++ )
												{
											   		$t[$j] = $res[$i]['date_debut'];
											    	$t[$j+1] = $res[$i]['date_fin'];
											    	$j = $j+2;
												}
									   		    sort($t);   // trié ASC par default 
		                                        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
										
												$Arr =  $outils->makeMidi($t[0],$t[count($t)-1]);
											    $this->view->warning = $pers->getNomPrenom()."&nbsp;&nbsp; &agrave; d&eacute;ja pos&eacute; &nbsp;&nbsp;".count($res)." &nbsp; cong&eacutes sur la p&eacute;riode &nbsp;&nbsp;&nbsp; du : ".$Arr[0]."  ".$Arr[1]." &nbsp;&nbsp;&nbsp; au :  ".$Arr[2]."  ".$Arr[3]." &nbsp;!";
											}
		
										}
								  //********************************************************************************************************************//		 
	                  
				       	    } // fin else tab null
				      }
		       	      catch (Exception $e) 
					  {      
					  	 // echo  $e->getMessage();
  						 $this->view->error = "La cr&eacuteation du cong&eacute pour : ".$pers->getNomPrenom()." &agrave; échou&eacute !";	
					  }
		         } 
	      }
	      else  // forme invalide 
	      {
	            if($data['Ressource'] === 'x')     // si on a pas selectionné une ressource  id = 'x'
				{$this->view->error = "Veuillez selectionner une ressource !";}    
				
			    elseif($data['TypeConge'] === 'x')     // si on a pas selectionné un type de conge
				{$this->view->error = "Veuillez selectionner un type de conge !";}
				
	         	elseif($data['Debut'] == null )
			    { $this->view->error = "Veuillez saisir une date de debut !"; }
			    
			    elseif($data['Fin'] == null )
			    {$this->view->error = "Veuillez saisir une date de fin !";}

			    elseif ($data['Debut'] > $data['Fin'])
				{$this->view->error = "La date de d&eacutebut doit &ecirc;tre inf&eacuterieure ou &eacutegale &agrave la date de fin";}	

	            $form->populate($data);
	      }   
	   }  
	}

	
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::// ACTION MODIFIER //:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://

	public function modifierAction()
	{
		 $this->_helper->viewRenderer('creer'); // creer conge
		 $form = new Default_Form_Conge();  	//création du fomulaire
		 $form->Valider->setLabel('Modifier');  //indique l'action qui va traiter le formulaire
		
		 //***// assigne le formulaire Ã  la vue
		 $this->view->form = $form;
		 $this->view->title = "Modifier Conge"; 

  		 $conge = new Default_Model_Conge();
         $personne = new Default_Model_Personne();
		
         //***// récupération des données envoyées par le formulaire
	     $data_id =  $this->getRequest()->getParams();
          
         //***// recupere l'id personne qui a posé le conge
         $cong = $conge->find($data_id['id']);
	     $id_personne =  $cong->getId_personne();  // id personne 
	     
	     $pers = $personne->find($id_personne);   // retourne l'objet personne ayant l'id "$id_personne"

	     $id_type_conge = $cong->getId_type_conge(); // id_type_conge
         
 		 
 		 //***// remplir la liste Année de reference par Annee , Annee + 1 , Annee - 1
 		 $date_debut = $cong->getDate_debut();  // recuperer date debut 
	     $annee = substr($date_debut, 0,4);     // extraire l'année 
 		 $list_annee = array($annee=>$annee,(string)$annee-1=>(string)$annee-1,(string)$annee+1=>(string)$annee+1);
 		 $anneeref = $form->getElement('AnneeRef');
 		 $anneeref->setMultiOptions($list_annee);
 
 		 
 		 //***// stocker les anciennes valeurs du formulaire 
		 $PreData['Debut']=  $conge->getDate_debut();  	
		 $PreData['DebutMidi'] = $conge->getMi_debut_journee();
		 $PreData['Fin'] =  $conge->getDate_fin();         
		 $PreData['FinMidi'] = $conge->getMi_fin_journee();    
		 $PreData['AnneeRef'] = $conge->getAnnee_reference();
		 $PreData['TypeConge'] = $conge->getId_type_conge();
		 $PreData['Ferme'] = $conge->getFerme(); 

		 
		 //***// stocker les nouvelles valeurs du formulaire 
	     $data = array();
	     $data['_date_debut'] = $form->getElement('Debut')->getValue();
	     $data['_mi_debut_journee'] = $form->getElement('DebutMidi')->getValue();
	     $data['_date_fin'] = $form->getElement('Fin')->getValue();
	     $data['_mi_fin_journee'] = $form->getElement('FinMidi')->getValue();
	     $data['_annee_reference'] = $form->getElement('AnneeRef')->getValue();
	     $data['_id_type_conge'] = $form->getElement('TypeConge')->getValue();
	     $data['_ferme'] = $form->getElement('Ferme')->getValue();
		 
	     //***// remplie le select avec les types de conge qui existent
	  /*   if($personne->getEntite()->getCs() == '0') // france == 0 
	     {	*/
	    
	     	$form->setDbOptions('TypeConge',new Default_Model_TypeConge(),'getId','getCode');
	   /*  }
	     else 
	     {  
	     	echo "MAROC" ;
	     	// remplie le select avec le libelle "CP" pour une ressource maroc 
	       	 $id = 1;
	         $cp = array('x'=>'Choisissez:','CP'=>'CP');
 		     $typeconge = $form->getElement('TypeConge');
 		     $typeconge->setMultiOptions($cp);
	       	 //$where = array('id = ?' => $id );
	         //$form->setDbOptions('TypeConge',new Default_Model_TypeConge(),'getId','getCode',$where);
	     }
	     */

		 //***// remplie le select avec le  nom et prenom de la personne ayant id personne  
	     $where = array('id = ?' => $id_personne);
		 $form->setDbOptions('Ressource',new Default_Model_Personne(),'getId','getNomPrenom',$where);
		 		 
		 //***// remplir le formulaire par les données recupérer 
		 $form->getElement('Ressource')->setValue($id_personne);
		 $form->getElement('Debut')->setValue(substr($PreData['Debut'],0,10)); // afficher la datedebut du datetime
		 $form->getElement('Fin')->setValue(substr($PreData['Fin'],0,10));     // afficher la datefin du datetime

		 if(substr($PreData['Debut'],11,19) == "12:00:00")
		 { $PreData['DebutMidi'] = 1;  }
		 else 
		 { $PreData['DebutMidi'] = 0;}
		 if(substr($PreData['Fin'],11,19) == "11:59:59")
		 { $PreData['FinMidi'] = 1;}
		 else 
		 {$PreData['FinMidi'] = 0;}
		 
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
				       { $i*=0; }  	
				    }
					 
				    if($data['Ressource'] === 'x')     // si on a pas selectionné une ressource  id = 'x'
					{
						$this->view->error = "Veuillez selectionner une ressource !";
					}
					elseif($data['TypeConge'] === 'x')     // si on a pas selectionné un type conge  id = 'x'
					{
						$this->view->error = "Veuillez selectionner un type cong&eacute; !";
					}
				    elseif(($data['Debut'] == $data['Fin']) && ($data['DebutMidi'] == 1 && $data['FinMidi'] == 1))
					{
					       $this->view->error = "Sur un meme jour vous ne pouvez selectionner que ' Debut midi ' ou ' Fin midi '  !";
					       $form->getElement('DebutMidi')->setValue($PreData['DebutMidi']);   // afficher que la date du datetime 
					       $form->getElement('FinMidi')->setValue($PreData['FinMidi']);       // afficher que la date du datetime
					}
					elseif($i == 1)  // pas de modification effectué
				    {
				        $this->view->warning = "Aucun champ n'a &eacute;t&eacute; modifi&eacute; !";
				    }
					elseif ($data['Debut'] > $data['Fin'])  // date debut > date fin 
					{ 
						$this->view->error = "La date de d&eacute;but doit étre inf&eacute;rieure ou &eacute;gale &agrave; la date de fin";
					}
				    else       
			        {           
					            $this->view->title = "Modification du cong&eacute;";

					            $outils = new Default_Controller_Helpers_outils();
					            
						try 
					 	{        
					            //************** gerer les datetimes en fonction des demis journées *****************************// 
			     			        $date = $outils->makeDatetime($data['Debut'],$data['Fin'],$data['DebutMidi'],$data['FinMidi']); 
			     	            //***********************************************************************************************// 		 
			     	            
			     			    //************** Normaliser date debut et date fin ***************//
			        	   	    	$tab = $outils->normaliser_date($date[0],$date[1],false);     // (maroc = false) ==> france 
                  	    	    //****************************************************************//
      
			        	   	    	
							        //***// remplir l'objet conge par les valeurs modifiées     
						            $conge ->setId($data_id['id']);
						            $conge->setId_proposition($cong->getId_proposition());
						            $conge->setId_personne($id_personne);
						            $conge->setDate_debut($tab[0]);
								    $conge->setDate_fin($tab[1]);
								    $conge->setMi_debut_journee($data['DebutMidi']);
								    $conge->setMi_fin_journee($data['FinMidi']);
								    $conge->setAnnee_reference($data['AnneeRef']);
								    
									//////////////////////////////////calcul nombre de jours/////////////////////////// 
									$conge->calcul_periode_conge(new DateTime($tab[0]),new DateTime($tab[1]));
									///////////////////////////////////////////////////////////////////////////////////

								    $conge->setId_type_conge($data['TypeConge']);
								    $conge->setFerme($data['Ferme']);    
								    
								 //****************/// Gestion des chevauchements de congés ///****************//	
										
									$c = new Default_Model_DbTable_Conge();

									$res = $c->conges_en_double($conge->getId_personne(),$conge->getDate_debut(),$conge->getDate_fin(),$conge->getId());
									
									if($res == null)
									{	 
										 
										  $r =  $outils->authorized($pers,$conge);
	
			                   		  	  if($r[0] == true)
			                   		  	  {
				                   		      // oui 
											   $conge->save();
										       $this->view->success = " Le cong&eacute; a &eacute;t&eacute; modifi&eacute; avec succ&eacute;s !";              
							                   header("Refresh:1.5;URL=".$baseurl."/conge/afficher");   // URL dynamique 
			                   		  	  } 
										  else 
										  {     					  	     
										  	     if($r[3] < $r[1])    // date_debut_conge  < date_debut_projet
										         {
										         	$this->view->error = "La ressource :  ".$pers->getNomPrenom()."  a d&eacute;but&eacute; le :  ".$pers->getDate_debut()."  aucune modification avant cette date n'est acc&eacute;pt&eacute;e ! ";
										         }
										          elseif($r[4] > $r[2])  // date_fin_conge  >  date_fin_projet
										         {
											             if($r[2] > date("Y-m-d"))  // date_fin_projet  >  date_aujourdhui 
											             {
											             	$this->view->error = "La ressource :  ".$pers->getNomPrenom()."   a quitt&eacute; le projet le :  ".$pers->getDate_fin()."   impossible de modifi&eacute; ce cong&eacute; !";
											             }	
											             else                        // date_fin_projet  >=  date_aujourdhui 
											             {
											             	$this->view->error = "La ressource :  ".$pers->getNomPrenom()."  va quit&eacute; le projet le :  ".$pers->getDate_fin()."  et ne peux modifi&eacute; le cong&eacute; du : ".$r[3]." au :".$r[4]." !";
											             }	
										         }
										         else
										        {   
										        	if(($r[2] == "-" || $r[2] == "01/01/1970" || $r[2] == "1970-01-01"  || $r[2] == "0000-00-00" || $r[2] == "00-00-0000"))
										        		$this->view->error = " La ressource :  ".$pers->getNomPrenom()."  a d&eacute;but&eacute; le :  ".$pers->getDate_debut()."  aucune modification avant cette date n'est acc&eacute;pt&eacute;e !";
										        	else
										        		$this->view->error = " La ressource :  ".$pers->getNomPrenom()."  a d&eacute;but&eacute; le :  ".$pers->getDate_debut()."  et fini le : ".$r[2]." aucune modification hors cette p&eacute;riode n'est acc&eacute;pt&eacute;e !";
										        } 
										  }
    
									}
									else 
									{    
										 $this->view->warning = "Avec cette modification vous touchez un cong&eacute; existant !";
                                         
										 // remplir le formulaire par les données recupérer 
										 $form->getElement('Ressource')->setValue($id_personne);
										 $form->getElement('Debut')->setValue(substr($PreData['Debut'],0,10)); // extraire la datedebut du datetime
										 $form->getElement('Fin')->setValue(substr($PreData['Fin'],0,10));     // extraire la datefin du datetime
										 $form->getElement('DebutMidi')->setValue($PreData['DebutMidi']);  
										 $form->getElement('FinMidi')->setValue($PreData['FinMidi']);
										 $form->getElement('AnneeRef')->setValue($PreData['AnneeRef']);
										 $form->getElement('TypeConge')->setValue($id_type_conge); 
										 $form->getElement('Ferme')->setValue($PreData['Ferme']);

									}  
							      //****************************************************************************//
						} 
						catch (Exception $e) 
						{
								//$this->view->error = $e->getMessage();
								$this->view->error = "Modification du cong&eacute; pour : ".$pers->getNomPrenom()." &agrave; &eacute;chou&eacute; !";	
						}
				
			        }
			}
		}
    }
    
    
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::// ACTION SUPPRIMER //::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://


	public function supprimerAction()
	{
		 if($this->getRequest()->isXmlHttpRequest())
		 {     
		 	 //récupére les paramétres de la requÃªte Ajax 
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
				        	 //en cas de succés envoie de reponse avec code succés [200]
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
