<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_ProfilMapper
{
	//$_dbTable va faire référence à un objet Zend_Db_Table_Abstract
	//dans notre cas la classe Default_Model_DbTable_Profil
	//du fichier application/models/DbTable/Profil.php
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
			$this->setDbTable('Default_Model_DbTable_Profil');
		}
		return $this->_dbTable;
	}

	//sauve une nouvelle entrée dans la table
	public function save(Default_Model_Profil $profil)
	{
		//récupération dans un tableau des données de l'objet $profil
		//les noms des clés du tableau correspondent aux noms des champs de la table
		$data = array(
               'login' => $profil->getLogin(),
               'mot_passe' => $profil->getMotDePasse()
		);

		//on vérifie si un l'objet $profil contient un id
		//si ce n'est pas le cas, il s'agit d'un nouvel enregistrement
		//sinon, c'est une mise à jour d'une entrée à effectuer
		if(null === ($id = $profil->getId()))
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
	public function find($id, Default_Model_Profil $profil)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}

		//initialisation de la variable $row avec l'entrée récupérée
		$row = $result->current();

		//setting des valeurs dans notre objet $profil passé en argument
		$profil->setId($row->id);
		$profil->setLogin($row->login);
		$profil->setMotDePasse($row->mot_passe);
	}

	//récupére toutes les entrées de la table
	public function fetchAll()
	{
		//récupération dans la variable $resultSet de toutes les entrées de notre table
		$resultSet = $this->getDbTable()->fetchAll();

		//chaque entrée est représentée par un objet Default_Model_Profil
		//qui est ajouté dans un tableau
		$entries = array();
		foreach($resultSet as $row)
		{
			$entry = new Default_Model_Profil();
			$entry->setId($row->id);
			$entry->setLogin($row->login);
			$entry->setMotDePasse($row->mot_passe);
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
}