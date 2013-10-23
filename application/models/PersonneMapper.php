<?php
//"Default" est le namespace d�fini dans le bootstrap
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
		//récupuration dans un tableau des données de l'objet $Personne
		//les noms des clés du tableau correspondent aux noms des champs de la table
		$data = array(
               	'id' => $personne->getId(),
               	'nom' => $personne->getNom(),
               	'prenom' => $personne->getPrenom(),
               	'date_entree' => $personne->getDate_entree(),
				'date_debut' => $personne->getDate_debut(),
				'date_fin' => $personne->getDate_fin(),
				'id_entite' => $personne->getEntite()->getId(),
				'id_pole' => $personne->getPole()->getId(),
				'id_modalite' => $personne->getModalite()->getId(),
				'id_fonction' => $personne->getFonction()->getId(),
				'pourcent' => $personne->getPourcent(),
				'stage' => $personne->getStage(),
		);
		

		//on v�rifie si un l'objet $conge contient un id
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

	//r�cup�re une entr�e dans la table
	public function find($id, Default_Model_Personne $personne)
	{
		
		$result = $this->getDbTable()->find($id);
		if (0 == count($result)) 
		{
			return;
		}

		//initialisation de la variable $row avec l'entrée récupérée
		$row = $result->current();
		$RowEntite = $row->findParentRow('Default_Model_DbTable_Entite');
        $Entite = new Default_Model_Entite($RowEntite->toArray());
        $RowModalite = $row->findParentRow('Default_Model_DbTable_Modalite');
        $Modalite = new Default_Model_Modalite($RowModalite->toArray());
        $RowFonction = $row->findParentRow('Default_Model_DbTable_Fonction');
        $Fonction = new Default_Model_Fonction($RowFonction->toArray());
        $RowPole = $row->findParentRow('Default_Model_DbTable_Pole');
        $Pole = new Default_Model_Pole($RowPole->toArray());
        
        
		//setting des valeurs dans notre objet $Personne pass� en argument
			$personne->setId($row->id);
			$personne->setNom($row->nom);
			$personne->setPrenom($row->prenom);
			$personne->setDate_entree($row->date_entree);
			$personne->setDate_debut($row->date_debut);
			$personne->setDate_fin($row->date_fin);
			$personne->setEntite($Entite);
			$personne->setModalite($Modalite);
			$personne->setFonction($Fonction);
			$personne->setPole($Pole);
		    $personne->setPourcent($row->pourcent);
			$personne->setStage($row->stage);
	}
	
	public function IsExist($nom,$prenom){
		$DbT = $this->getDbTable();
		$Select =  $DbT->select()
		         ->from($DbT,'count(id) as nombre')
                 ->where('LOWER(nom) = ?' , strtolower($nom))
                 ->where('LOWER(prenom) = ?', strtolower($prenom))
                 ->order('id');
	     
        $rows = $DbT->fetchAll($Select);
       
        return($rows[0]->nombre);       
    
        
               
	}

	//r�cup�re toutes les entr�es de la table
	public function fetchAll($str,$where = null)
	{
		//r�cup�ration dans la variable $resultSet de toutes les entr�es de notre table
		$resultSet = $this->getDbTable()->fetchAll($where,$str);

		//chaque entr�e est repr�sent�e par un objet Default_Model_Personne
		//qui est ajout� dans un tableau
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
			$entry->setEntite($row->id_entite);
			$entry->setPole($row->id_pole);
			$entry->setModalite($row->id_modalite);
			$entry->setFonction($row->id_fonction);
			$entry->setPourcent($row->pourcent);
			$entry->setStage($row->stage);
			$entry->setMapper($this);

			$entries[] = $entry;
		}

		return $entries;
	}
	
	/**
	 * function : fonction qui retourne les ressouces selon le centre de service
	 * auquel elles appartiennent.
	 *
	 */
	public function getRessourcesByCs($cs)
	{
		if($cs == true)
		$cs = '1';
		else if($cs == false)
		$cs = '0';
		
		$db = Zend_Db_Table_Abstract::getDefaultAdapter();
		$select = new Zend_Db_Select($db);
		
		
		$select = $db->select()
             ->from(array('p' => 'personne'))
             ->join(array('e' =>'entite'),
                   'p.id_entite = e.id')
             ->where('e.cs = ?', $cs);
		
		$result = $select->query()->fetchAll();
		return $result;
		
		
	}

	//permet de supprimer un utilisateur,
	//reçoit la condition de suppression (le plus souvent basé sur l'id)
	public function delete($id)
	{
		$result = $this->getDbTable()->delete($id);
		return $result;
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
