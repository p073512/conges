<?php
//"Default" correspond au namespace que nous avons d�fini dans le bootstrap
class Default_Form_Proposition extends Default_Form_MyForm
{
	//l'initialisation et la configuration des �l�ments de notre formulaire
	//se trouveront dans le constructeur de la classe,
	//de cette fa�on tous les �l�ments de notre formulaire seront cr��s lors
	//de l'instaniation d'un objet de type Default_Form_Users
	
	public function init()
	{
		parent::init();
		
		// nom du form 
     	 $this->setName('Create_Proposition');
     	 // Method par défaut du form
         $this->setMethod('post');
		
		
		
	   	 /*
         * Champ input type text Nom Prenom 
         * Validation : requis,
         */
		
         
         
		$iNomPrenom = $this->createElement('select', 'NomPrenom', array(
			  															'label'  => 'Nom Prenom',
			  															'name' => 'NomPrenom',
																		) 
	         							   ); 
	         							   
	     							   
	         							   
        /*
         * Date debut type jquery_x datepicker
         * 
         */
		
		$iDateDebut = new ZendX_JQuery_Form_Element_DatePicker('date_debut');
		$iDateDebut->setJQueryParam('dateFormat', 'yy-mm-dd');
		$iDateDebut->setLabel("Date debut");
		$iDateDebut->addValidator('date',true,array('date' => 'yy-MM-dd'));
		$iDateDebut->addDecorator('Ftextinput', array('label'));  
		$iDateDebut->setRequired(true);   
		$iDateDebut->setErrorMessages(array("Champs vide !"));
		/*
         * Debut midi type checkbox 
         * 
         */

		 $iDebutMidi =   new Zend_Form_Element_Checkbox( 'DebutMidi',array('label' => 'Debut Midi'));
		
	    /*
         * Date fin type jquery_x datepicker
         * 
         */
		
		$iDateFin = new ZendX_JQuery_Form_Element_DatePicker('date_fin');
		$iDateFin->setJQueryParam('dateFormat', 'yy-mm-dd');
		$iDateFin->setLabel("Date fin");
		$iDateFin->addValidator('date',true,array('date' => 'yy-MM-dd'));
		$iDateFin->addDecorator('Ftextinput', array('label'));
        $iDateFin->setRequired(true); 
        $iDateFin->setErrorMessages(array("Champs vide !"));
		
		/*
         * Debut midi type checkbox 
         * 
         */

		  $iFinMidi =   new Zend_Form_Element_Checkbox( 'FinMidi',array('label' => 'Fin Midi'));
		
		/*
         * Debut midi type checkbox 
         * 
         */
		   $iSubmit =   new Zend_Form_Element_submit( 'Valider',
		   array('label' => 'Valider'));
		   

			
		   
		   
		//ajout des éléments au formulaire
		$this->addElements(array(
								 $iNomPrenom,
								 $iDateDebut,
								 $iDebutMidi,
								 $iDateFin,
								 $iFinMidi,
								 $iSubmit 
								 )
							);   
		
	}
	

}
