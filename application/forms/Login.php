<?php
class Default_Form_Login extends Zend_Form
{
	public function init()
	{
		$this->setName('login');
		$this->setMethod('post');

		$login = new Zend_Form_Element_Text('login');
		$login->setLabel('Login');
		$login->setRequired(true);
		$login->addFilter('StripTags');
		$login->addFilter('StringTrim');
		$this->addElement($login);

		$password = new Zend_Form_Element_Password('password');
		$password->setLabel('Password');
		$password->setRequired(true);
		$password->addFilter('StripTags');
		$password->addFilter('StringTrim');
		$this->addElement($password);

		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Connect');
		$submit->style = array('float: right');
		$this->addElement($submit);
	}
}