<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Model_Ferie
{
	//variables correspondant à chacun des champs de notre table proposition
	protected  $_id;
	protected  $_id_type;
	protected  $_date_debut;
	protected  $_annee_reference;
	protected  $_libelle;
	

	//le mapper va nous fournir les méthodes pour interagir avec notre table (objet de type Default_Model_FerieMapper)
	protected $_mapper;

	//constructeur
	//le tableau d'options peut contenir les valeurs des champs à utiliser
	//pour l'initialisation de l'objet
	public function __construct(array $options = null)
	{
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
	public function setId($id)
	{
		$this->_id = (int)$id;
		return $this;
	}

	public function getId()
	{
		return $this->_id;
	}

	public function setId_type($id_type)
	{
		$this->_id_type = $id_type;
		return $this;
	}
	public function getId_type()
	{
		return $this->_id_type;
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


	public function setAnnee_reference($annee_reference)
	{
		$this->_annee_reference = (int)$annee_reference;
		return $this;
	}
	public function  getAnnee_reference()
	{
		return $this->_annee_reference;
	}

	public function setLibelle($libelle)
	{
		$this->_libelle = $libelle;
		return $this;
	}
	public function getLibelle()
	{
		return $this->_libelle;
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
			$this->setMapper(new Default_Model_FerieMapper());
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

	public function ChercheUnJourFerie( $num_fete,$annee_reference)  
	{
		return $this->getMapper()->ChercheUnJourFerie( $num_fete,$annee_reference) ;
	}
	 
	public function RecupererLesJoursFeries( $annee_reference) 
	{
		return $this->getMapper()->RecupererLesJoursFeries( $annee_reference);
	}
}
