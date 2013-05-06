<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Form_Conge extends Default_Form_MyForm
{
	//l'initialisation et la configuration des éléments de notre formulaire
	//se trouveront dans le constructeur de la classe,
	//de cette façon tous les éléments de notre formulaire seront créés lors
	//de l'instaniation d'un objet de type Default_Form_Users
	

	public function init()
	{
		parent::init();
		
		// nom du form 
     	 $this->setName('Create_Conge');
     	 // Method par défaut du form
         $this->setMethod('post');

         
        /*
         * Nom Prenom type select
         */ 
		$iRessource = $this->createElement('select', 'Ressource', array(
			  															'label'  => 'Ressource ',
			  															'name' => 'Ressource',
		                                                                'required'   => 'true',
																		) 
	         							   ); 
						   
	         							   
        /*
         * Type de conge type select
         */
	    $iTypeConge = $this->createElement('select', 'TypeConge', array(
			  															'label'  => 'Type de conge ',
			  															'name' => 'TypeConge',
		                                                                'required'   => 'true',
																		) 
	         							   ); 
							   
        /*
         * Date debut type jquery_x datepicker
         * 
         */
		
		$iDebut = new ZendX_JQuery_Form_Element_DatePicker('Debut');
		$iDebut->setJQueryParam('dateFormat', 'yy-mm-dd');
		$iDebut->setLabel('Debut ');
		$iDebut->addValidator('date',true,array('date' => 'yy-MM-dd'));
		$iDebut->addDecorator('Ftextinput', array('label'));    
		$iDebut->setRequired(true); 
		$iDebut->setErrorMessages(array("Date D&eacute;but invalide !"));
	
	
		
		
		/*
         * Debut midi type checkbox 
         */

		 $iDebutMidi =   new Zend_Form_Element_Checkbox( 'DebutMidi',array('label' => 'Debut Midi'));
		
	    /*
         * Date fin type jquery_x datepicker
         * 
         */
		
		$iFin = new ZendX_JQuery_Form_Element_DatePicker('Fin');
		$iFin->setJQueryParam('dateFormat', 'yy-mm-dd');
		$iFin->setLabel('Fin ');
		$iFin->addValidator('date',true,array('date' => 'yy-MM-dd'));
		$iFin->addDecorator('Ftextinput', array('label'));
        $iFin->setRequired(true); 
        $iFin->setErrorMessages(array("Date Fin invalide !"));
		
		/*
         * Debut midi type checkbox 
         * 
         */

		  $iFinMidi =   new Zend_Form_Element_Checkbox('FinMidi',array('label' => 'Fin Midi'));
		
		  
	    /*
         * Ferme type checkbox 
         * 
         */

		  $iFerme =   new Zend_Form_Element_Checkbox('Ferme',array('label' => 'Ferme'));	  
		  
		  
		 /*
         * Annee de reference type textbox 
         * 
         */

		  $iAnneeRef =   new Zend_Form_Element_Text('AnneeRef',array('label' => 'Annee de reference'));	
		  $iAnneeRef->addValidator('regex',true,array('/^[0-9]{4}$/'));     
		  $iAnneeRef->addDecorator('Ftextinput', array('label'));
          $iAnneeRef->setRequired(true); 
          $iAnneeRef->setAllowEmpty(false);
          $iAnneeRef->setErrorMessages(array("Ann&eacute;e de reference invalide !"));
		  
		  
		/*
         * Submit type button 
         * 
         */
		   $iSubmit =   new Zend_Form_Element_submit( 'Valider',
		   array('label' => 'Valider'));

		
		   
		   
		//ajout des éléments au formulaire
		$this->addElements(array(
								 $iRessource,
								 $iDebut,
								 $iDebutMidi,
								 $iTypeConge,
								 $iFerme,
								 $iFin,
								 $iFinMidi,
								 $iAnneeRef,
								 $iSubmit
								 )
							);   
		
	}
	
	
}
