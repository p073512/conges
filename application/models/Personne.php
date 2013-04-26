<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Model_Personne
{
	//variables correspondant à chacun des champs de notre table users
	protected  $_id;
	protected  $_nom;
	protected  $_prenom;
	protected  $_date_entree;
	protected  $_date_debut;
	protected  $_date_fin;
	protected  $_id_entite;
	protected  $_id_pole;
	protected  $_id_modalite;
	protected  $_id_fonction;
	protected  $_pourcent;
	protected  $_centre_service;
	protected  $_stage;
	protected  $entite;

	
	
	//le mapper va nous fournir les méthodes pour interagir avec notre table (objet de type Default_Model_PersonneMapper)
	protected $_mapper;

	//constructeur
	//le tableau d'options peut contenir les valeurs des champs à utiliser
	//pour l'initialisation de l'objet
	public function __construct(array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
		$this->entite = new Default_Model_Entite();
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
	public function setId($id)
	{
		$this->_id = (int)$id;
		return $this;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function setNom($nom)
	{
		$this->_nom = (string)$nom;
		return $this;
	}
	public function getNom()
	{
		return $this->_nom;
	}

	public function setPrenom($prenom)
	{
	
		$this->_prenom = (string) $prenom;
		return $this;

	}
	public function getPrenom()
	{
		return $this->_prenom;
	}

	public function setDate_entree($date_entree)
	{
		$this->_date_entree = $date_entree;
		return $this;
	}
	public function getDate_entree()
	{
		return $this->_date_entree;
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

	public function setDate_fin($date_fin)
	{
		$this->_date_fin = $date_fin;
		return $this;
	}
	public function getDate_fin()
	{
		return $this->_date_fin;
	}
	public function setId_entite($id_entite)
	{
		$this->_id_entite = (int)$id_entite;
		return $this;
	}
	public function  getId_entite()
	{
		return $this->_id_entite;
	}
	/*
	 * getters et setters de l'objet entité
	 */
	#region MBA 
    public function setEntite($entite)
	{
		$this->entite = $entite;
		return $this;
	}
	public function  getEntite()
	{
		return $this->entite;
	}
    #endregion MBA
	public function setId_pole($id_pole)
	{
		$this->_id_pole = (int)$id_pole;
		return $this;
	}
	

	public function  getId_pole()
	{
		return $this->_id_pole;
	}

	public function setId_modalite($id_modalite)
	{
		$this->_id_modalite = (int)$id_modalite;
		return $this;
	}

	public function  getId_modalite()
	{
		return $this->_id_modalite;
	}
	
	
	public function setId_fonction($id_fonction)
	{
		$this->_id_fonction = (int)$id_fonction;
		return $this;
	}

	public function  getId_fonction()
	{
		return $this->_id_fonction;
	}

	
	public function setPourcent($pourcent)
	{
		$this->_pourcent = (int)$pourcent;
		return $this;
	}

	public function  getPourcent()
	{
		return $this->_pourcent;
	}
	
	
	
	public function setCentre_service($centre_service)
	{
		$this->_centre_service = $centre_service;
		return $this;
	}

	public function  getCentre_service()
	{
		return $this->_centre_service;
	}
	
	
	public function setStage($stage)
	{
		$this->_stage = $stage;
		return $this;
	}
	
	public function  getStage()
	{
		return $this->_stage;
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
			$this->setMapper(new Default_Model_PersonneMapper());
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

	// vérifie si personne exist par nom et prenom
	public function IsExist($nom,$prenom)
	{
		return $this->getMapper()->IsExist($nom,$prenom);
	}
	
	
	//récupère toutes les entrées de la table
	public function fetchAll($str,$where = null)
	{
		return $this->getMapper()->fetchAll($where,$str);
	}

	//permet la suppression
	public function delete($id)
	{
		return $this->getMapper()->delete($id);
	}
	// cherche le nombre de ressources existant de la table conge
	public function obtenirColonnes($debut_mois,$fin_mois)
	{
		return $this->getMapper()->obtenirColonnes($debut_mois,$fin_mois);
	}
	// cherche le nombre de ressources realisant les conditions du filtre
	public function obtenirresources($tableau_personnes,$debut_mois,$fin_mois) 
	{
		return $this->getMapper()->obtenirresources($tableau_personnes,$debut_mois,$fin_mois);
	}

	public function maxid() 
	{
		return $this->getMapper()->maxid();
	}
     
	
	public function ObtenirID($id_pole,$id_fontion,$id_entite)  
	{
		return $this->getMapper()->ObtenirID($id_pole,$id_fontion,$id_entite) ;
	}
	
	public function toString() {
		return $this->_nom. ' '.$this->_prenom;
	}


}

