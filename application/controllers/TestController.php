<?php
class TestController extends Zend_Controller_Action
{
	public function indexAction()
	{ 
	    $fileHandle = fopen("C:\\Documents and Settings\\Administrateur\\Bureau\\Template.docx", "r");
    	$line = @fread($fileHandle, filesize($userDoc));   
	    $lines = explode(chr(0x0D),$line);
	    $outtext = "";
	    foreach($lines as $thisline)
	      {
	        $pos = strpos($thisline, chr(0x00));
	        if (($pos !== FALSE)||(strlen($thisline)==0))
	          {
	          } else {
	            $outtext .= $thisline." ";
	          }
	      }
	     $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
	    return $outtext;
	} 
	

        
        
        
        
        
/*
		$utils = new Default_Controller_Helpers_Validation();
		
		$maroc = false;
		$alsacemoselle=false;
		
		$date_debut = new DateTime("2013-01-01");
		$date_fin = new DateTime("2013-12-31");
		
		$debut_midi = true;
		$fin_midi = true;
		
		$annee = "2013";
*/
        // mettre les jours fériés maroc dans session TEST 
		//$jours_feries_maroc = new Zend_Session_Namespace('TEST',false);
        //$jours_feries_maroc->jfm = $utils->jours_feries_maroc($annee);
		///////////////////////////////////////////////////////////////

		
		// $this->view->var = $utils->jours_feries($annee, $alsacemoselle, $maroc);            //OK 

        // $this->view->var = $utils->est_ferie($date_debut, $alsacemoselle, $maroc);          //OK
        
        //  $this->view->var  = $utils->a_normaliser($date_debut, $maroc);                      //OK

        //  $this->view->var =  $utils->normaliser_flag_midi($date_debut,$debut_midi,$maroc);   //OK
        
        //  $this->view->var = $utils->normaliser_date_debut_conge($date_debut,$maroc);         //OK
        
		//  $this->view->var = $utils->normaliser_date_fin_conge($date_fin,$maroc);             //OK
		
		//  $this->view->var = $utils->calcul_nombre_jours_conges($date_debut,$date_fin,$debut_midi,$fin_midi,$maroc);
		
		



	public function calculJoursCongesAction()
	{

		$logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('php://output');
		$logger->addWriter($writer);

		// DEBUT de mon programme
		$logger->log('Calcul des jours ouvrés sur une période donnée', Zend_Log::INFO);
        
		/* format :
		 d et j 	Jour du mois, sur 2 chiffres, avec ou sans le zéro initial 	01 à 31 ou 1 à 31
		 D and l 	Une représentation textuelle du jour 	De Mon jusqu'à Sun ou de Sunday jusqu'à Saturday
		 w 	Jour de la semaine au format numérique 	0 (pour dimanche) à 6 (pour samedi)
		 z 	Jour de l'année 	0 à 365
		 a et A 	Ante meridiem et Post meridiem 	am ou pm

		 */

		// Définition de mes paramètres d'entrée
		$date_debut = new DateTime('2013-01-01');
		$date_fin = new DateTime('2013-01-01');
		$debut_midi = false;
		$fin_midi = false;
		$maroc = false;


		// Sauver les flags midi après normalisation et avant le calcul de nombre de jours de congés
		$this->_helper->validation->normaliser_flag_midi($date_debut,$debut_midi,$maroc);
		$this->_helper->validation->normaliser_flag_midi($date_fin,$fin_midi,$maroc);

		// Sauver les date de début et fin congés après normalisation et avant le calcul de nombre de jours de congés
		$d1 = new DateTime(date_format($date_debut, 'Y-m-d'));
		$d2 = new DateTime(date_format($date_fin, 'Y-m-d'));
		$date_debut = $this->_helper->validation->normaliser_date_debut_conge($date_debut,$maroc);
		$date_fin = $this->_helper->validation->normaliser_date_fin_conge($date_fin,$maroc);

		// Terminer par le calcul du nombre de jours de congés
		$nb_conges = $this->_helper->validation->calculer_jours_conges($d1,$d2,$debut_midi,$fin_midi,$maroc);

		if ($nb_conges <= 0) {
			$logger->log('Erreur, intervalle incorrect', Zend_Log::INFO);
			
		}
		else if ($maroc)
			$logger->log("Conge posé au CSM du ". date_format($date_debut, 'Y-m-d') . " au " . date_format($date_fin, 'Y-m-d') . " soit " . $nb_conges . " jours", Zend_Log::INFO);
		else
			$logger->log("Conge posé en France du ". date_format($date_debut, 'Y-m-d') . " au " . date_format($date_fin, 'Y-m-d') . " soit " . $nb_conges . " jours", Zend_Log::INFO);

		$this->view->var = $nb_conges;
		
	}	
/*
	public function droitsACongesAction()
	{
		$logger = new Zend_Log();
		$writer = new Zend_Log_Writer_Stream('php://output');
		$logger->addWriter($writer);
			
		// DEBUT de mon programme
		$logger->log('Calcul des droits à congés pour une ressource', Zend_Log::INFO);

		/* format :
		 d et j 	Jour du mois, sur 2 chiffres, avec ou sans le zéro initial 	01 à 31 ou 1 à 31
		 D and l 	Une représentation textuelle du jour 	De Mon jusqu'à Sun ou de Sunday jusqu'à Saturday
		 w 	Jour de la semaine au format numérique 	0 (pour dimanche) à 6 (pour samedi)
		 z 	Jour de l'année 	0 à 365
		 a et A 	Ante meridiem et Post meridiem 	am ou pm

		 */

		// Définition de mes paramètres d'entrée
//		$ressource = new Default_Model_Personne();
//		$ressource = $ressource->fetchAll("nom = 'TRIFOL'");
//		$ressource = $ressource[0];
//		$logger->log('Nom Prenom : '.$ressource->toString(), Zend_Log::INFO);
//		$anne_reference = '2013';
/*		
		// Sauver les flags midi après normalisation et avant le calcul de nombre de jours de congés
		$droits = $this->_helper->validation->calculer_droits_a_conges($ressource,$anne_reference);
		
		$this->view->var = $droits;
		
}
*/	
    function afficheAction() 
    {
    	 //chercher toutes les propositions validees par admin
    	$proposition = new Default_Model_Proposition;
       	$result_proposition_ok = $proposition->fetchAll('Etat = "OK"');
    	$str=NULL;	
       	$conge = new Default_Model_Conge();
     	$resultat_id_conge = $conge->fetchAll($str);
     	 $tableau_id_conge = array();
       	$index =0;
      	foreach($resultat_id_conge as $c)
       	{
       		$tableau_id_conge[$index] = $c->getId_proposition();
       		$index++;
       	}
       	//print_r($tableau_id_conge);	
		
		foreach($result_proposition_ok  as $p)
		{
			$conge->setId_proposition($p->getId('id'));
			$conge->setId_personne($p->getId_personne('id_personne'));
	       	$conge->setDate_debut($p->getDate_debut());
	       	$conge->setDate_fin($p->getDate_fin());
	       	$conge->setMi_debut_journee($p->getMi_debut_journee());
	       	$conge->setMi_fin_journee($p->getMi_fin_journee());
	       	$conge->setNombre_jours($p->getNombre_jours());
	       	$conge->setId_type_conge('1');
	       	$conge->setAnnee_reference(date('Y/m/d'));
	       	$conge->setFerme(1);
	       	
	       	if (!in_array($p->getId('id'), $tableau_id_conge))
	       	{
	       		$conge->save();
	       	}
	       	var_dump($conge);
		}
		
	}

	/*public function afficheAction()
	{
		
		
		$proposition = new Default_Model_Proposition;
		$result_proposition_ok = $proposition->fetchAll('id = 18');
		foreach ($result_proposition_ok as $p)
		{
			$date_base=$p->getDate_debut();
		}
		
		echo  $date_base.'</br>';
		echo $date=date('Y-m-d').'</br>';
		list($annee, $mois_A,$day) = explode("-", $date);
		
		echo "le dernier jour du mois est : ".$lastday=date('Y-m-d',mktime(0,0,0,$mois_A+1,0,$annee)).'</br>';
		echo "le premier jour du mois est : ".$firstday=date('Y-m-d',mktime(0,0,0,$mois_A,1,$annee)).'</br>';	

		if ($date_base <= $firstday)
		{
			echo "la date de la base :".$date_base. " est inferieur a celle du".$firstday;
		}
		elseif ($date_base >= $firstday)
		{
			echo "la date de la base :".$date_base." est superieur a celle ".$firstday;
		}
	
	}*/

/*public function afficheAction()
	{
		// tu peut utiliser cette fonction pour afficher les nombre totale ouvere pour un mois donné
    	$date=date('Y-m-d');
		list($annee, $mois_A,$day) = explode("-", $date);
		$fin_mois=date('Y-m-d',mktime(0,0,0,$mois_A+1,0,$annee));
		$debut_moiss=date('Y-m-d',mktime(0,0,0,$mois_A,1,$annee));
		$conge = new Default_Model_Conge();
$nb_jr_ouv_mois = $conge->joursOuvresDuMois($debut_moiss,$fin_mois);
	

		echo  $nb_jr_ouv_mois;
	}

	public function afficheAction()
	{
		$date_base_proposition ='2012-09-15';
		$conge = new Default_Model_Conge();
		$conge = $conge ->fetchall('id_personne = 2');
		$proposition = new Default_Model_proposition();
		$proposition = $proposition ->fetchall('id_personne = 2');
		
		foreach ($proposition as $p ) 
		{
				$date_base = $p->getDate_debut();
		}
		echo $date_base ;
		/*if (count($conge)!=0 ||count($proposition)!=0 ) 
		{
			$date_base_proposition = $proposition->getDate_debut();
			$date_base_conge = $conge->getDate_debut();
			if ($data_base_proposition == $date_base_proposition || $data_base_conge == $date_base_conge)
			{
				echo "proposition ou connge deja demande";
			}
		}
		//else $conge->save();
		//var_dump($conge);
	
	}*/
	/*public function afficheAction()
	{
	$conge = new Default_Model_Conge();
	$debut_moiss = '2012-01-01';
	$fin_mois = '2012-12-31'; // il faut la remplacer par l'annee de reference
	echo $jours_ouvres_de_annee_ref = $conge->joursOuvresDuMois($debut_moiss,$fin_mois);
	$nbr_heurs_ouvrees_annee = ($jours_ouvres_de_annee_ref -14) *7.4;
	$personne = new Default_Model_Personne();
	$personne = $personne->fetchall('id_entite = 1');
	var_dump($personne);
	
	foreach($personne as $p )
	{
		if ($p->getId_modalite() == '4')
		{
			$nbr_heurs_ouvrees_annee = ($jours_ouvres_de_annee_ref -14) *7.4;
			if($nbr_heurs_ouvrees_annee >1607)
			{
				$total_q1 =0.5;
				$p->setTotal_q1($total_q1);
				echo "la modalite N :".$p->getId_modalite()."a 0,5 jour</br>";
			}
			elseif($nbr_heurs_ouvrees_annee <1607.5)
			{
				$total_q1 =1.0;
				$p->setTotal_q1($total_q1);
				echo "la modalite N :".$p->getId_modalite()."a 1 jour</br>";
			}
		}
		
		if ($p->getId_modalite() == '5' ||$p->getId_modalite() == '6' )
		{
			$nbr_jours_ouvrees_annee = $jours_ouvres_de_annee_ref-243;
			if($nbr_jours_ouvrees_annee <  10)
			{
				$total_q1 =10.0;
				$p->setTotal_q1($total_q1);
				echo "la modalite N :".$p->getId_modalite()."a 10 jour</br>";
			}
			elseif($nbr_jours_ouvrees_annee  >10)
			{
				$p->setTotal_q1($nbr_jours_ouvrees_annee);
				echo "la modalite N :".$p->getId_modalite()."a".$nbr_jours_ouvrees_annee."jour</br>";
			}
		}
		
		if ($p->getId_modalite() == '1' ||$p->getId_modalite() == '2'  ||$p->getId_modalite() == '3')
		{
				$total_q1 =10.0;
				$p->setTotal_q1($total_q1);
				
				echo "la modalite N :".$p->getId_modalite()."a 10 jours</br>";
		}
	
		$p->save();
	}
	
	}*/

	
/*public function afficheAction()
	{
	//$conge = new Default_Model_Conge();
	$solde = new Default_Model_Solde;
	$solde = $solde->find('82');
	
	$anne_ref = $solde->getAnnee_reference();
	  $id = $solde->getId_personne();
	$debut_moiss = $anne_ref.'-01-01';
	$fin_mois = $anne_ref.'-12-31';
	
	$conge = new Default_Model_Conge();
	$jours_ouvres_de_annee_ref = $conge->joursOuvresDuMois($debut_moiss,$fin_mois);
	 $jours_ouvres_de_annee_ref;
	 $nbr_heurs_ouvrees_annee = ($jours_ouvres_de_annee_ref -14) *7.4."</br>";
	$id_entite =1;
	$personne = new Default_Model_Personne();
	$personne = $personne->fetchall('id_entite ='.$id_entite. '&&'. 'id ='.$id);
var_dump($personne);
	
	foreach($personne as $p )
	{
		if ( $p->getId_modalite() == '4')
		{
			echo $p->getId_modalite();
			$nbr_heurs_ouvrees_annee = ($jours_ouvres_de_annee_ref -14) *7.4;
			if($nbr_heurs_ouvrees_annee >1607)
			{
				echo 0.5 ."</br>";
			}
			elseif($nbr_heurs_ouvrees_annee <1607.5)
			{
				echo 1.0 ."</br>";
			}
		}
		
		if ($p->getId_modalite() == '5' ||$p->getId_modalite() == '6' )
		{
			echo $p->getId_modalite();
			$nbr_jours_ouvrees_annee = $jours_ouvres_de_annee_ref-243;
			if($nbr_jours_ouvrees_annee <  10)
			{
				echo 10.0 ."</br>";
			}
			elseif($nbr_jours_ouvrees_annee  >10)
			{
				echo $nbr_jours_ouvrees_annee ."</br>";
			}
		}
		
		if ($p->getId_modalite() == '1' ||$p->getId_modalite() == '2'  ||$p->getId_modalite() == '3')
		{
			echo $p->getId_modalite();
				echo 10.0 ."</br>";
		}
	}
	}*/
	/*
	 * la fonction qui gere le reste de conge
	 */
	
	
	
	
	/*public function afficheAction()
	{
		

$date=date('D/d/m/Y');
list($dcourt,$day, $month, $year) = explode("/", $date);

/*$joursem = array('dim', 'lun', 'mar', 'mer', 'jeu', 'ven', 'sam');
$mois_A=array('','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
$numjour= array(0, 1, 2, 3, 4,5,6); // 0 pour un dimanche et 6 pour un samedi
// calcul du timestamp
$timestamp = mktime (0, 0, 0, $month, 01, $year);
echo $year;
		if( $this ->_getParam ('mois'))
		{
		echo $Username = $this ->_getParam ('mois');	
		}
		$mois_A = 0;
		if ($this ->_getParam ('mois'))
		{
			$mois_A= $this ->_getParam ('mois');
			$annee = '2012';
		}
		else
		{
			$date=date('Y-m-d');
			list($annee, $mois_A,$day) = explode("-", $date);
		}
		//echo $mois_A ;
		$fin_mois=date('Y-m-d',mktime(0,0,0,$mois_A+1,0,$annee));
		$debut_moiss=date('Y-m-d',mktime(0,0,0,$mois_A,1,$annee));
		$personne = new Default_Model_Personne();
		$reponse = $personne->obtenirColonnes($debut_moiss,$fin_mois);
		//echo $debut_moiss."</br>";
		//echo $fin_mois."</br>";
		var_dump($reponse);
		$month = 10;
		
	
	
	echo $_SESSION['salut']['mois'];*/
	/*	$mois_A = new Zend_Session_Namespace('salut',false);
	$mois_A->mois = 10;
		echo $_SESSION['salut']['mois'];
		$id_pole =4;
		$id_fonction=1;
		$id_entite = 2;
	 $fin_mois=date('Y-m-d',mktime(0,0,0,$_SESSION['salut']['mois']+1,0,'2012'));
	$debut_moiss=date('Y-m-d',mktime(0,0,0,$_SESSION['salut']['mois'],1,'2012'));
		$personne = new Default_Model_Personne();
		$set_personnes= $personne->fetchAll('id_pole ='.$id_pole .'&&'. 'id_fonction ='.$id_fonction .'&&'. 'id_entite ='.$id_entite );
		var_dump($set_personnes);
		$tableau_personnes = array();
		
		foreach($set_personnes as $p)
		{
			$tableau_personnes[] = $p->getId();
		}
		
				$reponse = $personne->obtenirresources($tableau_personnes,$debut_moiss,$fin_mois);
			
	var_dump($reponse);
}
	public function afficheAction()
	{
		
		$form = new Default_Form_ChoixMois;
		
		$this->view->form = $form;
		//$form->setAction($this->view->url(array( 'controller' => 'calendrier', 'action' => 'calendriermensuel'), 'default', true));
		if ($this->_request->ispost())
		{
			$tab = $this->_request->getPost() ;
			echo($tab['num_mois'])."</br>";
			if (array_key_exists('num_mois', $tab))
			echo "yahooooo";
			else 
			echo"pourquoiiiiiiiii";
			
			$mois_A = new Zend_Session_Namespace('salut',false);
			
	$mois_A->mois = 10;
		echo $_SESSION['salut']['mois'];
		}
		
	
	}*/
	
	/*public function afficheAction(){
				$date=date('Y-m-d');
			list($annee, $mois_A,$day) = explode("-", $date);
		
		 $mois_A ;
		 $fin_mois=date('Y-m-d',mktime(0,0,0,$mois_A+1,0,$annee));
		 $debut_moiss=date('Y-m-d',mktime(0,0,0,$mois_A,1,$annee));
		$personne = new Default_Model_Personne();
		$reponse = $personne->obtenirColonnes($debut_moiss,$fin_mois);
		 
		var_dump($reponse);
	
				$mois_A = new Zend_Session_Namespace('salut',false);
			
	$mois_A->mois = 9;
		echo $_SESSION['salut']['mois'];

}
	
	
	public function chercher_jours_feriers($debut_moiss,$fin_mois)
	{
	$date_debut = strtotime($debut_moiss );
    	$date_fin = strtotime($fin_mois );
		
    	$tableau_jours_feries = array(); // Tableau des jours feriés
    // On boucle dans le cas où l'année de départ serait différente de l'année d'arrivée
    	$difference_annees = date('Y', $date_fin) - date('Y', $date_debut);
 for ($i = 0; $i <= $difference_annees; $i++) 
    {
	    $annee = (int)date('Y', $date_debut) + $i;
	    // Liste des jours feriés
	    $tableau_jours_feries[] = '1_1_'.$annee; // Jour de l'an
	    $tableau_jours_feries[] = '11_1_'.$annee; // Anniversaire du manifeste de l'indépendance (1944)
	    $tableau_jours_feries[] = '1_5_'.$annee; // Fête du travail
	    $tableau_jours_feries[] = '30_7_'.$annee; // Fête du trône
	    $tableau_jours_feries[] = '14_8_'.$annee; // Journée de Oued Ed-Dahab
	    $tableau_jours_feries[] = '20_8_'.$annee; // Fête de la révolution du roi et du peuple 
	    $tableau_jours_feries[] = '21_8_'.$annee; // Anniversaire de sa majesté Henry je sais pas  vi
	    $tableau_jours_feries[] = '6 _11_'.$annee; // Anniversaire du roi
	    $tableau_jours_feries[] = '18_11_'.$annee; // Fête de l'indépendance
	    // Récupération de paques. Permet ensuite d'obtenir le jour de l'ascension et celui de la pentecote
	    $easter = easter_date($annee);
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + 86400); // Paques
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + (86400*39)); // Ascension
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + (86400*50)); // Pentecote
    }
    	return $tableau_jours_feries;
	}
	public function afficheAction()
	{$fin_mois ='2012-09-30';
		     $select = $this->select()->distinct()->setIntegrityCheck(false)
                    ->from(array('pr' => $this->_name), array('nom','prenom'))
                    ->joinInner(array('c' => 'conge'), 'c.id_personne =pr.id', array('date_debut','date_fin','nombre_jours','id_type_conge','mi_debut_journee','mi_fin_journee'))
                  	//->where('c.date_debut>= ?' ,$debut_moiss)
        			//->where('c.date_fin <= ?' ,$fin_mois)
        			->where("$fin_mois BETWEEN c.date_debut AND c.date_fin");
	
	var_dump($select);
	}
	
	
	
	
	public function afficheAction()
	{
				$id = 20;
				$conge1 = new Default_Model_Conge();
				$conge1 = $conge1 ->fetchall('id_personne = '.$id);
				
				
				if (count($conge1)!=0  ) 
				{
					$flag_save = false;
					foreach ($conge1 as $c ) 
					{
						$date_debut_base_conge = $c->getDate_debut();
						echo "la date debut sur la base est :".$date_debut_base_conge."</br>";
						$date_fin_base_conge = $c->getDate_fin();
						$date_mi_debut_base_conge = $c->getMi_debut_journee();
						$date_mi_fin_base_conge = $c->getMi_fin_journee();
						
					
						if ( (('2012-09-01' >= $date_debut_base_conge) &&('2012-09-01' <= $date_fin_base_conge))||(('2012-09-09' >= $date_debut_base_conge) && ('2012-09-09' <= $date_fin_base_conge)))
						{
							/*if ($form->getValue('mi_debut_journee')|| $form->getValue('mi_fin_journee'))
							{
								if ((('2012-09-01' == $date_debut_base_conge)&&('2012-09-09'  == $date_fin_base_conge))&&(('1'==$date_mi_debut_base_conge)||('0'==$date_mi_fin_base_conge)))
								{
									$flag_save =NULL;
									$form->populate($data);
									echo "<strong><em><span style='background-color:rgb(255,0,0)'>  conge deja demande</span></em></strong>";
									
								}
								else $flag_save =TRUE;
							}
							
							if (!($flag_save) )
							{
								$form->populate($data);
								echo "<strong><em><span style='background-color:rgb(255,0,0)'>  conge deja demande</span></em></strong>";
						
							}
						
							
						}
					 	
					}
					
			}
	}
	
	public function afficheAction ()
	
	{
		//$personne = new Default_Model_Personne();
		//$personne = $personne->fetchall('annee_reference==2012');
		$annee ='2012';
		$solde = new Default_Model_Solde();
		$solde=$solde->fetchall('annee_reference='.$annee);
		var_dump($solde );
		
	
		$id_personne = 37;
		$annee_reference ='2012';
		$date=date('Y-m-d');
		list($annee_debut, $mois_A_debut,$jour_debut ) = explode("-", $date);
		list($annee_fin, $mois_A_fin,$jour_fin ) = explode("-", $date);
		$debut_annee_reference = $annee_debut.'-01-01';
		$fin_annee_reference = $annee_debut.'-12-31';
		$nombre_jours =20;
		
		
		if ($this->_helper->validation->verifierSolde ($id_personne,$debut_annee_reference, $fin_annee_reference,$annee_reference,$nombre_jours))
		{
			echo "udgkjwgjkfes";
			 $this->view->var = false;

		}
		else $this->_helper->redirector('message');

	}*/
	
/*	public function afficheAction()
	{
	
		$fin_mois ='2012-10-31';
		$debut_moiss ='2012-10-01';
		$typeconge = new Default_Model_TypeConge();
		$result_set_types = $typeconge->fetchAll($str=array());
		$tableau_types = array();
		foreach($result_set_types as $p)
		{
			$tableau_types[$p->getId()] = $p->getCode();
		}
		
		$personne = new Default_Model_Personne();
		$conge = new Default_Model_Conge();
		$doublont = $conge->doublont($debut_moiss,$fin_mois);
		$nondoublont = $conge->CongesNondoublont( $debut_moiss,$fin_mois) ;
		
		$tableau_doublon = array();
		$tableau_non_doublon = array();
		
		for($i=0;$i<count($doublont);$i++)
		{
			$tableau_doublon[$i]= $doublont[$i]['id_personne'];
			$tableau_non_doublon[$i]= $nondoublont[$i]['id_personne'];
		}
		for($i=0;$i<count($nondoublont );$i++)
		{
			$tableau_non_doublon[$i]= $nondoublont[$i]['id_personne'];
		}
		
		$calendrier = array();
		
		if (count($tableau_non_doublon))
		{
			for($j=0;$j<count($tableau_non_doublon);$j++)
			{
				
				if (!(in_array($tableau_non_doublon[$j],$tableau_doublon)))
				{
				$reponse2 = $personne->obtenirresources($tableau_non_doublon[$j],$debut_moiss,$fin_mois);
				
						$t=0;
						$calendrier[$j][$t]['nom']=$reponse2[0]['nom'];
						$calendrier[$j][$t]['prenom']=$reponse2[0]['prenom'];
						$calendrier[$j][$t]['centre_service']=$reponse2[0]['centre_service'];
						$calendrier[$j][$t]['date_debut']=new Zend_Date($reponse2[0]['date_debut']);
						$calendrier[$j][$t]['date_fin']=new Zend_Date($reponse2[0]['date_fin']);
						$calendrier[$j][$t]['nombre_jours']=$reponse2[0]['nombre_jours'];
						$calendrier[$j][$t]['id_type_conge']=$reponse2[0]['id_type_conge'];
						$calendrier[$j][$t]['mi_debut_journee']=$reponse2[0]['mi_debut_journee'];
						$calendrier[$j][$t]['mi_fin_journee']=$reponse2[0]['mi_fin_journee'];
						
				}
				else 
				{
					$reponse2 = $personne->obtenirresources($tableau_non_doublon[$j],$debut_moiss,$fin_mois);
					for ($i=0;$i<count($reponse2);$i++)
					{
						$calendrier[$j][$i]['nom']=$reponse2[$i]['nom'];
						$calendrier[$j][$i]['prenom']=$reponse2[$i]['prenom'];
						$calendrier[$j][$i]['centre_service']=$reponse2[$i]['centre_service'];
						$calendrier[$j][$i]['date_debut']=new Zend_Date($reponse2[$i]['date_debut']);
						$calendrier[$j][$i]['date_fin']=new Zend_Date($reponse2[$i]['date_fin']);
						$resu =$conge->RecupererLeNombreConge( $tableau_non_doublon[$j],$reponse2[$i]['date_debut']);
						$calendrier[$j][$i]['nombre_jours']=$reponse2[$i]['nombre_jours'];
						if (($reponse2[$i]['date_fin']==$reponse2[$i]['date_fin']) && (count($resu)==2))
						{
							$type =array();
							$calendrier[$j][$i]['mi_fin_journee']=1;
							$calendrier[$j][$i]['nombre_jours']=1;
							for ($l=0;$l<count($resu);$l++)
							{
								$type[$l]=$resu[$l]['id_type_conge'];
							
								if (( $reponse2[$i]['id_type_conge']!= $type[$l] ) )
								{
									if ($reponse2[$i]['mi_debut_journee'] ==1)
									{
										$calendrier[$j][$i]['id_type_conge'] =$tableau_types[$reponse2[$i]['id_type_conge']];
										$calendrier[$j][$i]['id_type_conge2'] =$tableau_types[$type[$l]];
									}
									elseif($reponse2[$i]['mi_fin_journee'] ==1)
									{
										$calendrier[$j][$i]['id_type_conge'] =$tableau_types[$type[$l]];
										$calendrier[$j][$i]['id_type_conge2'] = $tableau_types[$reponse2[$i]['id_type_conge']] ;
									}
								}
							}	
						}
						else
						{
						$calendrier[$j][$i]['id_type_conge']=$reponse2[$i]['id_type_conge'];
						$calendrier[$j][$i]['mi_debut_journee']=$reponse2[$i]['mi_debut_journee'];
						$calendrier[$j][$i]['mi_fin_journee']=$reponse2[$i]['mi_fin_journee'];
						
					
						}
					}
				}
			}
			var_dump($calendrier) ;
		}
		
	}

	public function afficheAction()
	{
		$id_personne =5;
		for ($mois_A=1;$mois_A<=12;$mois_A++)
		{	
			$fin_mois=date('Y-m-d',mktime(0,0,0,$mois_A+1,0,$_SESSION['salut']['annee']));
			$debut_mois=date('Y-m-d',mktime(0,0,0,$mois_A,1,$_SESSION['salut']['annee']));
			$personne = new Default_Model_Personne();
			$reponse = $this->_helper->validation->calendrier($id_personne,$debut_mois,$fin_mois);
			$conge = new Default_Model_Conge();
			$propostion = new Default_Model_Proposition();
			$tableau_jours_feries=$conge->chercher_jours_feriers($debut_mois, $fin_mois);
			$ferie = new Default_Model_Ferie();
			$jours_feries_marocain = $ferie->RecupererLesJoursFeries( $_SESSION['salut']['annee']);
			$nb= count($jours_feries_marocain );
			$tableau = array();
			for ($i=0;$i<$nb;$i++)
			{
				$tableau[$i]=$jours_feries_marocain[$i]['date_debut'];
				
			}
					
			$vac= new Default_Model_Vacances();
			$vac = $vac->jours_vacances( $debut_mois, $fin_mois);
			$comp = 0;
			$resultat[$mois_A]['A']=array();
			$resultat[$mois_A]['B']=array();
			$resultat[$mois_A]['C']=array();
			for ($comp =0;$comp<count($vac);$comp++)
			{
				
				if ($vac[$comp]['date_debut']>=$debut_mois && $vac[$comp]['date_fin']<=$fin_mois)
				{
					$debut=	new Zend_Date($vac[$comp]['date_debut']);	
					$fin = new Zend_Date($vac[$comp]['date_fin']);
					$resultat [$mois_A][$vac[$comp]['zone']]=($this->_helper->validation->CompteurJours($debut,$fin));
				
				}
				elseif ($vac[$comp]['date_debut']< $debut_mois && $vac[$comp]['date_fin']<$fin_mois)
				{
					$fin = new Zend_Date($vac[$comp]['date_fin']);
					$debut =  new Zend_Date($debut_mois);
					$resultat[$mois_A][$vac[$comp]['zone']]=($this->_helper->validation->CompteurJours($debut,$fin));
				}
				elseif ($vac[$comp]['date_debut']< $fin_mois && $vac[$comp]['date_fin']>$fin_mois && $vac[$comp]['date_debut']> $debut_mois )
				{
					$fin =  new Zend_Date($fin_mois);
					$debut =  new Zend_Date($vac[$comp]['date_debut']);
					$resultat [$mois_A][$vac[$comp]['zone']]=($this->_helper->validation->CompteurJours($debut,$fin));
				}
				elseif ($vac[$comp]['date_debut']< $debut_mois && $vac[$comp]['date_fin']>$fin_mois)
				{
					$fin = new Zend_Date($fin_mois);
					$debut =  new Zend_Date($debut_mois);
					$resultat [$mois_A][$vac[$comp]['zone']]=($this->_helper->validation->CompteurJours($debut,$fin));
				}
					
			}
			
			
			$timestamp = mktime (0, 0, 0,$mois_A, 01, $_SESSION['salut']['annee']);
			 $nombreDeJoursDeMois = intval(date("t",$timestamp));
			
			
			$feries=array();
			$k=1;
			$var = strtotime($debut_mois );
			for($k==1;$k<=$nombreDeJoursDeMois;$k++)
			{
				
				
				if(in_array(date('w', $var), array(0, 6)))
				{
					$feries[$k]='<div class="colorgris">WE</div>';
				}
				elseif((((in_array(date('j_n_'.date('Y', $var), $var), $tableau_jours_feries))&&($reponse[0][0]['centre_service']== 0))||((in_array(date(date('Y', $var).'-m-d', $var), $tableau))&&($reponse[0][0]['centre_service']==1))) && !(in_array(date('w', $var), array(0, 6))) ) 
				{
					if ((in_array(date('j_n_'.date('Y', $var), $var), $tableau_jours_feries))&&(in_array(date(date('Y', $var).'-m-d', $var), $tableau)))
					{
						$feries[$k] = '<div class="FC">FC</div>';
						
					}
					if((in_array(date('j_n_'.date('Y', $var), $var), $tableau_jours_feries))&&($reponse[0][0]['centre_service']==0))
					{
						$feries[$k]=  '<div class="FF"> FF</div>';
						
					}
					if((in_array(date(date('Y', $var).'-m-d', $var), $tableau))&&($reponse[0][0]['centre_service']==1))
					{
						$feries[$k]=   '<div class="FF">FM</div>';
						
					}
				}
			
			 $var = mktime(date('H', $var), date('i', $var), date('s', $var), date('m', $var), date('d', $var)+1, date('Y', $var));
			}
			
			
			$i=0;
			$t=0;
			$tab =array();
			$conge_type =array();
			for($i==0;$i<count ($reponse[0]);$i++) 
			{
				$date1[$i] = $reponse[0][$i]['date_debut']->get(Zend_Date::DAY);
				$date2[$i] = $reponse[0][$i]['date_fin']->get(Zend_Date::DAY);
				$type_conge[$i]= $reponse[0][$i]['id_type_conge'];
				$mi_debut_journee[$i]= $reponse[0][$i]['mi_debut_journee'];
				$mi_fin_journee[$i]= $reponse[0][$i]['mi_fin_journee'];
				
				$j=0;
				for($j==1;$j<=($date2[$i]-$date1[$i]);$j++)
				{
					$tab[$t]=  $date1[$i]+$j;
					
					if ($mi_debut_journee[$i]==1 && $mi_fin_journee[$i]==0)
					{
						$conge_type[$t]="<div class=".$type_conge[$i]."_1>".$type_conge[$i]."</div>";
					}
					elseif ($mi_debut_journee[$i]==0 && $mi_fin_journee[$i]==1)
					{
						$conge_type[$t]="<div class=".$type_conge[$i]."_2>".$type_conge[$i]."</div>";
					}
					elseif ($mi_debut_journee[$i]==1 && $mi_fin_journee[$i]==1)
					{
						$type_conge[$i]= "<div class=".$reponse[0][$i]['id_type_conge']."_1>".$reponse[0][$i]['id_type_conge']."</div>"."<div class=".$reponse[0][$i]['id_type_conge2']."_22>".$reponse[0][$i]['id_type_conge2']."</div>";
						$conge_type[$t]= $type_conge[$i];
					}
					elseif($mi_debut_journee[$i]==0 && $mi_fin_journee[$i]==0) {$conge_type[$t]="<div class=".$type_conge[$i].">".$type_conge[$i]."</div>";}
					$t++;
				
				}
			}
			$resultat[$mois_A]['conge']=array_flip($tab);
			$resultat[$mois_A]['type_conge']= $conge_type;
			$resultat[$mois_A]['feries']= $feries;
		}
		
		$this->view->calendrierArray =  $resultat;
		
	}
	
/*	public function afficheAction()
	{
		$mois_A =8;
		$fin_mois=date('Y-m-d',mktime(0,0,0,$mois_A+1,0,$_SESSION['salut']['annee']));
		$debut_mois=date('Y-m-d',mktime(0,0,0,$mois_A,1,$_SESSION['salut']['annee']));
	 	
		
		$vac= new Default_Model_Vacances();
		$vac = $vac->jours_vacances( $debut_mois, $fin_mois);
		$comp = 0;
		$tab=array();
		for ($comp =0;$comp<count($vac);$comp++)
		{
			if ($vac[$comp]['date_debut']>=$debut_mois && $vac[$comp]['date_fin']<=$fin_mois)
			{
				$debut=	new Zend_Date($vac[$comp]['date_debut']);	
				$fin = new Zend_Date($vac[$comp]['date_fin']);
				$tab [$mois_A][$vac[$comp]['zone']]=($this->_helper->validation->CompteurJours($debut,$fin));
			
			}
			elseif ($vac[$comp]['date_debut']< $debut_mois && $vac[$comp]['date_fin']<$fin_mois)
			{
				$fin = new Zend_Date($vac[$comp]['date_fin']);
				$debut =  new Zend_Date($debut_mois);
				$tab [$mois_A][$vac[$comp]['zone']]=($this->_helper->validation->CompteurJours($debut,$fin));
			}
			elseif ($vac[$comp]['date_debut']< $fin_mois && $vac[$comp]['date_fin']>$fin_mois && $vac[$comp]['date_debut']> $debut_mois )
			{
				$fin =  new Zend_Date($fin_mois);
				$debut =  new Zend_Date($vac[$comp]['date_debut']);
				$tab [$mois_A][$vac[$comp]['zone']]=($this->_helper->validation->CompteurJours($debut,$fin));
			}
			elseif ($vac[$comp]['date_debut']< $debut_mois && $vac[$comp]['date_fin']>$fin_mois)
			{
				$fin = new Zend_Date($fin_mois);
				$debut =  new Zend_Date($debut_mois);
				$tab [$mois_A][$vac[$comp]['zone']]=($this->_helper->validation->CompteurJours($debut,$fin));
			}
					
		}
		var_dump($tab);
		
	}*/
//	public function afficheAction()
//	{
//		$form = new Default_Form_Personne();
//		$form->setAction($this->view->url(array('controller' => 'personne', 'action' => 'create'), 'default', true));
//		$form->submit_pr->setLabel('Ajouter');
//		$this->view->form = $form;
//		if($this->_request->isPost())
//		{
//			if ($form->getValue('centre_service_pr') == 1)
//			{
//				$form = $form->getDisplayGroup> ('login');
//			}
			
//		}
		
	
	
//	}
	
	
//}	
}