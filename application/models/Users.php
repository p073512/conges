<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Model_Users
{
	//variables correspondant à chacun des champs de notre table users
	protected $_id;
	protected $_firstname;
	protected $_lastname;
	protected $_mail;
	protected $_password;

	//le mapper va nous fournir les méthodes pour interagir avec notre table (objet de type Default_Model_UsersMapper)
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

	public function setFirstname($firstname)
	{
		$this->_firstname = (string)$firstname;
		return $this;
	}
	public function getFirstname()
	{
		return $this->_firstname;
	}

	public function setLastname($lastname)
	{
		$this->_lastname = (string)$lastname;
		return $this;
	}
	public function getLastname()
	{
		return $this->_lastname;
	}

	public function setMail($mail)
	{
		$this->_mail = (string)$mail;
		return $this;
	}
	public function getMail()
	{
		return $this->_mail;
	}

	public function setPassword($password)
	{
		$this->_password = (string)$password;
		return $this;
	}
	public function getPassword()
	{
		return $this->_password;
	}

	public function setMapper($mapper)
	{
		$this->_mapper = $mapper;
		return $this;
	}
	public function getMapper()
	{
		//si la valeur $_mapper n'est pas initialisée, on l'initialise (
		if(null === $this->_mapper){
			$this->setMapper(new Default_Model_UsersMapper());
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
	public function fetchAll()
	{
		return $this->getMapper()->fetchAll();
	}

	//permet la suppression
	public function delete($id)
	{
		$this->getMapper()->delete($id);
	}
}