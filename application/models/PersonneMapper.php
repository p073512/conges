<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_PersonneMapper
{
	//$_dbTable va faire référence à un objet Zend_Db_Table_Abstract
	//dans notre cas la classe Default_Model_DbTable_Personne
	//du fichier application/models/DbTable/Personne.php
	protected $_dbTable;

	//settor
	public function setDbTable($dbTable)
	{
		if (is_string($dbTable)) {
			$dbTable = new $dbTable();
		}
		if (!$dbTable instanceof Zend_Db_Table_Abstract) {
			throw new Exception('Invalid table data gateway provided');
		}
		$this->_dbTable = $dbTable;
		return $this;
	}

	//guettor
	public function getDbTable()
	{
		if (null === $this->_dbTable) {
			$this->setDbTable('Default_Model_DbTable_Personne');
		}
		return $this->_dbTable;
	}

	//sauve une nouvelle entrée dans la table
	public function save(Default_Model_Personne $personne)
	{
		//récupération dans un tableau des données de l'objet $Personne
		//les noms des clés du tableau correspondent aux noms des champs de la table
		$data = array(
               	'id' => $personne->getId(),
               	'nom' => $personne->getNom(),
               	'prenom' => $personne->getPrenom(),
               	'date_entree' => $personne->getDate_entree(),
				'date_debut' => $personne->getDate_debut(),
				'date_fin' => $personne->getDate_fin(),
				'id_entite' => $personne->getEntite()->getId(),
				'id_pole' => $personne->getId_pole(),
				'id_modalite' => $personne->getId_modalite(),
				'id_fonction' => $personne->getId_fonction(),
				'pourcent' => $personne->getPourcent(),
				'centre_service' => $personne->getCentre_service(),
				'stage' => $personne->getStage(),
		);
		

		//on vérifie si un l'objet $conge contient un id
		//si ce n'est pas le cas, il s'agit d'un nouvel enregistrement
		//sinon, c'est une mise à jour d'une entrée à effectuer
		if(null === ($id = $personne->getId()))
		{
			unset($data['id']);
			$this->getDbTable()->insert($data);
		}
		else
		{
			$this->getDbTable()->update($data, array('id = ?' => $id));
		}
	}

	//récupére une entrée dans la table
	public function find($id, Default_Model_Personne $personne)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) 
		{
			return;
		}

		//initialisation de la variable $row avec l'entrée récupérée
		$row = $result->current();

		//setting des valeurs dans notre objet $Personne passé en argument
			$personne->setId($row->id);
			$personne->setNom($row->nom);
			$personne->setPrenom($row->prenom);
			$personne->setDate_entree($row->date_entree);
			$personne->setDate_debut($row->date_debut);
			$personne->setDate_fin($row->date_fin);
			$personne->setId_entite($row->id_entite);
			$personne->setId_pole($row->id_pole);
			$personne->setId_modalite($row->id_modalite);
			$personne->setId_fonction($row->id_fonction);
			$personne->setPourcent($row->pourcent);
			$personne->setCentre_service($row->centre_service);
			$personne->setStage($row->stage);
	}

	//récupére toutes les entrées de la table
	public function fetchAll($str)
	{
		//récupération dans la variable $resultSet de toutes les entrées de notre table
		$resultSet = $this->getDbTable()->fetchAll($str);

		//chaque entrée est représentée par un objet Default_Model_Personne
		//qui est ajouté dans un tableau
		$entries = array();
		foreach($resultSet as $row)
		{
			$entry = new Default_Model_Personne();
			$entry->setId($row->id);
			$entry->setNom($row->nom);
			$entry->setPrenom($row->prenom);
			$entry->setDate_entree($row->date_entree);
			$entry->setDate_debut($row->date_debut);
			$entry->setDate_fin($row->date_fin);
			$entry->setId_entite($row->id_entite);
			$entry->setId_pole($row->id_pole);
			$entry->setId_modalite($row->id_modalite);
			$entry->setId_fonction($row->id_fonction);
			$entry->setPourcent($row->pourcent);
			$entry->setCentre_service($row->centre_service);
			$entry->setStage($row->stage);
			$entry->setMapper($this);

			$entries[] = $entry;
		}

		return $entries;
	}

	//permet de supprimer un utilisateur,
	//reçoit la condition de suppression (le plus souvent basé sur l'id)
	public function delete($id)
	{
		$result = $this->getDbTable()->delete($id);
	}
	public function obtenirColonnes($debut_mois,$fin_mois)
	{
		return $resultSet = $this->getDbTable()->obtenirColonnes($debut_mois,$fin_mois);
	}
	
	public function obtenirresources($tableau_personnes,$debut_mois,$fin_mois)
	{
		return $resultSet = $this->getDbTable()->obtenirresources($tableau_personnes,$debut_mois,$fin_mois);
	}
	public function maxid() 
	{
		return $max = $this->getDbTable()->maxid();
	}
	
	public function ObtenirID($id_pole,$id_fontion,$id_entite)  
	{
		return $max = $this->getDbTable()->ObtenirID($id_pole,$id_fontion,$id_entite);
	}
}
