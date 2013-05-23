
<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_DbTable_Proposition extends Zend_Db_Table_Abstract
{
	//nom de la table
	protected $_name = 'proposition';
	
  ////////////////////////////////////////////////////////////////////MTA/////////////////////////////////////////////////////////////////////
    
	
	
    public function Propositionindentique($id_personne,$date_debut,$date_fin,$debut_midi,$fin_midi) 
    { 
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $select = new Zend_Db_Select($db);	
	    
        $select ->from((array('p' =>'proposition')),array('p.id_personne' ,'p.date_debut','p.date_fin','p.mi_debut_journee','p.mi_fin_journee')); 
	    $select->where('p.id_personne ='.$id_personne);
	    $select->where('('.$db->quoteInto('p.date_debut =?', $date_debut).'&&'.$db->quoteInto('p.date_fin =?', $date_fin).'&&'.$db->quoteInto('p.mi_debut_journee =?', $debut_midi).'&&'.$db->quoteInto('p.mi_fin_journee =?', $fin_midi).')');
	
        return $row = $select->query()->fetchAll();
    }
	
	
	
	
    // recuperer les conges dans une periode de temps pour une ressource donnée 
    //$flag = 1 inclue les bornes / $flag = 0 n'iclue pas les bornes 
    public function PropositionExistante($id_personne,$date_debut,$date_fin,$flag) 
    {  
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $select = new Zend_Db_Select($db);
	    $select ->from((array('p' =>'proposition')),array('p.id_personne' ,'p.date_debut','p.date_fin','p.mi_debut_journee','p.mi_fin_journee')); 
	    $select->where('p.id_personne ='.$id_personne);
        if($flag == 1)
        {
	        $select->where('('.$db->quoteInto('p.date_debut>=?', $date_debut).'&&'.$db->quoteInto('p.date_fin <=?', $date_fin).') OR  			
	         				('.$db->quoteInto('p.date_debut<=?', $date_fin).'&&'.$db->quoteInto('p.date_fin >?', $date_fin).')OR 
	                        ('.$db->quoteInto('p.date_debut<?', $date_debut).'&&'.$db->quoteInto('p.date_fin >=?', $date_debut).')OR
	                        ('.$db->quoteInto('p.date_debut>?', $date_debut).'&&'.$db->quoteInto('p.date_fin <?', $date_fin).')');
	
	        
		    $row = $select->query()->fetchAll();
        }
        elseif($flag == 0)
        {
	        $select->where('('.$db->quoteInto('p.date_debut>=?', $date_debut).'&&'.$db->quoteInto('p.date_fin <=?', $date_fin).') OR 
	                 ('.$db->quoteInto('p.date_debut<?', $date_debut).'&&'.$db->quoteInto('p.date_fin >?', $date_debut).')OR 
	                 ('.$db->quoteInto('p.date_debut<?', $date_fin).'&&'.$db->quoteInto('p.date_fin >?', $date_fin).')');
	
		    $row = $select->query()->fetchAll();

        }
    	return $row;
    }

     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}