<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Model_Entite
{
	//variables correspondant à chacun des champs de notre table users
	protected  $_id;
	protected  $_libelle;
	protected  $_cs; // MBA: attribut centre de service

	//le mapper va nous fournir les méthodes pour interagir avec notre table (objet de type Default_Model_EntiteMapper)
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
			throw new Exception('Invalid guestbook property');
		}
		$this->$method($value);
	}

	//cette méthode permet d'appeler n'importe quel gettor en fonction
	//du nom passé en argument
	public function __get($name)
	{
		$method = 'get' . $name;
		if (('mapper' == $name) || !method_exists($this, $method)) {
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

	public function setLibelle($libelle)
	{
		$this->_libelle = (string)$libelle;
		return $this;
	}

	public function  getLibelle()
	{
		return $this->_libelle;
	}
	/*
	 * getters et setters du champ cs (centre de service) 
	 */
	#region MBA
	public function setCs($cs)
	{
		if("1" === $cs OR "0" === $cs) // MBA : contrôle sur les valeur que peut prendre cs limitées à 0 ou 1
		$this->_cs = (string) $cs;
		
		return $this;
	}

	public function  getCs()
	{
		return $this->_cs;
	}
    #endregion MBA


	public function setMapper($mapper)
	{
		$this->_mapper = $mapper;
		return $this;
	}
	public function getMapper()
	{
		//si la valeur $_mapper n'est pas initialisée, on l'initialise (
		if(null == $this->_mapper){
			$this->setMapper(new Default_Model_EntiteMapper());
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
    //récupère une entrée particulière where $champ = $val
	
	//récupère toutes les entrées de la table
	public function fetchAll($str,$where = null)
	{
		return $this->getMapper()->fetchAll($where,$str);
	}

	//permet la suppression
	public function delete($id)
	{
		$this->getMapper()->delete($id);
	}
	
}
