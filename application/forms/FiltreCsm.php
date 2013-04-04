<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Form_FiltreCsm extends Zend_Form
{
	//l'initialisation et la configuration des éléments de notre formulaire
	//se trouveront dans le constructeur de la classe,
	//de cette façon tous les éléments de notre formulaire seront créés lors
	
	public function  __construct($options = null) {
		parent::__construct($options);

		//donne un nom à notre formulaire
		$this->setName('filtrecsm');
		//création d'un élément input de type hidden
		$id = new Zend_Form_Element_Hidden('id');

		//création de la liste muli-selection entites



		//création de la liste muli-selection Poles
		$pole = new Default_Model_Pole();
		$result_set_poles = $pole->fetchAll($str=array());
		$tableau_poles = array();
		foreach($result_set_poles as $p)
		{
			$tableau_poles [$p->getId()] = $p->getLibelle();
		}

		$id_pole= new Zend_Form_Element_Select('id_pole');
		$id_pole->addMultiOptions($tableau_poles);
		$id_pole->setLabel('Poles : ');
		
		//création de la liste muli-selection Fonctions
		$fonction = new Default_Model_Fonction();
		$result_set_fonctions = $fonction->fetchAll($str=array());
		$tableau_fonctions = array();
		foreach($result_set_fonctions as $p)
		{
			$tableau_fonctions [$p->getId()] = $p->getLibelle();
		}

		$id_fonction= new Zend_Form_Element_Select('id_fonction');
		$id_fonction->addMultiOptions($tableau_fonctions);
		$id_fonction->setLabel('Fonctions : ');



		//création d'un élément submit pour envoyer le formulaire
		$submit = new Zend_Form_Element_Submit('submit');
		//définit l'attribut "id" de l'élément submit
		$submit->setAttrib('id', 'submitBt');
		
		
		
		//ajout des éléments au formulaire
		$this->addElements(array(
		$id,
		$id_pole,
		$id_fonction,
		$submit));
	}
}
