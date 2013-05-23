<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Model_Conge
{
	//variables correspondant à chacun des champs de notre table users
	protected  $_id;
	protected  $_id_personne;
	protected  $_id_proposition;
	protected  $_date_debut;
	protected  $_mi_debut_journee;
	protected  $_date_fin;
	protected  $_mi_fin_journee;
	protected  $_nombre_jours;
	protected  $_id_type_conge;
	protected  $_annee_reference;
	protected  $_ferme;

	//le mapper va nous fournir les méthodes pour interagir avec notre table (objet de type Default_Model_CongeMapper)
	protected $_mapper;

	//constructeur
	//le tableau d'options peut contenir les valeurs des champs à utiliser
	//pour l'initialisation de l'objet
	public function __construct(array $options = null)
	{
		if (is_array($options)) 
		{
			$this->setOptions($options);
		}
	}

	//cette méthode permet d'appeler n'importe quel settor en fonction
	//des arguments
	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) 
		{
			throw new Exception('Invalid guestbook property');
		}
		$this->$method($value);
	}

	//cette méthode permet d'appeler n'importe quel gettor en fonction
	//du nom passé en argument
	public function __get($name)
	{
		$method = 'get' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) 
		{
			throw new Exception('Invalid guestbook property');
		}
		return $this->$method();
	}

	//permet de gérer un tableau d'options passé en argument au constructeur
	//ce tabelau d'options peut contenir la valeur des champs à utiliser
	//pour l'initialisation de l'objet
	public function setOptions(array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) 
		{
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) 
			{
				$this->$method($value);
			}
		}
		return $this;
	}

	//gettors and settors d'accès aux variables
	public function setId($id)
	{
		$this->_id = (int)$id;
		return $this;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function setId_personne($id_personne)
	{
		$this->_id_personne = (int)$id_personne;
		return $this;
	}
	public function getId_personne()
	{
		return $this->_id_personne;
	}
	
	public function setId_proposition($id_proposition)
	{
		$this->_id_proposition = $id_proposition;
		return $this;
	}
	public function getId_proposition()
	{
		return $this->_id_proposition;
	}

	public function setDate_debut($date_debut)
	{
		$this->_date_debut = $date_debut;
		return $this;
	}
	public function getDate_debut()
	{
		return $this->_date_debut;
	}

	public function setMi_debut_journee($mi_debut_journee)
	{
		$this->_mi_debut_journee = $mi_debut_journee;
		return $this;
	}
	public function getMi_debut_journee()
	{
		return $this->_mi_debut_journee;
	}

	public function setDate_fin($date_fin)
	{
		$this->_date_fin = $date_fin;
		return $this;
	}

	public function getDate_fin()
	{
		return $this->_date_fin;
	}

	public function setMi_fin_journee($mi_fin_journee)
	{
		$this->_mi_fin_journee = $mi_fin_journee;
		return $this;
	}
	public function getMi_fin_journee()
	{
		return $this->_mi_fin_journee;
	}
	
	/* Mohamed khalil TAKAFI */
	public function setNombre_jours()  // MTA : calcul nombre de jours Congé
	{
		$date_debut = new DateTime($this->getDate_debut());    // date_debut 
    	$date_fin = new DateTime($this->getDate_fin());        // date_fin
    	
    	$date_depart =  date_timestamp_get($date_debut);
    	$annee = (string)date('Y', $date_depart);              // année 
	    
        $debut_midi = $this->getMi_debut_journee();            // debut midi 
        $fin_midi = $this->getMi_fin_journee();                // fin midi 

        $maroc = false;                                        // France 
        
        $utils = new Default_Controller_Helpers_Validation();

        // mettre les jours fériés maroc dans session TEST 
		$jours_feries_maroc = new Zend_Session_Namespace('TEST',false);
        $jours_feries_maroc->jfm = $utils->jours_feries_maroc($annee);
        
        //MTA :  verifié si c'est un CSM ou France     
        $personne = new Default_Model_Personne();
        $per = $personne->find($this->getId_personne());
       
        
        if ($per->getEntite()->getCs() == 1) 
        {
		 $maroc = true;     // maroc 
        }
    
        $this->_nombre_jours = $utils->calculer_jours_conges($date_debut, $date_fin,$debut_midi,$fin_midi,$maroc);
		return $this;  
	}
	
	
	
	public function  getNombre_jours()
	{
		return $this->_nombre_jours;
	}

	public function setId_type_conge($id_type_conge)
	{
		$this->_id_type_conge = (int)$id_type_conge;
		return $this;
	}
	public function  getId_type_conge()
	{
		return $this->_id_type_conge;
	}

	public function setAnnee_reference($annee_reference)
	{
			return $this->_annee_reference = (int)$annee_reference;
	}
	public function  getAnnee_reference()
	{
		return $this->_annee_reference;
	}

	public function setFerme($ferme)
	{
		$this->_ferme = $ferme;
		return $this;
	}

	public function  getFerme()
	{
		return $this->_ferme;
	}



	public function setMapper($mapper)
	{
		$this->_mapper = $mapper;
		return $this;
	}
	public function getMapper()
	{
		//si la valeur $_mapper n'est pas initialisée, on l'initialise (
		if(null == $this->_mapper)
		{
			$this->setMapper(new Default_Model_CongeMapper());
		}

		return $this->_mapper;
	}

	//méthodes de classe utilisant les méthodes du mapper
	//crée ou met à jour une entrée dans la table
	public function save()
	{
		$this->getMapper()->save($this);
	}

	//récupère une entrée particulière
	public function find($id)
	{
		$this->getMapper()->find($id, $this);
		return $this;
	}

	//récupère toutes les entrées de la table
	public function fetchAll($str)
	{
		return $this->getMapper()->fetchAll($str);
	}

	//permet la suppression
	public function delete($id)
	{
		$this->getMapper()->delete($id);
	}
	public function somme($id,$annee_reference) 
    {
    	return $this->getMapper()->somme($id,$annee_reference);
    }
    public function selctid() 
    {
    	return $this->getMapper()->selctid();
    }
    public function somme_solde_annuel_confe($id_personnes, $debut_annee, $fin_annee)
    {
    	return $this->getMapper()->somme_solde_annuel_confe($id_personnes, $debut_annee, $fin_annee);
    }
    
    public function doublont( $debut_annee, $fin_annee)
    {
    	return $this->getMapper()->doublont(  $debut_annee, $fin_annee);
    }
   
    public function  DateDebutMin($id,$debut_mois,$fin_mois)
    {
    	return $this->getMapper()-> DateDebutMin($id,$debut_mois,$fin_mois);
    }
    
    public function DateFinMax($id,$debut_mois,$fin_mois)
    {
    	return $this->getMapper()-> DateFinMax($id,$debut_mois,$fin_mois);
    }
    public function CongesNondoublont( $debut_mois,$fin_mois) 
 	{
    	return $this->getMapper()-> CongesNondoublont( $debut_mois,$fin_mois) ;
    }
     public function RecupererLeNombreConge( $id_personne,$date_debut)
 	{
    	return $this->getMapper()-> RecupererLeNombreConge( $id_personne,$date_debut) ;
    }
   
  	 public function  DoublontAuNiveauPole( $tableau_id, $debut_mois,  $fin_mois)
 	{
    	return $this->getMapper()->DoublontAuNiveauPole( $tableau_id, $debut_mois,  $fin_mois) ;
    }
    
 	public function  CongesNondoublontPole( $tableau_id,$debut_mois,$fin_mois) 
 	{
    	return $this->getMapper()->CongesNondoublontPole( $tableau_id,$debut_mois,$fin_mois)  ;
    }
  
	
/*
	 * calcule de nombre de jours ouvres dans un mois  
	 */

	public function joursOuvresDuMois($debut_mois,$fin_mois)
	{
		// tu peut utiliser cette fonction pour afficher les nombre totale ouvere pour un mois donné

		$date_debut = strtotime($debut_mois );
    	$date_fin = strtotime($fin_mois );
		
    	$tableau_jours_feries = array(); // Tableau des jours feriés
    // On boucle dans le cas où l'année de départ serait différente de l'année d'arrivée
    	$difference_annees = date('Y', $date_fin) - date('Y', $date_debut);
    for ($i = 0; $i <= $difference_annees; $i++) 
    {
	    $annee = (int)date('Y', $date_debut) + $i;
	    // Liste des jours feriés
	    $tableau_jours_feries[] = '1_1_'.$annee; // Jour de l'an
	    $tableau_jours_feries[] = '1_5_'.$annee; // Fete du travail
	    $tableau_jours_feries[] = '8_5_'.$annee; // Victoire 1945
	    $tableau_jours_feries[] = '14_7_'.$annee; // Fete nationale
	    $tableau_jours_feries[] = '15_8_'.$annee; // Assomption
	    $tableau_jours_feries[] = '1_11_'.$annee; // Toussaint
	    $tableau_jours_feries[] = '11_11_'.$annee; // Armistice 1918
	    $tableau_jours_feries[] = '25_12_'.$annee; // Noel
	    // Récupération de paques. Permet ensuite d'obtenir le jour de l'ascension et celui de la pentecote
	    $easter = easter_date($annee);
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + 86400); // Paques
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + (86400*39)); // Ascension
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + (86400*50)); // Pentecote
    }
    //print_r($tableau_jours_feries);
    $nb_jours_ouvres = 0;
    // Mettre <= si on souhaite prendre en compte le dernier jour dans le décompte
    while ($date_debut <= $date_fin) 
    {
    // Si le jour suivant n'est ni un dimanche (0) ou un samedi (6), ni un jour férié, on incrémente les jours ouvrés
	    if (!in_array(date('w', $date_debut), array(0, 6)) && !in_array(date('j_n_'.date('Y', $date_debut), $date_debut), $tableau_jours_feries)) 
	    {
	    	$nb_jours_ouvres++;
	    }
	    	$date_debut = mktime(date('H', $date_debut), date('i', $date_debut), date('s', $date_debut), date('m', $date_debut), date('d', $date_debut) + 1, date('Y', $date_debut));
	}

		return $nb_jours_ouvres; 
	}

	
	
	/*
	 * cette fonction a pour role de chercher les jours feries d'un mois 
	 * elle est utilisée afin de valoriser les jours feries au niveau du 
	 * calendrier mensuel
	 */
	
	
	public function chercher_jours_feriers($debut_mois,$fin_mois)
	{
	$date_debut = strtotime($debut_mois );
    $date_fin = strtotime($fin_mois );
		
    	$tableau_jours_feries = array(); // Tableau des jours feriés
    // On boucle dans le cas où l'année de départ serait différente de l'année d'arrivée
    	$difference_annees = date('Y', $date_fin) - date('Y', $date_debut);
 for ($i = 0; $i <= $difference_annees; $i++) 
    {
	    $annee = (int)date('Y', $date_debut) + $i;
	    // Liste des jours feriés
	    $tableau_jours_feries[] = '1_1_'.$annee; // Jour de l'an
	    $tableau_jours_feries[] = '1_5_'.$annee; // Fete du travail
	    $tableau_jours_feries[] = '8_5_'.$annee; // Victoire 1945
	    $tableau_jours_feries[] = '14_7_'.$annee; // Fete nationale
	    $tableau_jours_feries[] = '15_8_'.$annee; // Assomption
	    $tableau_jours_feries[] = '1_11_'.$annee; // Toussaint
	    $tableau_jours_feries[] = '11_11_'.$annee; // Armistice 1918
	    $tableau_jours_feries[] = '25_12_'.$annee; // Noel
	    // Récupération de paques. Permet ensuite d'obtenir le jour de l'ascension et celui de la pentecote
	    $easter = easter_date($annee);
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + 86400); // Paques
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + (86400*39)); // Ascension
	    $tableau_jours_feries[] = date('j_n_'.$annee, $easter + (86400*50)); // Pentecote
    }
    	return $tableau_jours_feries;
	}
    
	
	
/////////////////////////////// Fonction vérifier chevauchement des congés	///////////////////////////////////
	public function verifier_chevauchement($conge)
    {
      // recupérer les valeurs à inserer (Formulaire congés) 
	  $c__id_personne = $conge->getId_personne();
	  $c__date_debut = $conge->getDate_debut();
	  $c__date_fin =  $conge->getDate_fin();
	  $c__debut_midi = $conge->getMi_debut_journee();
	  $c__fin_midi = $conge->getMi_fin_journee();
	 
	  // chargé les congés d'une ressource donnée 
	  $c = new Default_Model_DbTable_Conge();
	  $c__tab = $c->conges_par_ressource($c__id_personne);

	  if($c__tab == null)      // si la ressource n'a pas de congés 
	  {
	    $res = 1;              // code succès 
	  }
	  else                     // sinon 
	  {
		  // boucler sur les congés de cette personne 
		  foreach($c__tab as $k => $v)
		  {
		      // pour chaque congé on loge ses valeurs dans les variables 
			  $B__id_personne = $c__tab[$k]['id_personne'];
			  $B__date_debut = $c__tab[$k]['date_debut'];
			  $B__date_fin =  $c__tab[$k]['date_fin'];
			  $B__debut_midi = $c__tab[$k]['mi_debut_journee'];
			  $B__fin_midi = $c__tab[$k]['mi_fin_journee'];
		      	
		      // le conge existe et identique dans la base de données 
		      if(($c->conges_indentique($c__id_personne,$c__date_debut,$c__date_fin ,$c__debut_midi,$c__fin_midi)) <> NULL)
			  {return -1 ;}
			  else // congé existe et non identique 
			  {     
			  	    // vérifier que les données saisies existe dans la base de donnée ( avec conditions flag == 0 )
			           $existe_0 = $c->conges_existant($c__id_personne,$c__date_debut,$c__date_fin ,0);
			        // vérifier que les données saisies existe dans la base de donnée ( avec conditions flag == 1 )
			           $existe_1 = $c->conges_existant($c__id_personne,$c__date_debut,$c__date_fin ,1);      
			                
					// conge ayant date_debut <> date_fin 
					if( $B__date_debut <> $B__date_fin )
					{
						     // si le congé à    debut_midi[0]		 et       Fin_midi[0]    
						     if($B__debut_midi == 0  && $B__fin_midi == 0 ) 
							{    
							     if($existe_0 == null)  // donnée saisie n'existe pas 
								 {return  2;}           // code succès 
							     else                   // donnée saisie existe 
								 {return -2 ;}          // code erreur 		 
							}

							// si le congé à   debut_midi[1] et  fin_midi[0]   ou   debut_midi[0] et  fin_midi[1]   ou   debut_midi[1] et  fin_midi[1]        
							 if($B__debut_midi == 1  ||  $B__fin_midi == 1)   
						    { 
						    	//     Base données :      d_d 2013-05-05   d_m[1]  f_m[0]                    05___________10
						    	//     Formulaire   :      d_f 2013-05-05   d_m[0]  f_m[1]       01___________05
						    	if($c__date_fin == $B__date_debut  &&  $c__fin_midi  == 1 && $B__debut_midi == 1)
						    	{    
						    		if($existe_1 == null)  // donnée saisie n'existe pas 
						    		 {return  3;}          // code succès 
						    		else                   // donnée saisie existe 
						    		{$res = -3;}           // code erreur 		   
						    	}
						    	//     Base données :      d_f 2013-05-05   d_m[0]  f_m[1]              01___________05     
						    	//     Formulaire   :      d_d 2013-05-05   d_m[1]  f_m[0]                           05__________10 
						    	elseif($B__date_fin == $c__date_debut  &&  $B__fin_midi  == 1 && $c__debut_midi == 1)
						    	{  
						         	if($existe_1 == null)   // donnée saisie n'existe pas 
						    	    {return  3;}            // code succès
						    		else                    // donnée saisie existe 
						    		{$res = -3;}            // code erreur
						    	}
						    	else
						    	{   			    		
						    	    if($existe_0 == null)   // donnée saisie n'existe pas
						    		{return  3;}            // code succès
						    		else                    // donnée saisie existe
						    		{return -3;}            // code erreur	
						    	}
						    }
						}
			}
			// conge ayant date_debut == date_fin 
			if($B__date_debut == $B__date_fin )
			{   
				// 1 jour 
				// si le congé à    debut_midi[0]		 et       Fin_midi[0]  
				if($B__debut_midi == 0  && $B__fin_midi == 0 ) 
				{     
					 if($existe_0 == null)  // donnée saisie n'existe pas
					 {return 4;}        // code succès
					 else                   // donnée saisie existe
					 {$res = -4;}       // code erreur
				} 
					    
				// 1/2 journee 
				// si le congé à    debut_midi[1]    et   Fin_midi[0]          ou            debut_midi[0]    et   Fin_midi[1]
				if($B__debut_midi == 1  && $B__fin_midi == 0  ||  $B__debut_midi == 0 && $B__fin_midi == 1)  
				{   
					 // formulaire debut_debut = date_fin
					if($c__date_debut == $c__date_fin )
					{   
						//formulaire: fin_midi[1] et  BD: debut_midi[1]       ou       formulaire: debut_midi[1] et  BD: fin_midi[1]
						if ((($c__fin_midi == 1 && $B__debut_midi == 1) || ($c__debut_midi == 1 && $B__fin_midi == 1)) )
						{return 5;}        // code succés 
						else
						{     
							if($existe_0 == null) // données saisies existent 
							{return 5;}         // code succés 
							else 
							{$res =  -5;}       // code d'erreur         
						}			  
					}
					// formulaire debut_debut <> date_fin
					if($c__date_debut <> $c__date_fin )      
		            {   
		               //formulaire: fin_midi[1] et  BD: debut_midi[1]       ou       formulaire: debut_midi[1] et  BD: fin_midi[1]
					   if (($c__fin_midi == 1 && $B__debut_midi == 1) || ($c__debut_midi == 1 && $B__fin_midi == 1))
					   {return 6;}   // code succés
					   else
					   {     
					       if($existe_0 == null) // données saisies existent 
						   {return 6;}          // code succés 
						   else 
					       {$res = -6;}       // code d'erreur 			        
					   }
			
					}
		       }
			 }
		   }
         }
		return $res;    // resultat de la fonction 
     } 
}
///////////////////////////////FIN Fonction vérifier chevauchement des congés	///////////////////////////////////
