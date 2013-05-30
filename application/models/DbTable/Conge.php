
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
   
    public function conges_en_double($id_personne,$date_debut,$date_fin,$debut_midi,$fin_midi,$id_conge) 
    {   
	        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
		    $select = new Zend_Db_Select($db);
		    $select ->from((array('c' =>'conge')),array('c.id_personne' ,'c.date_debut','c.date_fin','c.mi_debut_journee','c.mi_fin_journee')); 
	        $select->where('c.id_personne ='.$id_personne);
            
	        // pour la modification 
	        if(isset($id_conge))
	        {
	           $select->where('c.id <>'.$id_conge);
	        }
	        ///////////////////////
	        
	        if($date_debut <> $date_fin)
	        {
		        if($debut_midi == 0  &&  $fin_midi == 0)
		        {   
		        	$select->where('('.$db->quoteInto('c.date_debut >=?', $date_debut).'&&'.$db->quoteInto('c.date_fin <= ?', $date_fin).') OR    
		            				('.$db->quoteInto('c.date_debut < ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_fin).')OR                    		
		            				('.$db->quoteInto('c.date_debut <= ?', $date_fin).'&&'.$db->quoteInto('c.date_debut > ?', $date_debut).')OR 	                        		
		            				('.$db->quoteInto('c.date_fin >= ?', $date_debut).'&&'.$db->quoteInto('c.date_fin < ?', $date_fin).')OR	                        		
		            				('.$db->quoteInto('c.date_debut = ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_fin).')OR	                        		
		            				('.$db->quoteInto('c.date_fin = ?', $date_fin).'&&'.$db->quoteInto('c.date_debut < ?', $date_debut).')');
		        
		        }
		        elseif($debut_midi == 1  &&  $fin_midi == 0)        
		        {  
		             $select->where('('.$db->quoteInto('c.date_debut > ?', $date_debut).'&&'.$db->quoteInto('c.date_fin < ?', $date_fin).')OR    
                                     ('.$db->quoteInto('c.date_debut = ?', $date_debut).'&&'.$db->quoteInto('c.date_fin = ?', $date_fin).')OR    
		             				 ('.$db->quoteInto('c.date_debut < ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_fin).')OR                    		
		            				 ('.$db->quoteInto('c.date_debut <= ?', $date_fin).'&&'.$db->quoteInto('c.date_debut > ?', $date_debut).')OR 	                     		 
		            				 ('.$db->quoteInto('c.date_fin = ?', $date_debut).'&&'.$db->quoteInto('c.date_fin < ?', $date_fin).'&&'.$db->quoteInto('c.mi_fin_journee <> ?', $debut_midi).')OR	                        		
		            				 ('.$db->quoteInto('c.date_fin > ?', $date_debut).'&&'.$db->quoteInto('c.date_fin < ?', $date_fin).')OR		            				 
		            				 ('.$db->quoteInto('c.date_debut = ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_fin).')OR
		            				 ('.$db->quoteInto('c.date_fin = ?', $date_fin).'&&'.$db->quoteInto('c.date_debut < ?', $date_debut).')');
		                            
		        }
		        elseif($debut_midi == 0  &&  $fin_midi == 1)
		        {     
		        	 $select->where('('.$db->quoteInto('c.date_debut > ?', $date_debut).'&&'.$db->quoteInto('c.date_fin < ?', $date_fin).') OR
		        	                 ('.$db->quoteInto('c.date_debut = ?', $date_debut).'&&'.$db->quoteInto('c.date_fin = ?', $date_fin).') OR
		            				 ('.$db->quoteInto('c.date_debut < ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_fin).')OR 
		            				 ('.$db->quoteInto('c.date_debut = ?', $date_fin).'&&'.$db->quoteInto('c.date_debut > ?', $date_debut).'&&'.$db->quoteInto('c.mi_debut_journee <> ?', $fin_midi).') OR                    		
		            				 ('.$db->quoteInto('c.date_debut < ?', $date_fin).'&&'.$db->quoteInto('c.date_debut > ?', $date_debut).')OR                   		 		            				 
								     ('.$db->quoteInto('c.date_fin >= ?', $date_debut).'&&'.$db->quoteInto('c.date_fin < ?', $date_fin).')OR	                        		
								     ('.$db->quoteInto('c.date_debut = ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_fin).')OR	                        		
		            				 ('.$db->quoteInto('c.date_fin = ?', $date_fin).'&&'.$db->quoteInto('c.date_debut < ?', $date_debut).')');
		        }
		        elseif($debut_midi == 1  ||  $fin_midi == 1)   
		        {   
		             $select->where('('.$db->quoteInto('c.date_debut > ?', $date_debut).'&&'.$db->quoteInto('c.date_fin < ?', $date_fin).') OR
		        	                 ('.$db->quoteInto('c.date_debut = ?', $date_debut).'&&'.$db->quoteInto('c.date_fin = ?', $date_fin).') OR  
		            				 ('.$db->quoteInto('c.date_debut < ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_fin).')OR    
		            				 ('.$db->quoteInto('c.date_debut = ?', $date_fin).'&&'.$db->quoteInto('c.date_debut > ?', $date_debut).'&&'.$db->quoteInto('c.mi_debut_journee <> ?', $fin_midi).') OR                		
		            				 ('.$db->quoteInto('c.date_debut < ?', $date_fin).'&&'.$db->quoteInto('c.date_debut > ?', $date_debut).')OR   
		            				 ('.$db->quoteInto('c.date_fin = ?', $date_debut).'&&'.$db->quoteInto('c.date_fin < ?', $date_fin).'&&'.$db->quoteInto('c.mi_fin_journee <> ?', $debut_midi).')OR
		                             ('.$db->quoteInto('c.date_fin > ?', $date_debut).'&&'.$db->quoteInto('c.date_fin < ?', $date_fin).')OR 
		                             ('.$db->quoteInto('c.date_debut = ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_fin).')OR
		                             ('.$db->quoteInto('c.date_fin = ?', $date_fin).'&&'.$db->quoteInto('c.date_debut < ?', $date_debut).')');
		       }
	        }
			if($date_debut == $date_fin)
			{
			 	    if($debut_midi == 0  &&  $fin_midi == 0)
			        {   
			        	$select->where('('.$db->quoteInto('c.date_debut <=?', $date_debut).'&&'.$db->quoteInto('c.date_fin >=?', $date_debut).')');   
			        }
			        elseif($debut_midi == 1  &&  $fin_midi == 0)        
			        {  	
			        	$select->where('('.$db->quoteInto('c.date_debut =?', $date_debut).'&&'.$db->quoteInto('c.date_fin =?', $date_debut).'&&'.$db->quoteInto('c.mi_fin_journee = ?', $fin_midi).') OR
			        	                ('.$db->quoteInto('c.date_debut < ?', $date_debut).'&&'.$db->quoteInto('c.date_fin = ?', $date_debut).'&&'.$db->quoteInto('c.mi_fin_journee = ?', $fin_midi).') OR
			        	                ('.$db->quoteInto('c.date_debut = ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_debut).'&&'.$db->quoteInto('c.mi_debut_journee <> ?', $debut_midi).') OR
			        	                ('.$db->quoteInto('c.date_debut = ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_debut).'&&'.$db->quoteInto('c.mi_debut_journee <> ?', $fin_midi).') OR
			        	                ('.$db->quoteInto('c.date_debut < ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_debut).')');
			        }
			        elseif($debut_midi == 0  &&  $fin_midi == 1)
			        {   
			        	$select->where('('.$db->quoteInto('c.date_debut =?', $date_debut).'&&'.$db->quoteInto('c.date_fin =?', $date_debut).'&&'.$db->quoteInto('c.mi_debut_journee = ?', $debut_midi).') OR	    	
			        	                ('.$db->quoteInto('c.date_debut = ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_debut).'&&'.$db->quoteInto('c.mi_debut_journee = ?', $debut_midi).') OR
			        	                ('.$db->quoteInto('c.date_debut < ?', $date_debut).'&&'.$db->quoteInto('c.date_fin = ?', $date_debut).'&&'.$db->quoteInto('c.mi_fin_journee <> ?', $fin_midi).') OR
			        	                ('.$db->quoteInto('c.date_debut < ?', $date_debut).'&&'.$db->quoteInto('c.date_fin = ?', $date_debut).'&&'.$db->quoteInto('c.mi_fin_journee <> ?', $debut_midi).') OR
			        	                ('.$db->quoteInto('c.date_debut < ?', $date_debut).'&&'.$db->quoteInto('c.date_fin > ?', $date_debut).')');	                
			        }
			}
	                 
			  return  $row = $select->query()->fetchAll(); 
    }// fin fonction 
    
   
  
     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    
    
    
    
}