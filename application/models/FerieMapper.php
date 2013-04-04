<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_FerieMapper
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
			$this->setDbTable('Default_Model_DbTable_Ferie');
		}
		return $this->_dbTable;
	}

	//sauve une nouvelle entrée dans la table
	public function save(Default_Model_Ferie $ferie)
	{
		//récupération dans un tableau des données de l'objet $ferie
		//les noms des clés du tableau correspondent aux noms des champs de la table
		$data = array(
               	'id' => $ferie->getId(),
               	'id_type' => $ferie->getId_type(),
               	'date_debut' => $ferie->getDate_debut(),
               	'annee_reference' => $ferie-> getAnnee_reference(),
			   	'libelle' => $ferie->getLibelle(),
		);

		//on vérifie si un l'objet $ferie contient un id
		//si ce n'est pas le cas, il s'agit d'un nouvel enregistrement
		//sinon, c'est une mise à jour d'une entrée à effectuer
		if(null === ($id = $ferie->getId()))
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
	public function find($id, Default_Model_Ferie $ferie)
	{
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) {
			return;
		}

		//initialisation de la variable $row avec l'entrée récupérée
		$row = $result->current();

		//setting des valeurs dans notre objet $ferie passé en argument
		$ferie->setId($row->id);
		$ferie->setId_personne($row->id_type);
		$ferie->setDate_debut($row->date_debut);
		$ferie->setAnnee_reference($row->annee_reference);
		$ferie->setLibelle($row->libelle);
		
	}

	//récupére toutes les entrées de la table
	public function fetchAll($str)
	{
		//récupération dans la variable $resultSet de toutes les entrées de notre table
		$resultSet = $this->getDbTable()->fetchAll($str);

		//chaque entrée est représentée par un objet Default_Model_Ferie
		//qui est ajouté dans un tableau
		$entries = array();
		foreach($resultSet as $row)
		{
			$entry = new Default_Model_Ferie();
			$entry->setId($row->id);
			$entry->setId_type($row->id_type);
			$entry->setDate_debut($row->date_debut);
			$entry->setAnnee_reference($row->annee_reference);
			$entry->setLibelle($row->libelle);
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
	
	public function ChercheUnJourFerie( $num_fete,$annee_reference)
	{
		return $max = $this->getDbTable()->ChercheUnJourFerie( $num_fete,$annee_reference) ;
	}
	
	public function RecupererLesJoursFeries( $annee_reference)
	{
		return $max = $this->getDbTable()->RecupererLesJoursFeries($annee_reference) ;
	}
}
