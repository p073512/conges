
<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_DbTable_Fonction extends Zend_Db_Table_Abstract
{
	//nom de la table
	protected $_name = 'fonction';
	//MBA :Tables en relation
    protected $_dependentTables = 'Default_Model_DbTable_Personne';
}