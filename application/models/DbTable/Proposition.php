
<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_DbTable_Proposition extends Zend_Db_Table_Abstract
{
	 //nom de la table
	 protected $_name = 'proposition';
	 
/*
 * MTA 
 */

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function propositions_en_double($id_personne,$date_debut,$date_fin,$id_proposition) 
    {   
	        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
		    $select = new Zend_Db_Select($db);
		    $select ->from((array('p' =>'proposition')),array('p.id','p.id_personne' ,'p.date_debut','p.date_fin')); 
	        $select->where('p.id_personne ='.$id_personne);
            
	        // pour la modification 
	        if(isset($id_proposition))
	        {
	           $select->where('p.id <>'.$id_proposition);
	        }
	        ///////////////////////
    
		       $select->where('('.$db->quoteInto('p.date_debut >= ?', $date_debut).'&&'.$db->quoteInto('p.date_debut <= ?', $date_fin).') OR  
	  					       ('.$db->quoteInto('p.date_debut < ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_fin).') OR
		       					('.$db->quoteInto('p.date_debut <= ?', $date_fin).'&&'.$db->quoteInto('p.date_debut >= ?', $date_debut).') OR  
	                           ('.$db->quoteInto('p.date_fin >= ?', $date_debut).'&&'.$db->quoteInto('p.date_fin <= ?', $date_fin).')');
     
			return  $select->query()->fetchAll(); 
    }// fin fonction 
   
}