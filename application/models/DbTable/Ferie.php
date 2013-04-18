
<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_DbTable_Ferie extends Zend_Db_Table_Abstract
{
	//nom de la table
	protected $_name = 'jours_feries_csm';

	public function ChercheUnJourFerie( $num_fete,$annee_reference) 
    {
    	$db = $this->getAdapter();  
        $select = $this->select()
        			->where("libelle LIKE ?", '%'.$num_fete.'%')
        			->where("annee_reference = (?)", $annee_reference);
        			
           return $this->fetchAll($select)->toArray();
    }

	public function RecupererLesJoursFeries( $annee_reference) 
    { 	
    	$db = $this->getAdapter();  
        $select = $this->select()
       				 ->from(array('f' => $this->_name),'date_debut')
        			->where("annee_reference = (?)", $annee_reference);
           return $this->fetchAll($select)->toArray();
    }
}