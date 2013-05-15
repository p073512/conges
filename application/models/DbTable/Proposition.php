
<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_DbTable_Proposition extends Zend_Db_Table_Abstract
{
	//nom de la table
	protected $_name = 'proposition';
	
   public function PropositionExistante($id_personne,$date_debut,$date_fin) 
    {
    	
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $select = new Zend_Db_Select($db);
	    // Requête
	    $select ->from((array('p' => 'proposition')),array('p.id_personne' ,'p.date_debut','p.date_fin')); 
	    $select->where('p.id_personne ='.$id_personne);
        $select->where('('.$db->quoteInto('p.date_debut>=?', $date_debut).'&&'.$db->quoteInto('p.date_fin <=?', $date_fin).') OR ('.$db->quoteInto('p.date_debut<?', $date_debut).'&&'.$db->quoteInto('p.date_fin >=?', $date_debut).')OR ('.$db->quoteInto('p.date_debut<?', $date_fin).'&&'.$db->quoteInto('p.date_fin >=?', $date_fin).')');
	    $row = $select->query()->fetchAll();
    	return $row;
    }

}