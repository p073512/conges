<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Form_Demande extends Zend_Form
{
	//l'initialisation et la configuration des éléments de notre formulaire
	//se trouveront dans le constructeur de la classe,
	//de cette façon tous les éléments de notre formulaire seront créés lors
	//de l'instaniation d'un objet de type Default_Form_Users
	public function  __construct($options = null) {
		parent::__construct($options);

		//donne un nom à notre formulaire
		$this->setName('conge');
		$this->removeDecorator('DtDdWrapper');
		$this->removeDecorator('HtmlTag');
		$this->removeDecorator('Label');
		$this->addElementPrefixPath('My_Decorator',
                            'My/Decorator/',
                            'decorator');
		
		
		//création d'un élément input de type hidden
		$id = new Zend_Form_Element_Hidden('id');
		
		$personne = new Default_Model_Personne();
		$result_set_personnes = $personne->fetchAll('id_entite =1');
		$tableau_personnes = array();
		foreach($result_set_personnes as $p)
		{
			$tableau_personnes[$p->getId()] = $p->getNom()."  ".$p->getPrenom();
		}

		$id_personne= new Zend_Form_Element_Select('id_personne');
		$id_personne->addMultiOptions($tableau_personnes);
		$id_personne->removeDecorator('DtDdWrapper');
		$id_personne->removeDecorator('HtmlTag');
		$id_personne->removeDecorator('Label');

		// dates
		$date_debut = new ZendX_JQuery_Form_Element_DatePicker('date_debut');
		$date_debut->setJQueryParams(array('dateFormat' => 'dd-mm-yy',
											'defaultDate' => '+1w',
											'changeMonth' => true,
											'numberOfMonths' => 2,
											'numberOfMonths' => 2));
		
//		$date_debut->setJQueryParam('dateFormat', 'yy-mm-dd');
		$date_debut->setRequired(true);
		$date_debut->addFilter('StripTags');
		$date_debut->addFilter('StringTrim');
		$date_debut->addValidator('NotEmpty');
		$date_debut->removeDecorator('DtDdWrapper');
		$date_debut->removeDecorator('HtmlTag');
		$date_debut->removeDecorator('Label');

		$date_fin = new ZendX_JQuery_Form_Element_DatePicker('date_fin');
		$date_fin->setJQueryParams(array('dateFormat' => 'yy-mm-dd',
											'defaultDate' => '+1w',
											'changeMonth' => true,
											'numberOfMonths' => 2,
											'numberOfMonths' => 2));
		
//		$date_fin->setJQueryParam('dateFormat', 'yy-mm-dd');
		$date_fin->setRequired(true);
		$date_fin->addFilter('StripTags');
		$date_fin->addFilter('StringTrim');
		$date_fin->addValidator('NotEmpty');
		$date_fin->removeDecorator('DtDdWrapper');
		$date_fin->removeDecorator('HtmlTag');
		$date_fin->removeDecorator('Label');

	
		// checkbox
		$mi_debut_journee = new Zend_Form_Element_Checkbox('mi_debut_journee');
		$mi_debut_journee->addValidator('NotEmpty');
		$mi_debut_journee->removeDecorator('DtDdWrapper');
		$mi_debut_journee->removeDecorator('HtmlTag');
		$mi_debut_journee->removeDecorator('Label');
		
		$mi_fin_journee = new Zend_Form_Element_Checkbox('mi_fin_journee');
		$mi_fin_journee->addValidator('NotEmpty');
		$mi_fin_journee->removeDecorator('DtDdWrapper');
		$mi_fin_journee->removeDecorator('HtmlTag');
		$mi_fin_journee->removeDecorator('Label');
		$ferme = new Zend_Form_Element_Checkbox('ferme');
		$ferme->addValidator('NotEmpty');
		$ferme->removeDecorator('DtDdWrapper');
		$ferme->removeDecorator('HtmlTag');
		$ferme->removeDecorator('Label');
		// anne reference
		// calcul pour la validation
		$date=date('D/d/m/Y');
		list($dcourt,$day, $month, $year) = explode("/", $date);
		$year =	(int)$year;
		
		$annee_reference = new Zend_Form_Element_Text('annee_reference');
		$annee_reference->setRequired(true);
		$annee_reference->addFilter('StripTags');
		$annee_reference->addFilter('StringTrim');
		$annee_reference->addValidator('NotEmpty');
		$annee_reference->addValidator('StringLength',4);
		$annee_reference->addValidator( new Zend_Validate_Between(array('min' => ($year-1),'max' => ($year+1))),  true);
       	$annee_reference->removeDecorator('DtDdWrapper');
		$annee_reference->removeDecorator('HtmlTag');
		$annee_reference->removeDecorator('Label');          
                                                         
                  
		
		//$annee_reference->addValidator('Between',(array('min' => 2011,'max' => 2013)));
		

			//création de la liste muli-selection
		$typeconge = new Default_Model_TypeConge();
		$result_set_types = $typeconge->fetchAll($str=array());
		$tableau_types = array();
		foreach($result_set_types as $p)
		{
			$tableau_types[$p->getId()] = $p->getCode();
		}
		
		
		
		// type congés
		$id_type_conge = new Zend_Form_Element_Select('id_type_conge');
		$id_type_conge->addMultiOptions($tableau_types);
		$id_type_conge->setRequired(true);
		$id_type_conge->addFilter('StripTags');
		$id_type_conge->addFilter('StringTrim');
		$id_type_conge->addValidator('NotEmpty');
		$id_type_conge->removeDecorator('DtDdWrapper');
		$id_type_conge->removeDecorator('HtmlTag');
		$id_type_conge->removeDecorator('Label'); 
		//création d'un élément submit pour envoyer le formulaire
		$submit = new Zend_Form_Element_Submit('submit');
		//définit l'attribut "id" de l'élément submit
		$submit->removeDecorator('DtDdWrapper');
		$submit->removeDecorator('HtmlTag');
		$submit->removeDecorator('Label');

		//ajout des éléments au formulaire
		$this->addElements(array(
			$id,
			$id_personne,
			$date_debut,$mi_debut_journee,
			$date_fin,$mi_fin_journee,
			$annee_reference,
			$ferme,
			$id_type_conge,$submit));
	}
}
