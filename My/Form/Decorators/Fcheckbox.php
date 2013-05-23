<?php

class My_Form_Decorators_Fcheckbox extends Zend_Form_Decorator_Abstract 
{
	

   protected $_format = '
                         <input class="checkbox" type="checkbox" id="%s" css:id="%s" name="%s" value="0"  />
                        <label class="checkbox"><span class="metro-checkbox">%s</span></label> ';
   protected $_error = '<span class="help-inline" id="inputError" for="%s">%s</span>';
	
	
	
	
	public function render($content)
	{
		$element =  $this->getElement();
		$name = htmlentities($element->getFullyQualifiedName());
		$label = htmlentities($element->getLabel()). "?";
		
	    $value = $element->getvalue();
	  
		if($element->isChecked())
		{
			$element->setChecked(true);
		}	
		else
		{
			$element->setChecked(false);
		}
		
		  $element->setChecked(true);
     	
		$id = htmlentities($element->getId());
		$separateur = "<br/>";
		
		if($element->isRequired())
		{
			
			$label .= " * ";
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
		    $markup = "<div class='control-group error'>". sprintf($this->_format,$id,$css_id,$name,$label) .
		          sprintf($this->_error,"inputError",$error)."</div>";
		          return $markup.$separateur;
		}
		
		$markup = sprintf($this->_format,$id,$css_id,$name,$label).sprintf($this->_error,"",$error);
		
		return $markup.$separateur ;
	}
	
	
	
	
	
	
	
	
}