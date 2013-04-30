<?php
#region MBA
// application/forms/Personne.php
 
class Default_Form_PersonneFr extends Default_Form_BasePersonne
{
	
	
	public function init()
	{ 
        parent::init();
        
		//$this->setName('createPersonne');
		//$this->setMethod('post');
 /*
  * Champ en plus iModalité et iEntité il suffit de les créé avec createElement.
  */
     $iModalite = $this->createElement('select','modalite',array(
		    'label' => 'Modalités',
            'name' => 'modalite'
		    
     ));
    
      $iEntite = $this->createElement('select', 'entite', array(
		    'label' =>'Entités',
		    'name' => 'entite',
     ));
	
	
	$this->addElements(array($this->getiNom(),
	 $this->getiPrenom(),
	 $iEntite,
	 $iModalite,
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