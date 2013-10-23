<?php
#region MBA
// application/forms/Personne.php
 
class Default_Form_BasePersonne extends Default_Form_MyForm
{   
	/*Nommage Variable:
	 * i pour input.
	 * e pour element.
	 * f pour form.
	 * 
	 * 
	 */
	
	protected $iNom; 
	protected $iPrenom;
	protected $iDateEntree;
	protected $iDateDebut;
 	protected $iDateFin;    // MTA  
	protected $iPole;
	protected $iFonction;
	protected $iPourcentage;
	protected $iStage;
	protected $iSubmit;
	/*
	 * getters  pour rÃ©cupÃ©rer les Ã©lÃ©ments crÃ©Ã©.
	 * pas de setters les Ã©lÃ©ments sont assignÃ©s dans init().
	 * 
	 */
    	public function getiNom()
        {
    	return $this->iNom;
        }
  
    	public function getiPrenom()
	    {
	    	return $this->iPrenom;
	    }
	
	 	public function getiDateEntree()
	    {
	    	return $this->iDateEntree;
	    }
    
     	public function getiDateDebut()
	    {
	    	return $this->iDateDebut;
	    }
	
        public function getiDateFin()  // MTA 
	    {
	    	return $this->iDateFin;
	    }
	
		public function getiPole()
	    {
	    	return $this->iPole;
	    }
		public function getiFonction()
	    {
		return $this->iFonction;
	    }
	
  	    public function getiPourcentage()
	    {
	    	return $this->iPourcentage;
	    }
	
   		 public function getiStage()
	    {
	    	return $this->iStage;
	    }
	    
    	public function getiSubmit()
	    {
	    	return $this->iSubmit;
	    }

	    
   	 public function init()
	 {
	 	
     	parent::init();
     	// nom par dÃ©faut du form si on veut la changÃ© dans la classe fille il faut rappeller la mÃ©thode.
     	 $this->setName('Create_Personne');
     	 // Method par dÃ©faut du form
         $this->setMethod('post');
   		 
		 /*
         * Champ input type text nom 
         * Validation : requis,
         * Filtre : StringTrim (supprime les espaces en dÃ©but et fin ),
         *          StringTags (supprime les balises html et php)
         */
         

   		 $this->iNom = $this->createElement('text', 'Nom',array(
   		 'label' => 'Nom',
   		 'placeholder' => 'Entrez nom..',
   		 'required' => true,
  		 'ErrorMessages' => array("Nom invalide !"),
   		 'validators' => array(  array('Regex', 
				                         true,
				                         array('pattern'=> "/^([a-zA-Z'àâéèêôùûçÀÂÉÈÔÙÛÇ[:blank:]-]{1,30})$/",
				                        'messages' => array("regexNotMatch"=>"ne doit pas contenir de caractères spéciaux")
                                       )
                               )
   		 )));
   		 
   		 
        
        /*
         * Champ input type text Prenom 
         * Validation : requis,
         * Filtre : StringTrim (supprime les espaces en dÃ©but et fin ),
         *          StringTags (supprime les balises html et php)
         */
            $this->iPrenom = $this->createElement('text', 'Prenom', array(
            'label'      => 'Prenom',
            'required'   => true,
            'placeholder' => 'Entrez prenom..',
            'filters'    => array('StringTrim','StripTags'),
            'ErrorMessages' => array("Prenom invalide !"),            
            'validators' => array(  array('Regex', 
				                        true,
				                        array('pattern'=> "/^([a-zA-Z'àâéèêôùûçÀÂÉÈÔÙÛÇ[:blank:]-]{1,30})$/",
				                        'messages' => array("regexNotMatch"=>"ne doit pas contenir de caractères spéciaux")
                                       )
                               )
   		 )));
        
        /*
         * Date entrée type jquery_x datepicker
         * 
         */

        
        $iDateEntree = new ZendX_JQuery_Form_Element_DatePicker('date_entree');
		$iDateEntree->setJQueryParam('dateFormat', 'yy-mm-dd');
		$iDateEntree->setLabel("Date d'entree");
	    $iDateEntree->setRequired(true);
		$iDateEntree->addValidator('date',true,array('date' => 'yy-MM-dd'));
		$iDateEntree->addDecorator('Ftextinput', array('label'));
	    $iDateEntree->setErrorMessages(array("Date d'entree invalide !"));
	    $iDateEntree->setAttrib('placeholder', "choisir une date d'entree ...");
		$this->iDateEntree =$iDateEntree;
        
	
		
		/*
         * Date debut type jquery_x datepicker
         * 
         */

		
		$iDateDebut = new ZendX_JQuery_Form_Element_DatePicker('date_debut');
		$iDateDebut->setJQueryParam('dateFormat', 'yy-mm-dd');
		$iDateDebut->setLabel("Date debut");
		$iDateDebut->setRequired(true);
		$iDateDebut->addValidator('date',true,array('date' => 'yy-MM-dd'));
		$iDateDebut->addDecorator('Ftextinput', array('label'));
	    $iDateDebut->setErrorMessages(array("Date debut invalide !"));//  $iDateDebut->setErrorMessages(array("Date debut invalide !"));
	    $iDateDebut->setAttrib('placeholder', "choisir une date debut ...");
		$this->iDateDebut = $iDateDebut;
		
		
		
		
		
		
		/*
         * Date fin type jquery_x datepicker
         * 
         */

		$iDateFin = new ZendX_JQuery_Form_Element_DatePicker('date_fin');
		$iDateFin->setJQueryParam('dateFormat', 'yy-mm-dd');
		$iDateFin->setLabel("Date fin");
		$iDateFin->addValidator('date',true,array('date' => 'yy-MM-dd'));
		$iDateFin->addDecorator('Ftextinput', array('label'));
		$iDateFin->setErrorMessages(array("Date fin invalide !"));
	    $iDateFin->setAttrib('placeholder', "choisir une date fin ...");
		$this->iDateFin = $iDateFin;
		

		
        
		
		/*
         * Liste dÃ©roulante "PÃ´le" peuplÃ©e Ã  partir de la BD via setDbOptions
         */
		
		
		$this->iPole = $this->createElement('select', 'pole',array(
		'label'  => 'Pole',
		'name' => 'pole',
			
		    ));
                            
		
		
		/*
         * Liste dÃ©roulante "Fonctions" peuplÃ©e Ã  partir de la BD via setDbOptions
         */
		 
		$this->iFonction = $this->createElement('select','fonction' ,array(
		'label'  => 'Fonctions',
		'name' => 'fonction',
		     
		   
		    
		 ));
		 
		 /*
         * Champ type text 
         * Validation : requis,NotEmpty,Regex (entre 0 et 100)
         * Filtre : StringTrim (supprime les espaces en dÃ©but et fin ),
         *          StringTags (supprime les balises html et php)
         */
		
		 $this->iPourcentage = $this->createElement('text','pourcent',array(
		 'label' => 'Pourcentage',
		 'value' => '100',
		 'required' => true,
		 'filters' => array('StringTrim','StripTags'),
		 'description' => 'Entre 0 et 100',
		 'validators' => array(  array('Regex', 
				                        true,
				                        array('pattern'=> '/^[1-9]?[0-9]{1}$|^100$/',
				                        'messages' => array(
                                        'regexNotMatch'=>'Pourcentage : Seulement valeurs entre 0 et 1 acceptées'
                               ))),
		                       ),
		 
		 
		  ));
		
		  
		  $this->iStage =   new Zend_Form_Element_Checkbox( 'Stage',array(
		  'label' => 'stage ?',
		  
		  ));
		  
		  
		  
		  $this->iSubmit =   new Zend_Form_Element_submit( 'creer',
		   array('label' => 'valider'));
      
    
	}
	
   
				

		
		
	
}