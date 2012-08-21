<?php
    class TestController extends Zend_Controller_Action
    {
        public function indexAction()
        {
            $form = new ZendX_JQuery_Form();
 
            $date = new ZendX_JQuery_Form_Element_DatePicker('date', array('label' => 'date'));
            $date->setJQueryParam('dateFormat', 'dd-mm-yy');
            $form->addElement($date);
 
            $this->view->form = $form;
        }
    }