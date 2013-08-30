<?php
#region MBA
// application/forms/TPersonne.php
 
class Default_Form_SoldePersonneAnnee extends Default_Form_MyForm
{   
	/*Nommage Variable:
	 * i pour input.
	 * e pour element.
	 * f pour form.
	 * 
	 * 
	 */
	
	protected $iPersonne; 
	protected $iAnneeReference;
	protected $iQ2;
	protected $iSubmit;
	/*
	 * getters  pour récupérer les éléments créé.
	 * pas de setters les éléments sont assignés dans init().
	 * 
	 */
    public function getiPersonne()
        {
    	return $this->iPersonne;
        }
        
	public function getiAnneeReference()
	    {
	    	return $this->iAnneeReference;
	    }
	
	public function getiQ2()
	    {
	    	return $this->iQ2;
	    }
    
    public function getiSubmit()
	    {
	    	return $this->iSubmit;
	    }
	    
public function init()
	{
	 	
     	parent::init();
     	// nom par défaut du form si on veut la changé dans la classe fille il faut rappeller la méthode.
     	 $this->setName('init_solde_personne_annee');
     	 // Method par défaut du form
         $this->setMethod('post');

   		 /*
         * Liste déroulante "Personne" peuplée à partir de la BD via setDbOptions
         */
		 
   		 $this->iPersonne = $this->createElement('select','personne' ,array(
		    'label'  => 'Personne',
		    'name' => 'personne',
   		 ));
   		 
		 /*
         * Champ input type text nom 
         * Validation : requis,
         * Filtre : StringTrim (supprime les espaces en début et fin ),
         *          StringTags (supprime les balises html et php)
         */
         
   		$this->iAnneeReference = $this->createElement('text', 'annee_reference',array(
   		 'label' => 'Annee de reference',
   		 'placeholder' => 'format attendu : AAAA',
   		 'required' => true,
   		 
   		
   		 ));
		 
		 /*
         * Champ type text 
         * Validation : requis,NotEmpty,Regex (entre 0 et 100)
         * Filtre : StringTrim (supprime les espaces en début et fin ),
         *          StringTags (supprime les balises html et php)
         */
		
		 $this->iQ2 = $this->createElement('text','q2',array(
		 'label' => 'Q2',
		 'value' => '0',
		 'required' => true,
		 'filters' => array('StringTrim','StripTags'),
		 'description' => 'Entre 0 et 10',
		 'validators' => array(  array('Regex', 
				                        true,
				                        array('pattern'=> '/^[1-9]?[0-9]{1}$|^10$/',
				                        'messages' => array(
                                        'regexNotMatch'=>'Q2 : valeurs accept�es entre 0 et 10'
                               ))),
		                       ),
		  ));
		
		  $this->iSubmit =   new Zend_Form_Element_submit( 'creer',
		   array('label' => 'valider'));
      
		  //ajout des �l�ments au formulaire
			$this->addElements(array(
								 	$this->iPersonne, 
									$this->iAnneeReference,
									$this->iQ2,
									$this->iSubmit));  
	}
	
}