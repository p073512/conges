<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Model_Solde
{
	//variables correspondant à chacun des champs de notre table users
	protected  $personne;
	protected  $_total_q1;
	protected  $_total_q2;
	protected  $_total_cp;
	protected  $_annee_reference;
	
	
	
	
	//le mapper va nous fournir les méthodes pour interagir avec notre table (objet de type Default_Model_PersonneMapper)
	protected $_mapper;

	//constructeur
	//le tableau d'options peut contenir les valeurs des champs à utiliser
	//pour l'initialisation de l'objet
	public function __construct(array $options = null)
	{
		$this->personne = new Default_Model_Personne();
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}

	//cette méthode permet d'appeler n'importe quel settor en fonction
	//des arguments
	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
			throw new Exception('Invalid guestbook property '.$name);
		}
		$this->$method($value);
	}

	//cette méthode permet d'appeler n'importe quel gettor en fonction
	//du nom passé en argument
	public function __get($name)
	{
		$method = 'get' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
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
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}
		return $this;
	}

	//gettors and settors d'accès aux variables
	public function setPersonne($personne)
	{
		if($personne instanceof Default_Model_Personne)
			{
				$this->personne = $personne;
			}
			else 
			{
				$this->personne = $this->personne->find((int) $personne);
			}
		
	   return $this;
	}

	public function getPersonne()
	{
		return $this->personne ;
	}

	public function setTotal_q1($modalite)
	{
		$this->_total_q1 = $this->totalq1($modalite);
		return $this;
	}

	public function  getTotal_q1()
	{
		return $this->_total_q1;
	}
	
	
	public function setTotal_q2($nb)
	{
		$this->_total_q2 = $nb;
		return $this;
	}

	public function  getTotal_q2()
	{
		return $this->_total_q2;
	}
	
	
	public function setTotal_cp()
	{
		$this->_total_cp = (float)$this->totalcp();
		return $this;
	}

	public function  getTotal_cp()
	{
		return $this->_total_cp;
	}
	
	
	public function setAnnee_reference()
	{
		$date=date('d/m/Y');
		list($jour_actuel, $mois_actuel, $annee_actuelle) = explode("/", $date);
		$this->_annee_reference = (int)$annee_actuelle;
		return $this;
	}


	public function  getAnnee_reference()
	{
		return $this->_annee_reference;
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
			$this->setMapper(new Default_Model_SoldeMapper());
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
	public function find($annee_reference,$idPersonne)
	{
		$this->getMapper()->find($annee_reference,$idPersonne,$this);
		return $this;
	}
	public function find2($annee_reference)
	{
		$this->getMapper()->find2($annee_reference, $this);
		return $this;
	}

	//récupère toutes les entrées de la table
	public function fetchAll($str)
	{
		return $this->getMapper()->fetchAll($str);
	}
	public function fetchAll2($str)
	{
		return $this->getMapper()->fetchAll2($str);
	}
	
	//permet la suppression
	public function delete($id_personne)
	{
		$this->getMapper()->delete($id_personne);
	}
	// cherche le nombre de ressources existant de la table conge REMARQUE remplace la date de debut
	public function totalcp()
	{
		$date=date('d/m/Y');
		list($jour_actuel, $mois_actuel, $annee_actuelle) = explode("/", $date);
		$date_entree = $this->personne->getDate_entree();
		list( $annee_entree, $mois_entree,$jour_entree) = explode("-", $date_entree);
		$cemule_annees =$annee_actuelle - $annee_entree ;
		$supplument_anciennete =0;
		if (01<=$mois_entree && $mois_entree <=05 && ($annee_actuelle -1 >= $annee_entree)  )
		{
			if($cemule_annees >=2  )
			{
				$supplument_anciennete = 1;
			}
			if ($cemule_annees >=3 )
			{
				$supplument_anciennete = 2;
			}
			if ($cemule_annees >=5 )
			{
				$supplument_anciennete = 3;
			}
			if ($cemule_annees >=8 )
			{
				$supplument_anciennete = 4;
			}
		
		}
		
		if (06<=$mois_entree && $mois_entree <=12 && $cemule_annees   >= 3)
		{
			if($cemule_annees >=3)
			{
				$supplument_anciennete = 1;
			}
			if ($cemule_annees >=4)
			{
				$supplument_anciennete = 2;
			}
			if ($cemule_annees >=6)
			{
				$supplument_anciennete = 3;
			}
			if ($cemule_annees >=9)
			{
				$supplument_anciennete = 4;
			}
		}
		
			if ( $jour_entree <15)
			{
			$cp = (12-$mois_entree)* 2.25;
			}
			else 
			{
				$cp = (11-$mois_entree)* 2.25;
			}
			return $cp_total = $cp + $supplument_anciennete;
	}
	
	/*
	 * cette fonction returne le nombre de jour q1 
	 */
	
	public function totalq1($modalite)
	{
	$conge = new Default_Model_Conge();
	$debut_mois = $this->getAnnee_reference().'-01-01';
	$fin_mois = $this->getAnnee_reference().'-12-31'; // il faut la remplacer par l'annee de reference
	
	
	$jours_ouvres_de_annee_ref = $conge->joursOuvresDuMois($debut_mois,$fin_mois);
	$nbr_heurs_ouvrees_annee = ($jours_ouvres_de_annee_ref -14) *7.4;
	$personne = new Default_Model_Personne();
	$id_entite =1; // MBA : pourquoi entite 1 pour personne? 
	$personne = $personne->fetchall('id_entite ='.$id_entite. '&&'. 'id ='.$this->getPersonne()->getId());
	//var_dump($personne);
	if (null!==$personne)
	
	{
		if ($modalite == 4)
		{
			$nbr_heurs_ouvrees_annee = ($jours_ouvres_de_annee_ref -14) *7.4;
			if($nbr_heurs_ouvrees_annee >1607)
			{
				return 0.5;
			}
			elseif($nbr_heurs_ouvrees_annee <1607.5)
			{
				return 1.0;
			}
		}
		
		elseif ($modalite == 5 ||$modalite == 6 )
		{
			$nbr_jours_ouvrees_annee = $jours_ouvres_de_annee_ref-243;
			if($nbr_jours_ouvrees_annee <  10)
			{
				return 10.0;
			}
			elseif($nbr_jours_ouvrees_annee  >10)
			{
				return $nbr_jours_ouvrees_annee;
			}
		}
		
		elseif ($modalite == 1 ||$modalite == 2  ||$modalite == 3)
		{
				return 10.0;
		}
	
	}
	
	else return 0;
	
	}


}

