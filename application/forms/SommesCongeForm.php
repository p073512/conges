<?php
class Default_Form_SommesCongeForm extends Default_Form_MyForm
{   
	/*Nommage Variable:
	 * i pour input.
	 * e pour element.
	 * f pour form.
	 */
	protected $_eSelectPersonne;
	protected $_eSelectAnnee;
	protected $_iSubmit;
	
	
	public function geteSelectPersonne()
	{
		return $this->_eSelectPersonne;
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
     	// nom par défaut du form si on veut la changé dans la classe fille il faut rappeller la m�thode.
     	 $this->setName('Calendrier');
     	 // Method par défaut du form
         $this->setMethod('post');
         $this->removeAllDecorators();
   		 
	$this->_eSelectPersonne = $this->createElement('select','personne' ,array(
		    'label'  => 'Personne',
		    'name' => 'personne',));
	
	
	
	$selectAnnee = $this->createElement('select','annee' ,array(
									    'label'  => 'Annee',
									    'name' => 'annee',));
	
	$date = new DateTime();
	$year = $date->format('Y');
	$selectAnnee->addMultiOptions(array(    $year -1 => $year -1,
					                        $year => $year,
					                        $year +1 => $year + 1  ));
	
	$this->_eSelectAnnee = $selectAnnee;
	
	 $this->_iSubmit =   new Zend_Form_Element_Submit( 'chargerSommeConge',
				 					  array('label' => 'Charger Les congés'));
				 
       
	$this->addElements(array($this->geteSelectPersonne(),
	                         $this->geteSelectAnnee(),
	                         $this->getiSubmit()));
	
	}
	
	
}