<?php
//"Default" correspond au namespace que nous avons défini dans le bootstrap
class Default_Form_Users extends Zend_Form
{
	//l'initialisation et la configuration des éléments de notre formulaire
	//se trouveront dans le constructeur de la classe,
	//de cette façon tous les éléments de notre formulaire seront créés lors
	//de l'instaniation d'un objet de type Default_Form_Users
	public function  __construct($options = null) {
		parent::__construct($options);

		//donne un nom à notre formulaire
		$this->setName('users');

		//création d'un élément input de type hidden
		$id = new Zend_Form_Element_Hidden('id');

		//création d'un élément de input de type text
		$firstname = new Zend_Form_Element_Text('firstname');
		//indique le label à utiliser pour l'élément
		$firstname->setLabel('First name: ');
		//indique que ce champs est requis et devra contenir une valeur
		$firstname->setRequired(true);
		//un filtre va effectuer des traitements sur la valeur de l'élément concerné
		//StripTags a le même effet que la fonction PHP strip_tags(),
		//supprime les balises XHTML
		$firstname->addFilter('StripTags');
		//StringTrim a le même effet que la fonction PHP trim(),
		//supprime les espaces inutiles en début et fin de String
		$firstname->addFilter('StringTrim');
		//un validateur est une condition sur l'élément qui si elle n'est pas respectée,
		//annule le traitement
		//NotEmpty indique que le champs ne pourra pas être vide
		$firstname->addValidator('NotEmpty');


		$lastname = new Zend_Form_Element_Text('lastname');
		$lastname->setLabel('Last name: ');
		$lastname->setRequired(true);
		$lastname->addFilter('StripTags');
		$lastname->addFilter('StringTrim');
		$lastname->addValidator('NotEmpty');

		$mail = new Zend_Form_Element_Text('mail');
		$mail->setLabel('Mail: ');
		$mail->setRequired(true);
		$mail->addFilter('StripTags');
		$mail->addFilter('StringTrim');
		$mail->addValidator('NotEmpty');
		//ce validateur vérifie que la valeur de l'élément correspond a une adresse mail
		$mail->addValidator('EmailAddress');

		//création d'un élément input de type password
		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('Password: ');
		$password->setRequired(true);
		$password->addFilter('StripTags');
		$password->addFilter('StringTrim');
		$password->addValidator('NotEmpty');

		//création d'un élément submit pour envoyer le formulaire
		$submit = new Zend_Form_Element_Submit('submit');
		//définit l'attribut "id" de l'élément submit
		$submit->setAttrib('id', 'submitBt');

		//ajout des éléments au formulaire
		$this->addElements(array($id, $firstname, $lastname, $mail, $password, $submit));
	}
}