<?php
#region MBA
// application/forms/TPersonne.php
 
class Default_Form_PersonneMa extends Default_Form_BasePersonne
{
	public function init()
	{ 
        parent::init();
        
        // si on veut changer le nom ou la méthode du form appliquez ces méthodes.
		//$this->setName('createPersonne');
		//$this->setMethod('post');
 
  
	
	$this->addElements(array($this->getiNom(),
	                         $this->getiPrenom(),
	                         $this->getiFonction(),
	                         $this->getiPole(),
	                         $this->getiDateDebut(),
	                         $this->getiDateEntree(),
	                         $this->getiPourcentage(),
	                         $this->getiStage(),
	                         $this->getiSubmit())); 
	 
	} 
	
	
}
#endregion MBA