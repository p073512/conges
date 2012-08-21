<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_UsersMapper
{
	//$_dbTable va faire référence à un objet Zend_Db_Table_Abstract
	//dans notre cas la classe Default_Model_DbTable_Users
	//du fichier application/models/DbTable/Users.php
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
			$this->setDbTable('Default_Model_DbTable_Users');
		}
		return $this->_dbTable;
	}

	//sauve une nouvelle entrée dans la table
	public function save(Default_Model_Users $users)
	{
		//récupération dans un tableau des données de l'objet $users
		//les noms des clés du tableau correspondent aux noms des champs de la table
		$data = array(
               'firstname' => $users->getFirstname(),
               'lastname' => $users->getLastname(),
               'mail' => $users->getMail(),
               'password' => $users->getPassword(),
		);

		//on vérifie si un l'objet $users contient un id
		//si ce n'est pas le cas, il s'agit d'un nouvel enregistrement
		//sinon, c'est une mise à jour d'une entrée à effectuer
		if(null === ($id = $users->getId()))
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
	public function find($id, Default_Model_Users $users)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}

		//initialisation de la variable $row avec l'entrée récupérée
		$row = $result->current();

		//setting des valeurs dans notre objet $users passé en argument
		$users->setId($row->id);
		$users->setFirstname($row->firstname);
		$users->setLastname($row->lastname);
		$users->setMail($row->mail);
		$users->setPassword($row->password);
	}

	//récupére toutes les entrées de la table
	public function fetchAll()
	{
		//récupération dans la variable $resultSet de toutes les entrées de notre table
		$resultSet = $this->getDbTable()->fetchAll();

		//chaque entrée est représentée par un objet Default_Model_Users
		//qui est ajouté dans un tableau
		$entries = array();
		foreach($resultSet as $row)
		{
			$entry = new Default_Model_Users();
			$entry->setId($row->id);
			$entry->setFirstname($row->firstname);
			$entry->setLastname($row->lastname);
			$entry->setMail($row->mail);
			$entry->setPassword($row->password);
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