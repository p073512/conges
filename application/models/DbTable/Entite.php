
<?php
//"Default" est le namespace d�fini dans le bootstrap
class Default_Model_DbTable_Entite extends Zend_Db_Table_Abstract
{
	//nom de la table
	protected $_name = 'entite';
	//MBA :Tables en relation
    protected $_dependentTables = 'Default_Model_DbTable_Personne';
}