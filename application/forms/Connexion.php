<?php
class Default_Form_Connexion extends Zend_Form
{
	public function  __construct($options = null) {
		parent::__construct($options);
		
		//donne un nom à notre formulaire
		$this->setName('connexion');
		$this->setMethod('post');
		
		$profil = new Zend_Form_Element_Select('profil');
		$profil->setMultiOptions(array('equipe' => 'Equipe','csm' => 'CSM','admin' => 'Admin'));
		$profil->setLabel('Profil : ');
		//$profil->setValue('admin');
		$profil->setRequired(true);
		$profil->addFilter('StripTags');
		$profil->addFilter('StringTrim');
		$profil->addValidator('NotEmpty');
		
		$password = new Zend_Form_Element_Password('mot_passe');
		$password->setLabel('Mot de passe : ');
		$password->setRequired(true);
		$password->addFilter('StripTags');
		$password->addFilter('StringTrim');
		$password->addValidator('NotEmpty');

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Connexion');
		//$submit->style = array('float: left');

		//ajout des éléments au formulaire
		$this->addElements(array($profil, $password, $submit));
	}
}