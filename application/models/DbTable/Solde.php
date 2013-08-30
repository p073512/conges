
<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_DbTable_Solde extends Zend_Db_Table_Abstract
{
	//nom de la table
	protected $_name = 'solde';
	protected $_primary = array('annee_reference', 'id_personne');
	protected $_referenceMap =array('Personne'=>array('columns' => 'id_personne',
	                                                'refTableClass' => 'Default_Model_DbTable_Personne',
	                                                'refColumns'=>'id'));
	
}