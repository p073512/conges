<?php 
class My_Form_Decorators_Ftextinput extends Zend_Form_Decorator_Abstract 
{
	
	protected $_label = '<label for="%s">%s : </label>';
	protected $_format = '   <input type="text" id="%s" name="%s" placeholder="%s">';
	protected $_error = '<span class="help-inline" id="inputError" for="%s">%s</span>';
	
	
	public function render($content)
	{
		$element = $this->getElement();
		$name = htmlentities($element->getFullyQualifiedName());
		$label = htmlentities($element->getLabel()). ":";
		$id = htmlentities($element->getId());
		$placeholder = htmlentities($element->getAttrib('placeholder'));
		$separateur = "<br/>";
		if($element->isRequired())
		{
			
			$label .= " * ";
		}
		
		
		
		$error = $element->getDescription()."</br>";
		 
		
		if($element->hasErrors())
		{
			
			$errors = $element->getErrors();
			
			foreach ($errors as $k=>$v)
			{
					$error .= $v."</br>";
				
			}
			$id = "inputError";
		    $markup = "<div class='control-group error'>".sprintf($label,"inputError",$label)."<br/>".sprintf($this->_format,$id,$name,$placeholder).
		          sprintf($this->_error,"inputError",$error)."</div>";
		          return $markup.$separateur;
		}
		
		$markup = sprintf($this->_label,"",$label).sprintf($this->_format,$id,$name,$placeholder).sprintf($this->_error,"",$error);
		
		return $markup.$separateur ;
	}
	
	
	
	
	
	
	
	
}