<?php
#region MBA
// application/forms/TPersonne.php
 
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
	protected $iPole;
	protected $iFonction;
	protected $iPourcentage;
	protected $iStage;
	protected $iSubmit;
	/*
	 * getters  pour récupérer les éléments créé.
	 * pas de setters les éléments sont assignés dans init().
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
     	// nom par défaut du form si on veut la changé dans la classe fille il faut rappeller la méthode.
     	 $this->setName('Create_Personne');
     	 // Method par défaut du form
         $this->setMethod('post');
   		 
		 /*
         * Champ input type text nom 
         * Validation : requis,
         * Filtre : StringTrim (supprime les espaces en début et fin ),
         *          StringTags (supprime les balises html et php)
         */
         
   		$this->iNom = $this->createElement('text', 'Nom',array(
   		 'label' => 'Nom',
   		 'placeholder' => 'Entrez nom..',
   		 'required' => true,
   		 
   		
   		 ));
   		 
   		 
        
        /*
         * Champ input type text Prenom 
         * Validation : requis,
         * Filtre : StringTrim (supprime les espaces en début et fin ),
         *          StringTags (supprime les balises html et php)
         */
        $this->iPrenom = $this->createElement('text', 'Prenom', array(
            'label'      => 'Prenom',
            'required'   => true,
            'placeholder' => 'Entrez prenom..',
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
               
            ),
           
            
           
        ));
        
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
		
        $this->iDateEntree =  $iDateEntree;
	
		
		/*
         * Date debut type jquery_x datepicker
         * 
         */
		
		$iDateDebut = new ZendX_JQuery_Form_Element_DatePicker('date_debut');
		$iDateDebut->setJQueryParam('dateFormat', 'yy-mm-dd');
		$iDateDebut->setLabel("Date debut");
		$iDateDebut->addValidator('date',true,array('date' => 'yy-MM-dd'));
		$iDateDebut->addDecorator('Ftextinput', array('label'));
		
		$this->iDateDebut = $iDateDebut;
		
        
		
		/*
         * Liste déroulante "Pôle" peuplée à partir de la BD via setDbOptions
         */
		
		
		$this->iPole = $this->createElement('select', 'pole',array(
		    'label'  => 'Pole',
		    'name' => 'pole',
			
		    ));
                            
		
		
		/*
         * Liste déroulante "Fonctions" peuplée à partir de la BD via setDbOptions
         */
		 
		$this->iFonction = $this->createElement('select','fonction' ,array(
		    'label'  => 'Fonctions',
		     'name' => 'fonction',
		     
		   
		    
		 ));
		 
		 /*
         * Champ type text 
         * Validation : requis,NotEmpty,Regex (entre 0 et 100)
         * Filtre : StringTrim (supprime les espaces en début et fin ),
         *          StringTags (supprime les balises html et php)
         */
		
		 $this->iPourcentage = $this->createElement('text','pourcentage',array(
		 'label' => 'Pourcentage',
		 'value' => '100',
		 'required' => true,
		 'filters' => array('StringTrim','StripTags'),
		 'description' => 'Entre 0 et 100',
		 'validators' => array(  array('Regex', 
				                        true,
				                        array('pattern'=> '/^[1-9]?[0-9]{1}$|^100$/',
				                        'messages' => array(
                                        'regexNotMatch'=>'Pourcentage : Seulement valeurs entre 0 et 1 accept�es'
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