<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Form_Personne extends Zend_Form
{
	//l'initialisation et la configuration des éléments de notre formulaire
	//se trouveront dans le constructeur de la classe,
	//de cette façon tous les éléments de notre formulaire seront créés lors
	//de l'instaniation d'un objet de type Default_Form_Users
	public function  __construct($options = null) {
		parent::__construct($options);

		//donne un nom à notre formulaire
		$this->setName('personne');
		$this->removeDecorator('DtDdWrapper');
		$this->removeDecorator('HtmlTag');
		$this->removeDecorator('Label');
		//création d'un élément input de type hidden
		$id = new Zend_Form_Element_Hidden('id');

		// champs nom et prenom 
		$nom_pr = new Zend_Form_Element_Text('nom_pr');
		$nom_pr->setRequired(true);
		$nom_pr->addFilter('StripTags');
		$nom_pr->addFilter('StringTrim');
		$nom_pr->addValidator('NotEmpty');
		$nom_pr->removeDecorator('DtDdWrapper');
		$nom_pr->removeDecorator('HtmlTag');
		$nom_pr->removeDecorator('Label');
		
		$prenom_pr = new Zend_Form_Element_Text('prenom_pr');
		$prenom_pr->setRequired(true);
		$prenom_pr->addFilter('StripTags');
		$prenom_pr->addFilter('StringTrim');
		$prenom_pr->addValidator('NotEmpty');
		$prenom_pr->removeDecorator('DtDdWrapper');
		$prenom_pr->removeDecorator('HtmlTag');
		$prenom_pr->removeDecorator('Label');
		
		// dates entree
		$date_entree_pr = new ZendX_JQuery_Form_Element_DatePicker('date_entree_pr');
		$date_entree_pr->setJQueryParam('dateFormat', 'yy-mm-dd');
		$date_entree_pr->setRequired(true);
		$date_entree_pr->addFilter('StripTags');
		$date_entree_pr->addFilter('StringTrim');
		$date_entree_pr->addValidator('NotEmpty');
		$date_entree_pr->removeDecorator('DtDdWrapper');
		$date_entree_pr->removeDecorator('HtmlTag');
		$date_entree_pr->removeDecorator('Label');

       //liste muli-selection entites
		$entite = new Default_Model_Entite();
		$result_set_entites = $entite->fetchAll($str=array());
		$tableau_entites = array();
		foreach($result_set_entites as $p)
		{
			$tableau_entites [$p->getId()] = $p->getLibelle();
		}
		$id_entite_pr= new Zend_Form_Element_Select('id_entite_pr');
		$id_entite_pr->addMultiOptions($tableau_entites);
		$id_entite_pr->removeDecorator('DtDdWrapper');
		$id_entite_pr->removeDecorator('HtmlTag');
		$id_entite_pr->removeDecorator('Label');
		
		
		

		// liste muli-selection Poles
		$pole = new Default_Model_Pole();
		$result_set_poles = $pole->fetchAll($str=array());
		$tableau_poles = array();
		foreach($result_set_poles as $p)
		{
			$tableau_poles [$p->getId()] = $p->getLibelle();
		}
		$id_pole_pr= new Zend_Form_Element_Select('id_pole_pr');
		$id_pole_pr->addMultiOptions($tableau_poles);
		$id_pole_pr->removeDecorator('DtDdWrapper');
		$id_pole_pr->removeDecorator('HtmlTag');
		$id_pole_pr->removeDecorator('Label');
		
		// muli-selection Fonctions
		$fonction = new Default_Model_Fonction();
		$result_set_fonctions = $fonction->fetchAll($str=array());
		$tableau_fonctions = array();
		foreach($result_set_fonctions as $p)
		{
			$tableau_fonctions [$p->getId()] = $p->getLibelle();
		}
		$id_fonction_pr= new Zend_Form_Element_Select('id_fonction_pr');
		$id_fonction_pr->addMultiOptions($tableau_fonctions);
		$id_fonction_pr->removeDecorator('DtDdWrapper');
		$id_fonction_pr->removeDecorator('HtmlTag');
		$id_fonction_pr->removeDecorator('Label');
		
		// muli-selection Modalites
		$modalite = new Default_Model_Modalite();
		$result_set_modalites = $modalite->fetchAll($str=array());
		$tableau_modalites = array();
		foreach($result_set_modalites as $p)
		{
			$tableau_modalites [$p->getId()] = $p->getLibelle();
		}
		$id_modalite_pr= new Zend_Form_Element_Select('id_modalite_pr');
		$id_modalite_pr->addMultiOptions($tableau_modalites);
		$id_modalite_pr->removeDecorator('DtDdWrapper');
		$id_modalite_pr->removeDecorator('HtmlTag');
		$id_modalite_pr->removeDecorator('Label');
		
		#region MBA 
		//
		
		// muli-selection Centre service
		$id_cservice_pr= new Zend_Form_Element_Select('centre_service_pr');
		$id_cservice_pr->addMultiOptions(array(0=>0,1=>1));
		//list FO/BO désactivée pas moyen de la supprimer sans impacter l'affichage des éléments du formulaire.
		$id_cservice_pr->setAttrib('disabled', true);
		//$id_cservice_pr->setOptions(array('onChange' => 'submit()'));
		
		
		$pourcent_pr = new Zend_Form_Element_Text('$pourcent_pr');
		$pourcent_pr->setValue('100');
		$pourcent_pr->setRequired(true);
		$pourcent_pr->addFilter('StripTags');
		$pourcent_pr->addFilter('StringTrim');
		$pourcent_pr->addValidator('NotEmpty');
		$pourcent_pr->addValidator('Between', false, array('min' => 0, 'max' => 100,'messages'=>'Pourcentage : Format incorrect, pas d\'enregistrement (Entre 0 et 100) '));
		$pourcent_pr->addValidator('digits',false,array('messages'=> 'Pourcentage : Format incorrect, pas d\'enregistrement (Uniquement valeur numeriques autorisees)'));
		$pourcent_pr->removeDecorator('DtDdWrapper');
		$pourcent_pr->removeDecorator('HtmlTag');
		$pourcent_pr->removeDecorator('Label');
		
		#endregion MBA
		
		// stage 
		$stage_pr = new Zend_Form_Element_Checkbox('stage_pr');
		$stage_pr->removeDecorator('DtDdWrapper');
		$stage_pr->removeDecorator('HtmlTag');
		$stage_pr->removeDecorator('Label');

		// bouton de creation
		$submit_pr = new Zend_Form_Element_Submit('submit_pr');
		$submit_pr->removeDecorator('DtDdWrapper');
		$submit_pr->removeDecorator('HtmlTag');
		$submit_pr->removeDecorator('Label');
		//$this-> addDisplayGroup ( array ('nom_pr', 'prenom_pr'), 'login');
		//ajout des éléments au formulaire
		$this->addElements(array(
		$id,
		$nom_pr,
		$prenom_pr,
		$date_entree_pr,
		$id_entite_pr,
		$id_pole_pr,
		$id_fonction_pr,
		$id_cservice_pr,
		$id_modalite_pr,
		$pourcent_pr,
		$stage_pr,
		$submit_pr));
	
	}
}
