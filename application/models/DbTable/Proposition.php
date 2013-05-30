
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
    public function propositions_en_double($id_personne,$date_debut,$date_fin,$debut_midi,$fin_midi,$id_proposition) 
    {   
	        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
		    $select = new Zend_Db_Select($db);
		    $select ->from((array('p' =>'proposition')),array('p.id_personne' ,'p.date_debut','p.date_fin','p.mi_debut_journee','p.mi_fin_journee')); 
	        $select->where('p.id_personne ='.$id_personne);
            
	        // pour la modification 
	        if(isset($id_proposition))
	        {
	           $select->where('p.id <>'.$id_proposition);
	        }
	        ///////////////////////
	        
	        if($date_debut <> $date_fin)
	        {
		        if($debut_midi == 0  &&  $fin_midi == 0)
		        {   
		        	$select->where('('.$db->quoteInto('p.date_debut >=?', $date_debut).'&&'.$db->quoteInto('p.date_fin <= ?', $date_fin).') OR    
		            				('.$db->quoteInto('p.date_debut < ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_fin).')OR                    		
		            				('.$db->quoteInto('p.date_debut <= ?', $date_fin).'&&'.$db->quoteInto('p.date_debut > ?', $date_debut).')OR 	                        		
		            				('.$db->quoteInto('p.date_fin >= ?', $date_debut).'&&'.$db->quoteInto('p.date_fin < ?', $date_fin).')OR	                        		
		            				('.$db->quoteInto('p.date_debut = ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_fin).')OR	                        		
		            				('.$db->quoteInto('p.date_fin = ?', $date_fin).'&&'.$db->quoteInto('p.date_debut < ?', $date_debut).')');
		        
		        }
		        elseif($debut_midi == 1  &&  $fin_midi == 0)        
		        {  
		             $select->where('('.$db->quoteInto('p.date_debut > ?', $date_debut).'&&'.$db->quoteInto('p.date_fin < ?', $date_fin).')OR    
                                     ('.$db->quoteInto('p.date_debut = ?', $date_debut).'&&'.$db->quoteInto('p.date_fin = ?', $date_fin).')OR    
		             				 ('.$db->quoteInto('p.date_debut < ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_fin).')OR                    		
		            				 ('.$db->quoteInto('p.date_debut <= ?', $date_fin).'&&'.$db->quoteInto('p.date_debut > ?', $date_debut).')OR 	                     		 
		            				 ('.$db->quoteInto('p.date_fin = ?', $date_debut).'&&'.$db->quoteInto('p.date_fin < ?', $date_fin).'&&'.$db->quoteInto('p.mi_fin_journee <> ?', $debut_midi).')OR	                        		
		            				 ('.$db->quoteInto('p.date_fin > ?', $date_debut).'&&'.$db->quoteInto('p.date_fin < ?', $date_fin).')OR		            				 
		            				 ('.$db->quoteInto('p.date_debut = ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_fin).')OR
		            				 ('.$db->quoteInto('p.date_fin = ?', $date_fin).'&&'.$db->quoteInto('p.date_debut < ?', $date_debut).')');
		                            
		        }
		        elseif($debut_midi == 0  &&  $fin_midi == 1)
		        {     
		        	 $select->where('('.$db->quoteInto('p.date_debut > ?', $date_debut).'&&'.$db->quoteInto('p.date_fin < ?', $date_fin).') OR
		        	                 ('.$db->quoteInto('p.date_debut = ?', $date_debut).'&&'.$db->quoteInto('p.date_fin = ?', $date_fin).') OR
		            				 ('.$db->quoteInto('p.date_debut < ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_fin).')OR 
		            				 ('.$db->quoteInto('p.date_debut = ?', $date_fin).'&&'.$db->quoteInto('p.date_debut > ?', $date_debut).'&&'.$db->quoteInto('p.mi_debut_journee <> ?', $fin_midi).') OR                    		
		            				 ('.$db->quoteInto('p.date_debut < ?', $date_fin).'&&'.$db->quoteInto('p.date_debut > ?', $date_debut).')OR                   		 		            				 
								     ('.$db->quoteInto('p.date_fin >= ?', $date_debut).'&&'.$db->quoteInto('p.date_fin < ?', $date_fin).')OR	                        		
								     ('.$db->quoteInto('p.date_debut = ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_fin).')OR	                        		
		            				 ('.$db->quoteInto('p.date_fin = ?', $date_fin).'&&'.$db->quoteInto('p.date_debut < ?', $date_debut).')');
		        }
		        elseif($debut_midi == 1  ||  $fin_midi == 1)   
		        {   
		             $select->where('('.$db->quoteInto('p.date_debut > ?', $date_debut).'&&'.$db->quoteInto('p.date_fin < ?', $date_fin).') OR
		        	                 ('.$db->quoteInto('p.date_debut = ?', $date_debut).'&&'.$db->quoteInto('p.date_fin = ?', $date_fin).') OR  
		            				 ('.$db->quoteInto('p.date_debut < ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_fin).')OR    
		            				 ('.$db->quoteInto('p.date_debut = ?', $date_fin).'&&'.$db->quoteInto('p.date_debut > ?', $date_debut).'&&'.$db->quoteInto('p.mi_debut_journee <> ?', $fin_midi).') OR                		
		            				 ('.$db->quoteInto('p.date_debut < ?', $date_fin).'&&'.$db->quoteInto('p.date_debut > ?', $date_debut).')OR   
		            				 ('.$db->quoteInto('p.date_fin = ?', $date_debut).'&&'.$db->quoteInto('p.date_fin < ?', $date_fin).'&&'.$db->quoteInto('p.mi_fin_journee <> ?', $debut_midi).')OR
		                             ('.$db->quoteInto('p.date_fin > ?', $date_debut).'&&'.$db->quoteInto('p.date_fin < ?', $date_fin).')OR 
		                             ('.$db->quoteInto('p.date_debut = ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_fin).')OR
		                             ('.$db->quoteInto('p.date_fin = ?', $date_fin).'&&'.$db->quoteInto('p.date_debut < ?', $date_debut).')');
		       }
	        }
			if($date_debut == $date_fin)
			{
			 	    if($debut_midi == 0  &&  $fin_midi == 0)
			        {   
			        	$select->where('('.$db->quoteInto('p.date_debut <=?', $date_debut).'&&'.$db->quoteInto('p.date_fin >=?', $date_debut).')');   
			        }
			        elseif($debut_midi == 1  &&  $fin_midi == 0)        
			        {  	
			        	$select->where('('.$db->quoteInto('p.date_debut =?', $date_debut).'&&'.$db->quoteInto('p.date_fin =?', $date_debut).'&&'.$db->quoteInto('p.mi_fin_journee = ?', $fin_midi).') OR
			        	                ('.$db->quoteInto('p.date_debut < ?', $date_debut).'&&'.$db->quoteInto('p.date_fin = ?', $date_debut).'&&'.$db->quoteInto('p.mi_fin_journee = ?', $fin_midi).') OR
			        	                ('.$db->quoteInto('p.date_debut = ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_debut).'&&'.$db->quoteInto('p.mi_debut_journee <> ?', $debut_midi).') OR
			        	                ('.$db->quoteInto('p.date_debut = ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_debut).'&&'.$db->quoteInto('p.mi_debut_journee <> ?', $fin_midi).') OR
			        	                ('.$db->quoteInto('p.date_debut < ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_debut).')');
			        }
			        elseif($debut_midi == 0  &&  $fin_midi == 1)
			        {   
			        	$select->where('('.$db->quoteInto('p.date_debut =?', $date_debut).'&&'.$db->quoteInto('p.date_fin =?', $date_debut).'&&'.$db->quoteInto('p.mi_debut_journee = ?', $debut_midi).') OR	    	
			        	                ('.$db->quoteInto('p.date_debut = ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_debut).'&&'.$db->quoteInto('p.mi_debut_journee = ?', $debut_midi).') OR
			        	                ('.$db->quoteInto('p.date_debut < ?', $date_debut).'&&'.$db->quoteInto('p.date_fin = ?', $date_debut).'&&'.$db->quoteInto('p.mi_fin_journee <> ?', $fin_midi).') OR
			        	                ('.$db->quoteInto('p.date_debut < ?', $date_debut).'&&'.$db->quoteInto('p.date_fin = ?', $date_debut).'&&'.$db->quoteInto('p.mi_fin_journee <> ?', $debut_midi).') OR
			        	                ('.$db->quoteInto('p.date_debut < ?', $date_debut).'&&'.$db->quoteInto('p.date_fin > ?', $date_debut).')');	                
			        }
			}
	                 
			  return  $row = $select->query()->fetchAll(); 
    }// fin fonction 
   
}