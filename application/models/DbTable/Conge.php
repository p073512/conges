
<?php
//"Default" est le namespace défini dans le bootstrap
class Default_Model_DbTable_Conge extends Zend_Db_Table_Abstract
{
	//nom de la table
	protected $_name = 'conge';
	/*
	 * cette fonction calcule la somme des jour ouvres  pour chaque annee de reference - les jour de conge
	 */

	
	
	public function somme($id,$annee_reference) 
    {
         $sum = $this->select()->distinct()
                ->from($this->_name, 'sum(nombre_jours)')
                ->where ('id_personne =?', $id)
                ->where ('date_debut >=?', $annee_reference);
           return $this->fetchAll($sum )->toArray();
    }
    
    public function selctid() 
    {
        $sel = $this->select()->distinct()
                ->from($this->_name, 'id_personne');
         return $this->fetchAll($sel)->toArray();
    }

    public function somme_solde_annuel_confe($id_personnes, $debut_annee, $fin_annee)
    {
    	$db = $this->getAdapter();
    	$select = $this->select()->distinct()->setIntegrityCheck(false)
    	->from(array('c' => $this->_name), 'sum(nombre_jours)')
    	->where('id_personne =?', $id_personnes)
    	->where('('.$db->quoteInto('c.date_debut>=?', $debut_annee).'&&'.$db->quoteInto('c.date_fin <=?', $fin_annee).') OR ('.$db->quoteInto('c.date_debut<?', $fin_annee).'&&'.$db->quoteInto('c.date_fin >=?', $fin_annee).')');
    
    	return $this->fetchAll($select)->toArray();
    }
    
    /*
     * la fonction doublont recupere id personne qui ont plusieurs conges dans un mois
     */
    public function doublont( $debut_mois,  $fin_mois)
    {
    	$db = $this->getAdapter();
    	$select = $this->select()->distinct()->setIntegrityCheck(false)
    	->from(array('c' => $this->_name), 'id_personne')
    	->joinInner(array('cr' => 'conge'), 'cr.id !=c.id'.'&&'.'cr.id_personne =c.id_personne',array('id_personne'))
    	->where('('.$db->quoteInto('c.date_debut>=?', $debut_mois).'&&'.$db->quoteInto('c.date_fin <=?', $fin_mois).') OR ('.$db->quoteInto('c.date_debut<?', $debut_mois).'&&'.$db->quoteInto('c.date_fin >=?', $debut_mois).')OR ('.$db->quoteInto('c.date_debut<?', $fin_mois).'&&'.$db->quoteInto('c.date_fin >=?', $fin_mois).')');
        
    	return $this->fetchAll($select)->toArray();
    }
    
    /*
     * cette fonction recupere le date min de la personne ayant plusieurs conge dans un mois
     */
    
    
    public function DateDebutMin($id,$debut_mois,$fin_mois)
    {
    	$db = $this->getAdapter();
    	$sum = $this->select()->distinct()
    	->from($this->_name,'min(date_debut)')
    	->where ('id_personne =?', $id)
    	->where('('.$db->quoteInto('date_debut>=?', $debut_mois).'&&'.$db->quoteInto('date_fin <=?', $fin_mois).') OR ('.$db->quoteInto('date_debut<?', $debut_mois).'&&'.$db->quoteInto('date_fin >=?', $debut_mois).')OR ('.$db->quoteInto('date_debut<?', $fin_mois).'&&'.$db->quoteInto('date_fin >=?', $fin_mois).')');
    	return $this->fetchAll($sum )->toArray();
    }
    
    public function DateFinMax($id,$debut_mois,$fin_mois)
    {
    	$db = $this->getAdapter();
    	$sum = $this->select()->distinct()
    	->from($this->_name,'max(date_fin)')
    	->where ('id_personne =?', $id)
    	->where('('.$db->quoteInto('date_debut>=?', $debut_mois).'&&'.$db->quoteInto('date_fin <=?', $fin_mois).') OR ('.$db->quoteInto('date_debut<?', $debut_mois).'&&'.$db->quoteInto('date_fin >=?', $debut_mois).')OR ('.$db->quoteInto('date_debut<?', $fin_mois).'&&'.$db->quoteInto('date_fin >=?', $fin_mois).')');
    	return $this->fetchAll($sum )->toArray();
    }
    
	public function CongesNondoublont( $debut_mois,$fin_mois) 
    {
    	$db = $this->getAdapter();  
        $select = $this->select()->distinct()->setIntegrityCheck(false)
                    ->from(array('c' => $this->_name), array('distinct(id_personne)'))
                   
        			->where('('.$db->quoteInto('c.date_debut>=?', $debut_mois).'&&'.$db->quoteInto('c.date_fin <=?', $fin_mois).') OR ('.$db->quoteInto('c.date_debut<?', $debut_mois).'&&'.$db->quoteInto('c.date_fin >=?', $debut_mois).')OR ('.$db->quoteInto('c.date_debut<?', $fin_mois).'&&'.$db->quoteInto('c.date_fin >=?', $fin_mois).')');
        			
       
        return $this->fetchAll($select)->toArray();
    }
    /*
     * cette fonction a pour but de compter le nombre des occurences 
     * d'un conge à fin de reperer les conges qui ont de mi de journee
     * elle utilisée au niveau du calendrier phtml
     */
	public function RecupererLeNombreConge( $id_personne,$date_debut) 
    {
    	$db = $this->getAdapter();  
        $select = $this->select()->setIntegrityCheck(false)
                    ->from(array('c' => $this->_name),'id_type_conge')
                     ->where('('.$db->quoteInto('c.date_debut=?', $date_debut).'&&'.$db->quoteInto('c.id_personne =?', $id_personne).')');
        			
       
        return $this->fetchAll($select)->toArray();
    }
    
    public function DoublontAuNiveauPole( $tableau_id, $debut_mois,  $fin_mois)
    {
    	$db = $this->getAdapter();
    	$select = $this->select()->distinct()->setIntegrityCheck(false)
    	->from(array('c' => $this->_name), 'id_personne')
    	->joinInner(array('cr' => 'conge'), 'cr.id !=c.id'.'&&'.'cr.id_personne =c.id_personne',array('id_personne'))
    	->where('cr.id_personne IN (?)', $tableau_id)
    	->where('('.$db->quoteInto('c.date_debut>=?', $debut_mois).'&&'.$db->quoteInto('c.date_fin <=?', $fin_mois).') OR ('.$db->quoteInto('c.date_debut<?', $debut_mois).'&&'.$db->quoteInto('c.date_fin >=?', $debut_mois).')OR ('.$db->quoteInto('c.date_debut<?', $fin_mois).'&&'.$db->quoteInto('c.date_fin >=?', $fin_mois).')');
        
    	return $this->fetchAll($select)->toArray();
    }
	
   public function CongesNondoublontPole( $tableau_id,$debut_mois,$fin_mois) 
    {
    	$db = $this->getAdapter();  
        $select = $this->select()->distinct()->setIntegrityCheck(false)
                    ->from(array('c' => $this->_name), array('distinct(id_personne)'))
                   	->where('c.id_personne IN (?)', $tableau_id)
        			->where('('.$db->quoteInto('c.date_debut>=?', $debut_mois).'&&'.$db->quoteInto('c.date_fin <=?', $fin_mois).') OR ('.$db->quoteInto('c.date_debut<?', $debut_mois).'&&'.$db->quoteInto('c.date_fin >=?', $debut_mois).')OR ('.$db->quoteInto('c.date_debut<?', $fin_mois).'&&'.$db->quoteInto('c.date_fin >=?', $fin_mois).')');
        			
       
        return $this->fetchAll($select)->toArray();
    }

    ////////////////////////////////////////////////////////////////////MTA/////////////////////////////////////////////////////////////////////
     
     // charge tt les congés d'une personne donnée apartir de la base de données 
      public function conges_par_ressource($id_personne) 
     {  
       $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $select = new Zend_Db_Select($db);
	    $select->from((array('c' =>'conge')),array('c.id_personne' ,'c.date_debut','c.date_fin','c.mi_debut_journee','c.mi_fin_journee')); 
	    $select->where('c.id_personne ='.$id_personne);
	    
        return $row = $select->query()->fetchAll();
     }
    
    
    
    
    // recuperer un congé identique dans la base de données  
    public function conges_indentique($id_personne,$date_debut,$date_fin,$debut_midi,$fin_midi) 
    { 
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $select = new Zend_Db_Select($db);	
	    
        $select ->from((array('c' =>'conge')),array('c.id_personne' ,'c.date_debut','c.date_fin','c.mi_debut_journee','c.mi_fin_journee')); 
	    $select->where('c.id_personne ='.$id_personne);
	    $select->where('('.$db->quoteInto('c.date_debut =?', $date_debut).'&&'.$db->quoteInto('c.date_fin =?', $date_fin).'&&'.$db->quoteInto('c.mi_debut_journee =?', $debut_midi).'&&'.$db->quoteInto('c.mi_fin_journee =?', $fin_midi).')');
	
        return $row = $select->query()->fetchAll();
    }
    
    
    // recuperer les conges dans une periode de temps pour une ressource donnée 
    //$flag = 1 inclue les bornes / $flag = 0 n'iclue pas les bornes 
    public function conges_existant($id_personne,$date_debut,$date_fin,$flag) 
    {  
	    $db = Zend_Db_Table_Abstract::getDefaultAdapter();
	    $select = new Zend_Db_Select($db);
	    $select ->from((array('c' =>'conge')),array('c.id_personne' ,'c.date_debut','c.date_fin','c.mi_debut_journee','c.mi_fin_journee')); 
	    $select->where('c.id_personne ='.$id_personne);
	    
        if($flag == 0) // retourne toute les dates qui touche la periode 
        {
	        $select->where('('.$db->quoteInto('c.date_debut>=?', $date_debut).'&&'.$db->quoteInto('c.date_fin <=?', $date_fin).') OR 
	                        ('.$db->quoteInto('c.date_debut<=?', $date_debut).'&&'.$db->quoteInto('c.date_fin >=?', $date_fin).')OR 
	                        ('.$db->quoteInto('c.date_debut<= ?', $date_fin).'&&'.$db->quoteInto('c.date_debut >?', $date_debut).')OR 
	                        ('.$db->quoteInto('c.date_fin >= ?', $date_debut).'&&'.$db->quoteInto('c.date_fin <?', $date_fin).')');
	
		    $row = $select->query()->fetchAll();
        }
        elseif($flag == 1) // retourne toute les dates qui touche la periode sauf date_debut == df  ou date_fin == dd 
        {
	        $select->where('('.$db->quoteInto('c.date_debut>=?', $date_debut).'&&'.$db->quoteInto('c.date_fin <=?', $date_fin).') OR 
	                        ('.$db->quoteInto('c.date_debut<?', $date_debut).'&&'.$db->quoteInto('c.date_fin >?', $date_fin).')OR 
	                        ('.$db->quoteInto('c.date_debut< ?', $date_fin).'&&'.$db->quoteInto('c.date_debut >?', $date_debut).')OR 
	                        ('.$db->quoteInto('c.date_fin > ?', $date_debut).'&&'.$db->quoteInto('c.date_fin <?', $date_fin).')');

		    $row = $select->query()->fetchAll();
        }

    	return $row;
    }
    

  
     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    
    
    
    
}