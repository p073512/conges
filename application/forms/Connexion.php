<?php
class Default_Form_Connexion extends Zend_Form
{
	public function  __construct($options = null) {
		parent::__construct($options);
		
		//donne un nom à notre formulaire
		$this->setName('connexion');
		$this->setMethod('post');
		$this->removeDecorator('DtDdWrapper');
		$this->removeDecorator('HtmlTag');
		$this->removeDecorator('Label');
		
		
		$profil = new Zend_Form_Element_Select('profil');
		$profil->setMultiOptions(array('equipe' => 'Equipe','csm' => 'CSM','admin' => 'Admin'));
		$profil->setRequired(true);
		$profil->addFilter('StripTags');
		$profil->addFilter('StringTrim');
		$profil->addValidator('NotEmpty');
		$profil->removeDecorator('DtDdWrapper');
		$profil->removeDecorator('HtmlTag');
		$profil->removeDecorator('Label');
		
		
		$password = new Zend_Form_Element_Password('mot_passe');
		$password->setRequired(true);
		$password->addFilter('StripTags');
		$password->addFilter('StringTrim');
		$password->addValidator('NotEmpty');
		$password->removeDecorator('DtDdWrapper');
		$password->removeDecorator('HtmlTag');
		$password->removeDecorator('Label');
		
		$Connexion = new Zend_Form_Element_Submit('Connexion');
		$Connexion->removeDecorator('DtDdWrapper');
		$Connexion->removeDecorator('HtmlTag');
		$Connexion->removeDecorator('Label');
		//$submit->style = array('float: left');

		//ajout des éléments au formulaire
		$this->addElements(array($profil, $password, $Connexion));
	}
}