<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_VacancesMapper
{

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
			$this->setDbTable('Default_Model_DbTable_Vacances');
		}
		return $this->_dbTable;
	}

	//sauve une nouvelle entrée dans la table


	//récupére une entrée dans la table


	//récupére toutes les entrées de la table
	public function fetchAll($str)
	{
		//récupération dans la variable $resultSet de toutes les entrées de notre table
		$resultSet = $this->getDbTable()->fetchAll($str);

		//chaque entrée est représentée par un objet Default_Model_Vacances
		//qui est ajouté dans un tableau
		$entries = array();
		foreach($resultSet as $row)
		{
			$entry = new Default_Model_Vacances();
			$entry->setId($row->id);
			$entry->setZone($row->zone);
			$entry->setDate_debut($row->date_debut);
			$entry->setDate_fin($row->date_fin);
			$entry->setMapper($this);

			$entries[] = $entry;
		}

		return $entries;
	}

	public function jours_vacances( $debut_mois, $fin_mois)
	{
		return $this->getDbTable()->jours_vacances( $debut_mois, $fin_mois) ;
	}

}
