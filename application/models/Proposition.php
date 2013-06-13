<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Model_Proposition
{
	//variables correspondant à chacun des champs de notre table proposition
	protected  $_id;
	protected  $_id_personne;
	protected  $_date_debut;
	protected  $_mi_debut_journee;
	protected  $_date_fin;
	protected  $_mi_fin_journee;
	protected  $_nombre_jours;
	protected  $_etat;

	//le mapper va nous fournir les méthodes pour interagir avec notre table (objet de type Default_Model_PropositionMapper)
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
			throw new Exception('Invalid guestbook property '.$name);
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
			throw new Exception('Invalid guestbook property '.$name);
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
	public function setNombre_jours()   //MTA : calcul nombre de jours proposition 
	{
		$date_debut = new DateTime($this->getDate_debut());    // date_debut 
    	$date_fin = new DateTime($this->getDate_fin());        // date_fin
    	
    	$date_depart =  date_timestamp_get($date_debut);
    	$annee = (string)date('Y', $date_depart);              // année 
	    
        $debut_midi = $this->getMi_debut_journee();            // debut midi 
        $fin_midi = $this->getMi_fin_journee();                // fin midi 

		$utils = new Default_Controller_Helpers_Validation();
		
		 // mettre les jours fériés maroc dans session TEST 
		$jours_feries_maroc = new Zend_Session_Namespace('TEST',false);
        $jours_feries_maroc->jfm = $utils->jours_feries_maroc($annee);
		
        $this->_nombre_jours = $utils->calculer_jours_conges($date_debut, $date_fin,$debut_midi,$fin_midi,true);
        
		return $this;
	}
	public function  getNombre_jours()
	{
		return $this->_nombre_jours;
	}

	public function setEtat($etat)
	{
		$this->_etat = (string)$etat;
		return $this;
	}

	public function  getEtat()
	{
		return $this->_etat;
	}



	public function setMapper($mapper)
	{
		$this->_mapper = $mapper;
		return $this;
	}
	public function getMapper()
	{
		//si la valeur $_mapper n'est pas initialisée, on l'initialise (
		if(null == $this->_mapper){
			$this->setMapper(new Default_Model_PropositionMapper());
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

	public function joursOuvresDuMois($debut_mois,$fin_mois)
	{

		// tu peut utiliser cette fonction pour afficher les nombre totale ouvere pour un mois donné
    	$date_debut = strtotime($debut_mois);
    	$date_fin = strtotime($fin_mois);

    	$tableau_jours_feries = array(); // Tableau des jours feriés
	    $annee = (int)date('Y', $date_debut);
	    $feris = new Default_Model_Ferie();
	    $tableau_jours_feries = $feris->RecupererLesJoursFeries($annee);
		$nb= count($tableau_jours_feries );
		$tableau = array();
	
		for ($i=0;$i<$nb;$i++)
		{
			$tableau[$i]=$tableau_jours_feries[$i]['date_debut'];
			
		}
   		 $nb_jours_ouvres = 0;
    // Mettre <= si on souhaite prendre en compte le dernier jour dans le décompte
    while ($date_debut <= $date_fin) 
    {
    // Si le jour suivant n'est ni un dimanche (0) ou un samedi (6), ni un jour férié, on incrémente les jours ouvrés
	    if (!in_array(date('w', $date_debut), array(0, 6)) && !in_array(date(date('Y', $date_debut).'-n-j', $date_debut),$tableau)) 
	    {
	    	$nb_jours_ouvres ++;
	    }
	    	$date_debut = mktime(date('H', $date_debut), date('i', $date_debut), date('s', $date_debut), date('m', $date_debut), date('d', $date_debut) + 1, date('Y', $date_debut));
	}
		return $nb_jours_ouvres;
	}
	
	
/*
 * MTA  
 */

/////////////////////////////// DEBUT Fonction Normaliser date_debut et date_fin ///////////////////////////////////
public function normaliser_dates($date_debut,$date_fin)
{
	 $outil = new Default_Controller_Helpers_Validation();
	 
	 if($date_debut <> $date_fin )
	 {
			  $d_d = new DateTime($date_debut);
			  $d_f = new DateTime($date_fin);
                 
			  // si date_debut = férié ou week      et       date_fin = férié ou week   on les décale tt les deux en avant 
			  if(($outil->est_ferie(date_format($d_f,'Y-m-d H:i:s'),false,false) || in_array(date_format($d_f, 'l'),array('Saturday','Sunday')) && ($outil->est_ferie(date_format($d_d, 'Y-m-d H:i:s'),false,false)) || in_array(date_format($d_d, 'l'),array('Saturday','Sunday'))))
			  {
			     $dd = $outil->normaliser_date_debut_conge($d_d,false);
			     $df = $outil->normaliser_date_debut_conge($d_f,false);
			     $tab[0] = $dd->format('Y-m-d H:i:s');
				 $tab[1] = $df->format('Y-m-d H:i:s');
			  }
			  else 
			  {
				  $dd = $outil->normaliser_date_debut_conge($d_d,false);
				  $df = $outil->normaliser_date_fin_conge($d_f,false);
				  $tab[0] = $dd->format('Y-m-d H:i:s');
				  $tab[1] = $df->format('Y-m-d H:i:s');
			  }
	}
	else 
    {
		      $d_d = new DateTime($date_debut);
			  $dd = $outil->normaliser_date_debut_conge($d_d,false);
		   
		      $tab[0] = $dd->format('Y-m-d H:i:s');
			  $tab[1] = $tab[0];
	}
		                  	    
	 return $tab; 
	
}
///////////////////////////////FIN Fonction Normaliser date_debut et date_fin ///////////////////////////////////
	

////////////////////////////// reglage des dates en fonction des demis journées ////////////////////////////////
public function makeDatetime($date_debut,$date_fin,$debut_midi,$fin_midi) 
{
	$date_deb = new DateTime($date_debut);
    $date_fi = new DateTime($date_fin);

	// gerer les datetimes 			
	if($debut_midi == 1)
	{   
		// ajouter 12h00m00s à la date 00:00:00
		$date_deb =  $date_deb->add(new DateInterval('PT12H00M00S'));				    
	} 
							    
	if($fin_midi == 1)
	{
		 // ajouter 11h59m59s à la date 00:00:00
		$date_fi =   $date_fi->add(new DateInterval('PT11H59M59S'));
			     			    
	}
	else //  $fin_midi == 0
	{
		// ajouter 23h59m59s à la date 00:00:00
		$date_fi =  $date_fi->add(new DateInterval('PT23H59M59S'));
	}
	
	 $date[0] = $date_deb->format('Y-m-d H:i:s');
	 $date[1] = $date_fi->format('Y-m-d H:i:s');
		
	 return $date;
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
