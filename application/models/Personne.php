<?php
//"Default" correspond au namespace que nous avons d�fini dans le bootstrap
class Default_Model_Personne
{
	//variables correspondant à chacun des champs de notre table users
	protected  $id;
	protected  $_nom;
	protected  $_prenom;
	protected  $_date_entree;
	protected  $_date_debut;
	protected  $_date_fin;
	
	protected  $entite;			   //
	protected  $pole;              //
	protected  $modalite;          //
    protected  $fonction;          //
    
    protected  $_pourcent;
	protected  $_stage;

	
	//le mapper va nous fournir les m�thodes pour interagir avec notre table (objet de type Default_Model_PersonneMapper)
	protected $_mapper;

	//constructeur
	//le tableau d'options peut contenir les valeurs des champs � utiliser
	//pour l'initialisation de l'objet
	public function __construct(array $options = null)
	{
		
		$this->entite = new Default_Model_Entite();
		$this->modalite = new Default_Model_Modalite();
		$this->fonction = new Default_Model_Fonction();
		$this->pole = new Default_Model_Pole();
		
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

	//permet de gérer un tableau d'options pass� en argument au constructeur
	//ce tabelau d'options peut contenir la valeur des champs � utiliser
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

	//gettors and settors d'accés aux variables
	public function setId($id)
	{
		$this->id = (int)$id;
		return $this;
	}

	public function getId()
	{
		return $this->id;
	}

	// MTA : fonction retourne le nom et prenom concatené 
	public function getNomPrenom()
	{
	
	return $this->_nom." ".$this->_prenom;
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


	#region MBA 
	/*
	 * getters et setters de l'objet entité
	 */
    public function setEntite($entite)
	{
		if($entite instanceof Default_Model_Entite)
		{
			$this->entite = $entite;
		}
		else 
		{
			$this->entite = $this->entite->find((int) $entite);
		}
		
		
		return $this;
	}
	public function  getEntite()
	{
		return $this->entite;
	}
	
	/*
	 * getters et setters de l'objet modalit�
	 */
	public function setModalite($modalite)
	{
		if($modalite instanceof Default_Model_Modalite)
		{
			$this->modalite = $modalite;
		}
		else
		{
			$this->modalite = $this->modalite->find((int) $modalite);
		}
		
		return $this;
	}
	public function getModalite()
	{
		return $this->modalite;
	}
	
	/*
	 * getters et setters de l'objet fonction
	 */
	public function setFonction($fonction)
	{
		if($fonction instanceof Default_Model_Fonction)
		{
			$this->fonction = $fonction;
		}
		else
		{
			$this->fonction = $this->fonction->find((int) $fonction);
		}
		
		return $this;
	}
	public function getFonction()
	{
		return $this->fonction;
	}
	
	/*
	 * getters et setters de l'objet pole
	 */
	public function setPole($pole)
	{
		if($pole instanceof Default_Model_Pole)
		{
			$this->pole = $pole;
		}
		else
		{
			$this->pole = $this->pole->find((int) $pole);
		}
		
		return $this;
	}
	public function getPole()
	{
		return $this->pole;
	}
	
	
    #endregion MBA
	
	
	public function setPourcent($pourcent)
	{
		$this->_pourcent = (int)$pourcent;
		return $this;
	}

	public function  getPourcent()
	{
		return $this->_pourcent;
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
		//si la valeur $_mapper n'est pas initialis�e, on l'initialise (
		if(null == $this->_mapper){
			$this->setMapper(new Default_Model_PersonneMapper());
		}

		return $this->_mapper;
	}

	//m�thodes de classe utilisant les m�thodes du mapper
	//cr�e ou met � jour une entr�e dans la table
	public function save()
	{
		$this->getMapper()->save($this);
	}

	//r�cup�re une entr�e particuli�re
	public function find($id)
	{
		$this->getMapper()->find($id, $this);
		return $this;
	}

	// v�rifie si personne exist par nom et prenom
	public function IsExist($nom,$prenom)
	{
		return $this->getMapper()->IsExist($nom,$prenom);
	}
	
	
	//r�cup�re toutes les entr�es de la table
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

