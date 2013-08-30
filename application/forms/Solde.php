<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Form_Solde extends Default_Form_MyForm
{
	//l'initialisation et la configuration des éléments de notre formulaire
	//se trouveront dans le constructeur de la classe,
	//de cette façon tous les éléments de notre formulaire seront créés lors
	//de l'instaniation d'un objet de type Default_Form_Users
	protected $_iAnnee;
	protected $_iSubmit;
	
     public function getiAnnee()
        {
    	return $this->_iAnnee;
        }
        
     public function getiSubmit()
	    {
	    	return $this->_iSubmit;
	    }
	    
	    public function init()
	 {

	 	
	 	parent::init();
        
        // si on veut changer le nom ou la méthode du form appliquez ces méthodes.
		$this->setName('initialiserSolde');
	    $this->setMethod('post');
		 /*
         * Champ input type text nom 
         * Validation : requis,
         * Filtre : StringTrim (supprime les espaces en début et fin ),
         *          StringTags (supprime les balises html et php)
         */
         
			$date = new DateTime();
	        $year = $date->format('Y');

   		 $this->_iAnnee = $this->createElement('text', 'Annee',array(
   		 'label' => 'Annee',
   		 'placeholder' => 'Entrez une année..',
   		 'required' => true,
  		 'filters'=> array('StripTags','StringTrim'),
   		 'validators' => array( 
                                 array('Between',
                                   true,
                                     array('min' => $year-1, 'max' => $year+1),
                                       array('Messages' => 'Année entre '.($year-1).' et '.($year+1).' !')),
   		                         array('NotEmpty',
   		                            true,
   		                               array('Messages'=>'Veuillez saisir une année !')),
   		                         array('stringLength',
   		                             true,
   		                               4,
   		                                 array('Messages'=>"L'année doit être sur 4 digits"))
   		                         )
   		 ));
   		 
   		  $this->iSubmit =   new Zend_Form_Element_submit( 'creer',
		   array('label' => 'Initialiser'));
   		 
	    $this->addElements(array($this->getiAnnee(),
	                             $this->getiSubmit(),)); 
	    
	 }
	 
	
	 
	
	/**
	public function  __construct($options = null) {
		parent::__construct($options);

		//donne un nom à notre formulaire
		$this->setName('solde');
		$this->removeDecorator('DtDdWrapper');
		$this->removeDecorator('HtmlTag');
		$this->removeDecorator('Label');
		//création d'un élément input de type hidden
		$id = new Zend_Form_Element_Hidden('id');

		
		$annee_reference_sl = new Zend_Form_Element_Text('annee_reference_sl');
		$annee_reference_sl->setRequired(true);
		$annee_reference_sl->addFilter('StripTags');
		$annee_reference_sl->addFilter('StringTrim');
		$annee_reference_sl->addValidator('NotEmpty');
		$annee_reference_sl->addValidator('StringLength',4);
		$annee_reference_sl->addValidator( new Zend_Validate_Between(array('min' => ($year-1),'max' => ($year+1))),  true);
		$annee_reference_sl->removeDecorator('DtDdWrapper');
		$annee_reference_sl->removeDecorator('HtmlTag');
		$annee_reference_sl->removeDecorator('Label');
		
		// bouton de creation
		$submit_sl = new Zend_Form_Element_Submit('submit_sl');
		$submit_sl->removeDecorator('DtDdWrapper');
		$submit_sl->removeDecorator('HtmlTag');
		$submit_sl->removeDecorator('Label');

		//ajout des éléments au formulaire
		$this->addElements(array(
		$id,
		$annee_reference_sl,
		$submit_sl));
	
	}
	**/
}
