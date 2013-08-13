<?php
class Default_Form_CalendrierForm extends Default_Form_MyForm
{   
	/*Nommage Variable:
	 * i pour input.
	 * e pour element.
	 * f pour form.
	 */
	protected $_eSelectPersonne;
	protected $_eSelectMois;
	protected $_eSelectAnnee;
	protected $_iSubmit;
	
	
	public function geteSelectPersonne()
	{
		return $this->_eSelectPersonne;
	}
	
	public function geteSelectMois()
	{
        return $this->_eSelectMois;		
	}
	public function geteSelectAnnee()
	{
		return $this->_eSelectAnnee;
	}
	
	public function getiSubmit()
	{
		return $this->_iSubmit;
	}
	
		    
public function init()
	{
	 	
     	parent::init();
     	// nom par d�faut du form si on veut la chang� dans la classe fille il faut rappeller la m�thode.
     	 $this->setName('Calendrier');
     	 // Method par défaut du form
         $this->setMethod('post');
         $this->removeAllDecorators();
   		 
	$this->_eSelectPersonne = $this->createElement('select','personne' ,array(
		    'label'  => 'Personne',
		    'name' => 'personne',));
	
	$selectMois = $this->createElement('select','mois' ,array(
		    'label'  => 'Mois',
		    'name' => 'mois',));
	
	$selectMois->addMultiOptions(array(
	                                '0' =>'Janvier',
	                                '1' => 'Février',
	                                '2' => 'Mars',
	                                '3' => 'Avril',
	                                '4' => 'Mai',
	                                '5' => 'Juin',
	                                '6' => 'Juillet',
	                                '7' => 'Aout',
	                                '8' => 'Septembre',
	                                '9' => 'Octobre',
	                                '10' => 'Novembre',
	                                '11' => 'Décembre' 
	
	                                   ));
	$this->_eSelectMois = $selectMois;
	
	$selectAnnee = $this->createElement('select','annee' ,array(
									    'label'  => 'Annee',
									    'name' => 'annee',));
	
	$date = new DateTime();
	$year = $date->format('Y');
	$selectAnnee->addMultiOptions(array(    $year -1 => $year -1,
					                        $year => $year,
					                        $year +1 => $year + 1  ));
	
	$this->_eSelectAnnee = $selectAnnee;
	
	 $this->_iSubmit =   new Zend_Form_Element_Button( 'chargerCalendrier',
				 					  array('label' => 'Charger Calendrier'));
				 
       
	$this->addElements(array($this->geteSelectPersonne(),
	                         $this->geteSelectMois(),
	                         $this->geteSelectAnnee(),
	                         $this->getiSubmit()));
	
	}
	
	
}