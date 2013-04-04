
<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_DbTable_Vacances extends Zend_Db_Table_Abstract
{
	//nom de la table
	protected $_name = 'Vacances';




    public function jours_vacances( $debut_mois, $fin_mois)
    {
    	$db = $this->getAdapter();
    	$select = $this->select()->distinct()->setIntegrityCheck(false)
    	->from(array('v' => $this->_name), array('zone','date_debut','date_fin'))
    	
    	->where('('.$db->quoteInto('v.date_debut>=?', $debut_mois).'&&'.$db->quoteInto('v.date_fin <=?', $fin_mois).') OR ('.$db->quoteInto('v.date_debut<?', $debut_mois).'&&'.$db->quoteInto('v.date_fin >=?', $debut_mois).')OR ('.$db->quoteInto('v.date_debut<?', $fin_mois).'&&'.$db->quoteInto('v.date_fin >=?', $fin_mois).')');
        
    	return $this->fetchAll($select)->toArray();
    }







}