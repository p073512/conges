<?php
#region MBA
// application/forms/TPersonne.php
 
class Default_Form_MyForm extends Zend_Form
{   
    /**
     * Les décorateurs définis qu'on veut appliqué sont stockés dans $eDecorator
     * sinon decorateur 'label' est assigné.
     * 
     * @var $eDecorator tableau contient 
     */
	private $eDecorator =array('Zend_Form_Element_Text'=>'Ftextinput', 
                                 'Zend_Form_Element_Select'=>'Fselect',
									'Zend_Form_Element_Checkbox' =>'label',
	                                    'Zend_Form_Element_Submit'=>'label');
	
	
	
	/**
	 * Tableau qui contiendra les éléments créés par la méthode createElement.
	 * (createElement est 'overridé' dans cette classe)
	 * @var $elements tableau de type Zend_Form_Element
	 */
	protected $elements = array();
    
    
    
    
    /**
	 * Fonction qui supprime tous les décorateurs par défault.
	 * supprime les décorateurs du formulaire si pas de paramètre passés.
	 * supprime les décorateurs de l'élément si nom de l'élément(Zend_Form_Element->getName()) est passé en paramètre.
	 *
	 * @param null|string $name (optionnel)
	 */
    public function removeAllDecorators($name= null)
	{
	 // définie les décorateurs par défaut.
	 $decorators = array('label','ViewHelper','Errors','description','htmltag','DtDdWrapper');
		
	 if(isset($name)) // si nom de l'élément est passé
		{    
			 foreach ($decorators as $decorator)
			// recupére l'élement stocké dans elements[] et supprime ses décorateurs par défaut
	    	 { $this->elements[$name]->removeDecorator($decorator);
	    	   }
			    
	    	
	    }  
	    else 
        {
		     foreach ($decorators as $decorator)
		     // pointe sur le formulaire en question supprime ses décorateurs par défaut
	     	 { $this->removeDecorator($decorator);}

	     	
	    }
	    return $this;
	}
	
	/**
	 * 
	 * Override de la fonction Zend_Form::createElement() :
	 * à la création d'un élément depuis la classe courante ou les classe filles
	 * via createElement(); les décorateurs par défaut sont supprimés (removeAlldecorators)
	 * et les décorateurs définis dans $eDecorator sont appliqués en fonction du type 
	 * de l'élément créé.
	 *
	 * (non-PHPdoc)
	 * @see Zend_Form::createElement()
	 */
	public function createElement($type, $name, $options=null)
	{
		$element = parent::createElement($type, $name, $options);
		
		/* on stocke la référence de l'élément créé dans le tableau
		* vu que createElement n'enregistre pas l'élément.
		*/
		$this->elements[$name] = &$element;
		
		// suppression des décorateurs de l'élément créé
		$this->removeAllDecorators($name);
		// on applique l'un des décorateurs définies en fonction du type de l'élément récemment créé.
		
		$this->elements[$name]->setDecorators((array)$this->eDecorator[$element->getType()]);
       
		$element = $this->elements[$name];
		
		return $element;
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
	

	public function setDbOptions($elementName, &$object, $id_function ='getId', $libelle_function = 'getLibelle',$where = null, $str = array())
				{
					// vérifie si le champ en question est un "select"
					if($this->elements[$elementName] instanceof Zend_Form_Element_Select)
					{
						// check si l'objet à la méthode getMapper (pour la liaison avec la base)
						if(method_exists($object, "getMapper"))
						{ 
						  $mapper = $object->getMapper();
						// check la méthode fetchAll() existe
						  if(method_exists($mapper, "fetchAll"))
							{ 
							   $str = $object->getMapper()->fetchAll($str,$where);
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
							

			  	return $this->elements[$elementName]->setOptions(array('MultiOptions' =>  $objArray));
					}	
					return; }
				return ;			
				}
	
	/*
	 * Initialisation du formulaire.
	 * 
	 */
	public function init()
	{
		 // le chemin du décorateur est défini.
           $this->addElementPrefixPath('My_Form_Decorators',
                       APPLICATION_PATH.'../../My/Form/Decorators',
		              'decorator');
	 	
   		
   		 
	}
	
				

		
		
	
}
#endregion MBA