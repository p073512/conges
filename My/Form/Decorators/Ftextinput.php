<?php 
class My_Form_Decorators_Ftextinput extends Zend_Form_Decorator_Abstract 
{
	
	protected $_label = '<label for="%s">%s</label>';
	protected $_format = '   <input type="text" id="%s" css:id="%s" name="%s" placeholder="%s" value="%s">';
	protected $_error = '<span class="help-inline" id="inputError" for="%s">%s</span>';
	
	
	public function render($content)
	{
		$element = $this->getElement();
		$name = htmlentities($element->getFullyQualifiedName());
		$label = htmlentities($element->getLabel()). ":";
		$value = htmlentities($element->getValue());
		$id = htmlentities($element->getId());
		$placeholder = htmlentities($element->getAttrib('placeholder'));
		$separateur = "<br/>";
		if($element->isRequired())
		{
			
			$label .= " * ";
		}
		
		if($element instanceof ZendX_JQuery_Form_Element_DatePicker)
		{
			$error = 'this is a datepicker';
		}
		
		
		$error = $element->getDescription()."</br>";
		 $css_id="";
		
		if($element->hasErrors())
		{
			
			$errors = $element->getErrors();
			
			foreach ($errors as $k=>$v)
			{
					$error .= $v."</br>";
				
			}
			$css_id = "inputError";
		    $markup = "<div class='control-group error'>".sprintf($label,"inputError",$label)."<br/>".sprintf($this->_format,$id,$css_id,$name,$placeholder,$value).
		          sprintf($this->_error,"inputError",$error)."</div>";
		          return $markup.$separateur;
		}
		
		$markup = sprintf($this->_label,"",$label).sprintf($this->_format,$id,$css_id,$name,$placeholder,$value).sprintf($this->_error,"",$error);
		
		return $markup.$separateur ;
	}
	
	
	
	
	
	
	
	
}