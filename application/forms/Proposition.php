<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Form_Proposition extends Zend_Form
{
	//l'initialisation et la configuration des éléments de notre formulaire
	//se trouveront dans le constructeur de la classe,
	//de cette façon tous les éléments de notre formulaire seront créés lors
	//de l'instaniation d'un objet de type Default_Form_Users
	public function  __construct($options = null) {
		parent::__construct($options);

		//donne un nom à notre formulaire
		$this->setName('proposition');
		$this->removeDecorator('DtDdWrapper');
		$this->removeDecorator('HtmlTag');
		$this->removeDecorator('Label');
		//création d'un élément input de type hidden
		$id = new Zend_Form_Element_Hidden('id');

		//création de la liste muli-selection
		$personne = new Default_Model_Personne();
		$result_set_personnes = $personne->fetchAll('centre_service =1');
		$tableau_personnes = array();
		foreach($result_set_personnes as $p)
		{
			$tableau_personnes[$p->getId()] = $p->getNom()."  ".$p->getPrenom();
		}

		$id_personne_pro= new Zend_Form_Element_Select('id_personne_pro');
		$id_personne_pro->addMultiOptions($tableau_personnes);
		$id_personne_pro->removeDecorator('DtDdWrapper');
		$id_personne_pro->removeDecorator('HtmlTag');
		$id_personne_pro->removeDecorator('Label');




		// dates
		$date_debut_pro = new ZendX_JQuery_Form_Element_DatePicker('date_debut_pro');
		$date_debut_pro->setJQueryParam('dateFormat', 'yy-mm-dd');
		
		$date_debut_pro->setRequired(true);
		$date_debut_pro->addFilter('StripTags');
		$date_debut_pro->addFilter('StringTrim');
		$date_debut_pro->addValidator('NotEmpty');
		$date_debut_pro->removeDecorator('DtDdWrapper');
		$date_debut_pro->removeDecorator('HtmlTag');
		$date_debut_pro->removeDecorator('Label');
		
		
		$date_fin_pro = new ZendX_JQuery_Form_Element_DatePicker('date_fin_pro');
		$date_fin_pro->setJQueryParam('dateFormat', 'yy-mm-dd');
		
		$date_fin_pro->setRequired(true);
		$date_fin_pro->addFilter('StripTags');
		$date_fin_pro->addFilter('StringTrim');
		$date_fin_pro->addValidator('NotEmpty');
		$date_fin_pro->removeDecorator('DtDdWrapper');
		$date_fin_pro->removeDecorator('HtmlTag');
		$date_fin_pro->removeDecorator('Label');
		// checkbox
		$mi_debut_journee_pro = new Zend_Form_Element_Checkbox('mi_debut_journee_pro');
		$mi_debut_journee_pro->removeDecorator('DtDdWrapper');
		$mi_debut_journee_pro->removeDecorator('HtmlTag');
		$mi_debut_journee_pro->removeDecorator('Label');

		$mi_fin_journee_pro = new Zend_Form_Element_Checkbox('mi_fin_journee_pro');
		$mi_fin_journee_pro->removeDecorator('DtDdWrapper');
		$mi_fin_journee_pro->removeDecorator('HtmlTag');
		$mi_fin_journee_pro->removeDecorator('Label');

		//création d'un élément submit pour envoyer le formulaire
		$submit_pro = new Zend_Form_Element_Submit('submit_pro');
		$submit_pro->removeDecorator('DtDdWrapper');
		$submit_pro->removeDecorator('HtmlTag');
		$submit_pro->removeDecorator('Label');
		
		
		
		
		//ajout des éléments au formulaire
		$this->addElements(array(
		$id,
		$id_personne_pro,
		$date_debut_pro,$mi_debut_journee_pro,
		$date_fin_pro,$mi_fin_journee_pro,
		$submit_pro));
	}
}
