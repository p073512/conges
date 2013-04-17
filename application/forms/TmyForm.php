<?php
#region MBA
// application/forms/TPersonne.php
 
class Default_TmyForm_TPersonne extends Zend_Form
{   
	protected $iNom;
	protected $iPrenom;
	protected $iDate_entree;
	protected $iDate_debut;
	protected $iEntite;
	protected $iPole;
	protected $iPourcentage;
	protected $iStage;
	protected $iSubmit;
	
    public function getiNom()
    {
    	return $this->iNom;
    }
	public function getiPrenom()
	    {
	    	return $this->iPrenom;
	    }
	public function getiDate_entree()
	    {
	    	return $this->iDate_entree;
	    }
	public function getiDate_debut()
	    {
	    	return $this->iDate_debut;
	    }
	public function getiEntite()
	    {
	    	return $this->iEntite;
	    }
	public function getiPole()
	    {
	    	return $this->iPole;
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
	 	
     	// La méthode HTTP d'envoi du formulaire
   		 $this->setMethod('post');
   		  $decorators = array('label','ViewHelper','Errors','description','htmltag','DtDdWrapper');
   		 foreach ($decorators as $decorator)
   		 { $this->removeDecorator($decorator);}
   		 
   		
   		 // le chemin du décorateur est défini.
           $this->addElementPrefixPath('My_Form_Decorators',
                       APPLICATION_PATH.'../../My/Form/Decorators',
		              'decorator');
   		 /*
   		  * Test decorator ftextinput
   		  * 
   		  */
   		
   		 
		 /*
         * Champ input type text nom 
         * Validation : requis,
         * Filtre : StringTrim (supprime les espaces en début et fin ),
         *          StringTags (supprime les balises html et php)
         */
   		$iNom =  new Zend_Form_Element('text','Nom',array(
   		 'label' => 'Nom',
   		 'placeholder' => 'Entrez nom..',
   		 'required' => true,
   		 'description' => 'required',
   		 'decorators' => array(
            'Ftextinput', array()),
   		 
   		 
   		 ));
   		 
        
        /*
         * Champ input type text Prenom 
         * Validation : requis,
         * Filtre : StringTrim (supprime les espaces en début et fin ),
         *          StringTags (supprime les balises html et php)
         */
        $iPrenom =  new Zend_Form_Element('text', 'Prenom', array(
            'label'      => 'Prenom',
            'required'   => true,
            'placeholder' => 'Entrez prenom..',
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
               
            ),
            'description' =>'description ici..',
            'decorators' => array(
            'Ftextinput', array()),
            
           
        ));
        /*
         * Date entrée type jquery_x datepicker
         * 
         */
        
		$iDate_entree = new ZendX_JQuery_Form_Element_DatePicker('date_entree');
		$iDate_entree->setJQueryParam('dateFormat', 'yy-mm-dd');
		$iDate_entree->setLabel("Date d'entree");
		$iDate_entree->setRequired(true);
		$iDate_entree->addValidator('date',true,array('date' => 'yy-MM-dd'));
		$iDate_entree->addDecorator('Ftextinput', array('label'));
		
        
		$this->addElement($date_entree_pr);
		
		/*
         * Date debut type jquery_x datepicker
         * 
         */
		
		$iDate_debut = new ZendX_JQuery_Form_Element_DatePicker('date_debut');
		$iDate_debut->setJQueryParam('dateFormat', 'yy-mm-dd');
		$iDate_debut->setLabel("Date debut");
		$iDate_debut->addValidator('date',true,array('date' => 'yy-MM-dd'));
		$iDate_debut->addDecorator('Ftextinput', array('label'));
		
		
		
        
		
		/*
         * Liste déroulante "Pôle" peuplée à partir de la BD via setDbOptions
         */
		
		
		$iPole = new Zend_Form_Element('select','pole',array(
		    'label'  => 'Pole',
		    'name' => 'pole',
			'decorators' => array(
	            'Fselect', array()),
		    ));
                            
		
		
		/*
         * Liste déroulante "Fonctions" peuplée à partir de la BD via setDbOptions
         */
		 
		$iPole = new Zend_Form_Element('select', 'fonctions' ,array(
		    'label'  => 'Fonctions',
		     'name' => 'fonctions',
		     'decorators' => array(
                'Fselect', array()),
		   
		    
		 ));
		 
		 /*
         * Champ type text 
         * Validation : requis,NotEmpty,Regex (entre 0 et 100)
         * Filtre : StringTrim (supprime les espaces en début et fin ),
         *          StringTags (supprime les balises html et php)
         */
		
		 $iPourcentage = new Zend_Form_Element('text','pourcentage',array(
		 'label' => 'Pourcentage',
		 'value' => '100',
		 'required' => true,
		 'filters' => array('StringTrim','StripTags'),
		 'description' => 'description : entre 0 et 100',
		 'validators' => array(  array('Regex', 
				                        true,
				                        array('pattern'=> '/^[1-9]?[0-9]{1}$|^100$/',
				                        'messages' => array(
                                        'regexNotMatch'=>'Pourcentage : Seulement valeurs entre 0 et 1 accept�es'
                               ))),
		                       ),
		 'decorators' => array(
            'Ftextinput', array()),
		 
		  ));
		
		  
		  $iStage =   new Zend_Form_Element('checkbox','Stage',array(
		  'label' => 'stage ?',
		  
		  ));
		  
		  
		  
		  $iSubmit =   new Zend_Form_Element('submit', 'creer',
		   array('label' => 'valider'));
      
    
	}
	
    /**
     * function @return : zend_element_select($elementNam) peuplé | null
     * function @param :
     * &$objet : la référence du type de l'objet dont on veut récupèrer la liste depuis la BD 
     * 
     * $id_function : le nom de la fonction qui retourne le Id de l'objet, par défaut getId sinon il faut
     * renseigné le nom de la fonction. [optionnel]
     * 
     * $libelle_function : Par défaut getLibelle() sinon renseignez nom de méthode. [optionnel]
     * 
     * $str[] : tableau dans lequel seront récupérés les objets depuis la base. [optionnel]
     * 
     * desc : fonction qui sert à peupler un element du formulaire select à partir d'une liste d'objet
     * récupèrée de la base de donnée. la fonction s'applique sur "tout" type d'objet.
     */
	public function setDbOptions($elementName, &$object, $id_function ='getId', $libelle_function = 'getLibelle', $str = array())
				{
					// v�rifie si le champ en question est un "select"
					if($this->getElement($elementName) instanceof Zend_Form_Element_Select)
					{
						// check si l'objet à la méthode getMapper (pour la liaison avec la base)
						if(method_exists($object, "getMapper"))
						{ 
						  $mapper = $object->getMapper();
						// check la méthode fetchAll() existe
						  if(method_exists($mapper, "fetchAll"))
							{ 
							   $str = $object->getMapper()->fetchAll($str);
						    }
							$objArray = array();
						    foreach($str as $p)
							{
								/* remplie le tableau objArray avec les id et libelle 
								 * obtenues via les fonction variable $id_function et $libelle_function
								*/
								$objArray [$p->$id_function()] = $p->$libelle_function();
							}
							/*
							 * Première option affiche le libellé choissisez pour forcer le user
							 * à sélectionner une ligne , id value à x pour s'assurer qu'il ne sera
							 * jamais un id de la base de donnée (id de type int)
							 */
							
							$selectOptionLibelle = array('x' => 'Choisissez :' );
							$objArray = (array)$selectOptionLibelle + (array)$objArray;
							
						

			  	return $this->getElement($elementName)->setOptions(array('MultiOptions' =>  $objArray));
					}	
					return; }
				return ;			}
				

		
		
	
}
#endregion MBA