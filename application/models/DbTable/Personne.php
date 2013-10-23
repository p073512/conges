<?php
//"Default" est le namespace d�fini dans le bootstrap
class Default_Model_DbTable_Personne extends Zend_Db_Table_Abstract
{
	//nom de la table
	protected $_name = 'personne';
	//Champs en liaison (reference)
	protected $_referenceMap =array('Entite'=>array('columns' => 'id_entite',
	                                                'refTableClass' => 'Default_Model_DbTable_Entite',
	                                                'refColumns'=>'id'),
	                                'Fonction'=>array('columns' => 'id_fonction',
	                                                'refTableClass' => 'Default_Model_DbTable_Fonction',
	                                                'refColumns'=>'id'),
	                                'Pole'=>array('columns' => 'id_pole',
	                                                'refTableClass' => 'Default_Model_DbTable_Pole',
	                                                'refColumns'=>'id'),
	                                'Modalite'=>array('columns' => 'id_modalite',
	                                                'refTableClass' => 'Default_Model_DbTable_Modalite',
	                                                'refColumns'=>'id'));
	//MBA :Tables en relation
    protected $_dependentTables = 'Default_Model_DbTable_Solde';
	
	/*
	 * cette fonction permet de calduler le nombre de resources ce qui genere le nombre de colonnes
	 */

	
	public function obtenirColonnes( $debut_mois,$fin_mois) 
    {
    	$db = $this->getAdapter();  
        $select = $this->select()->distinct()->setIntegrityCheck(false)
                    ->from(array('pr' => $this->_name), array('id','nom','prenom','id_entite'))
                    ->joinInner(array('c' => 'conge'), 'c.id_personne =pr.id', array('date_debut','date_fin','nombre_jours','id_type_conge','mi_debut_journee','mi_fin_journee'))
        			->where('('.$db->quoteInto('c.date_debut>=?', $debut_mois).'&&'.$db->quoteInto('c.date_fin <=?', $fin_mois).') OR ('.$db->quoteInto('c.date_debut<?', $debut_mois).'&&'.$db->quoteInto('c.date_fin >=?', $debut_mois).')OR ('.$db->quoteInto('c.date_debut<?', $fin_mois).'&&'.$db->quoteInto('c.date_fin >=?', $fin_mois).')');
        			
       
        return $this->fetchAll($select)->toArray();
    }
    
	/*
	 * cette fonction permet de retourner les resources et ces conges
	 */
    
    public function obtenirresources($tableau_personnes, $debut_mois, $fin_mois) 
    {
        $db = $this->getAdapter();
        
    	$select = $this->select()->distinct()->setIntegrityCheck(false)
                    ->from(array('pr' => $this->_name), array('nom','prenom','id','id_entite'))
                    ->joinInner(array('c' => 'conge'), 'c.id_personne =pr.id', array('date_debut','date_fin','nombre_jours','id_type_conge','mi_debut_journee','mi_fin_journee'))
                    ->where('pr.id IN (?)', $tableau_personnes)
                	 ->where('('.$db->quoteInto('c.date_debut>=?', $debut_mois).'&&'.$db->quoteInto('c.date_fin <=?', $fin_mois).') OR ('.$db->quoteInto('c.date_debut<?', $debut_mois).'&&'.$db->quoteInto('c.date_fin >=?', $debut_mois).')OR ('.$db->quoteInto('c.date_debut<?', $fin_mois).'&&'.$db->quoteInto('c.date_fin >=?', $fin_mois).')');

                   return $this->fetchAll($select)->toArray();
    }
    
    /*
     * retourne la derniere personne ajoutée dans la table personne pour recuperer cette valeur
     * dans le traitement de d'initialisation de solde de cette personne
     */
    
    public function maxid() 
    {
        $max = $this->select()
                    ->from($this->_name, 'Max(id)');
              
           return $this->fetchAll($max)->toArray();
    }
	
	   public function ObtenirID($id_pole,$id_fonction,$id_entite) 
    {
		 $db = $this->getAdapter();
    	$id = $this->select()->distinct()->setIntegrityCheck(false)
                    ->from(array('pr' => $this->_name), 'id')
                  
         ->where('('.$db->quoteInto('pr.id_pole=?', $id_pole).'&&'.$db->quoteInto('pr.id_fonction=?', $id_fonction).'&&'.$db->quoteInto('pr.id_entite=?', $id_entite).') OR ('.$db->quoteInto('pr.id_pole=?', $id_pole).'&&'.$db->quoteInto('pr.id_fonction=?', $id_fonction).')OR ('.$db->quoteInto('pr.id_fonction=?', $id_fonction).'&&'.$db->quoteInto('pr.id_entite=?', $id_entite).')OR ('.$db->quoteInto('pr.id_pole=?', $id_pole).'&&'.$db->quoteInto('pr.id_entite=?', $id_entite).')OR ('.$db->quoteInto('pr.id_pole=?', $id_pole).')OR ('.$db->quoteInto('pr.id_fonction=?', $id_fonction).')OR ('.$db->quoteInto('pr.id_entite=?', $id_entite).')');      
           return $this->fetchAll($id)->toArray();
    }
	
	
}