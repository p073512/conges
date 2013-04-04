<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Form_Login extends Zend_Form
{
	//l'initialisation et la configuration des éléments de notre formulaire
	//se trouveront dans le constructeur de la classe,
	//de cette façon tous les éléments de notre formulaire seront créés lors
	//de l'instaniation d'un objet de type Default_Form_Profil
	public function  __construct($options = null) {
		parent::__construct($options);

		//donne un nom à notre formulaire
		$this->setName('login');
		//$this->setMethod('post');
		//création d'un élément input de type hidden







		//création d'un élément de input de type text
		$profil = new Default_Model_Profil();
		$result_set_profils = $profil->fetchAll($str=array());
		$tableau_profils = array();
		foreach($result_set_profils as $p)
		{
			$tableau_profils [$p->getId()] = $p->getLogin();
		}

		$login= new Zend_Form_Element_Select('login');
		$login->addMultiOptions($tableau_profils);
		$login->setLabel('Profil : ');
		//indique que ce champs est requis et devra contenir une valeur
		$login->setRequired(true);
		//un filtre va effectuer des traitements sur la valeur de l'élément concerné
		//StripTags a le même effet que la fonction PHP strip_tags(),
		//supprime les balises XHTML
		$login->addFilter('StripTags');
		//StringTrim a le même effet que la fonction PHP trim(),
		//supprime les espaces inutiles en début et fin de String
		$login->addFilter('StringTrim');
		//un validateur est une condition sur l'élément qui si elle n'est pas respectée,
		//annule le traitement
		//NotEmpty indique que le champs ne pourra pas être vide
		$login->addValidator('NotEmpty');



		//création d'un élément input de type password
		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('Password: ');
		$password->setRequired(true);
		$password->addFilter('StripTags');
		$password->addFilter('StringTrim');
		$password->addValidator('NotEmpty');

		//création d'un élément submit pour envoyer le formulaire
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Connect');
		$submit->style = array('float: right');

		//ajout des éléments au formulaire
		$this->addElements(array( $login, $password, $submit));
	}
}