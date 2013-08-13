
<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_DbTable_Vacances extends Zend_Db_Table_Abstract
{
	//nom de la table
	protected $_name = 'Vacances';




    public function jours_vacances( $debut_mois, $fin_mois)
    {
    	// Conversion des dates passés en string au format DATE 
    	
    	$debut_mois = date('Y-m-d',strtotime($debut_mois)); 
    	$fin_mois = date('Y-m-d',strtotime($fin_mois)).'23:59:59';
    	
    	$db = $this->getAdapter();
    	$select = $this->select()->distinct()->setIntegrityCheck(false)
    	->from(array('v' => $this->_name), array('zone','date_debut','date_fin'))
    	->where('v.date_debut >= ?', $debut_mois) 
    	->where('v.date_fin <= ?', $fin_mois);
        
    	// retour des résultats dans un tableau associatif.
    	return $this->fetchAll($select)->toArray();
    }







}