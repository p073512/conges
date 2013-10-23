<?php
#region MBA
// application/forms/Personne.php
 
class Default_Form_OutilsForm extends Default_Form_MyForm
{   
	/*Nommage Variable:
	 * i pour input.
	 * e pour element.
	 * f pour form.
	 */
	protected $_iDateDebut;
	protected $_iDateFin;
	protected $_iDebutMidi;
	protected $_iFinMidi;
	protected $_iCsm;
	protected $_iAlsaceMoselle;
	protected $_iSubmit;
	
	public function getiDateDebut(){
	return $this->_iDateDebut;
	}
	
	public function getiDateFin(){
    return $this->_iDateFin;
	}
	
	public function getiDebutMidi()
	{
	return $this->_iDebutMidi;
	}
	
	public function getiFinMidi()
	{
	return $this->_iFinMidi;
	}
	
	public function getiCsm()
	{
		return $this->_iCsm;
	}
	public function getiAlsaceMoselle()
	{
		return $this->_iAlsaceMoselle;
	}
 
	public function getiSubmit()
	    {
	    	return $this->_iSubmit;
	    }
	 
	public function init(){
		
		parent::init();
		
		
		
		/*
         * Date debut type jquery_x datepicker
         * 
         */
		
		$_iDateDebut = new ZendX_JQuery_Form_Element_DatePicker('dateDebut');
		$_iDateDebut->setJQueryParam('dateFormat', 'yy-mm-dd');
		$_iDateDebut->setLabel("Date debut");
		$_iDateDebut->addValidator('date',true,array('date' => 'yy-MM-dd'));
		$_iDateDebut->addDecorator('Ftextinput', array('label'));
		
		$this->_iDateDebut = $_iDateDebut;
		
		
				 /*
         * Date Fin type jquery_x datepicker
         * 
         */
        
		$_iDateFin = new ZendX_JQuery_Form_Element_DatePicker('dateFin');
		$_iDateFin->setJQueryParam('dateFormat', 'yy-mm-dd');
		$_iDateFin->setLabel("Date fin");
		$_iDateFin->setRequired(true);
		$_iDateFin->addValidator('date',true,array('date' => 'yy-MM-dd'));
	
	    $_iDateFin->addDecorator('Ftextinput', array('label'));
		
        $this->_iDateFin =  $_iDateFin;
	
		  $this->_iDebutMidi = new Zend_Form_Element_Checkbox('DebutMidi');
		  $this->_iDebutMidi->setLabel('Début Midi');
	

             
		  $this->_iFinMidi = new Zend_Form_Element_Checkbox('FinMidi');
		  $this->_iFinMidi->setLabel('Fin Midi');
		  
		  
		
		     
		  $sub =   new Zend_Form_Element_submit( 'creer',
		   array('label' => 'valider'));
		  $sub->removeDecorator('wrapper');
		  $this->_iSubmit = $sub;
		  
		  
		  $this->_iCsm = new Zend_Form_Element_Checkbox('csm');
		  $this->_iCsm->setLabel('CSM?');
		  
		  $this->_iAlsaceMoselle = new Zend_Form_Element_Checkbox('AlsaceMoselle');
		  $this->_iAlsaceMoselle->setLabel('AlsaceMoselle');
		  
		  

		 
	
		  
		
		
		  $this->addElements(array( $this->getiDateDebut(),
		                     $this->getiDebutMidi(),
	                         $this->getiDateFin(),
	                         $this->getiFinMidi(),
	                         $this->getiCsm(),
	                         $this->getiAlsaceMoselle(),
	                         $this->getiSubmit(),
	                        )); 
		  
		  
		  
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
}
	