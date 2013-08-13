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
  * Champ en plus iModalité et iEntié il suffit de les créer avec createElement.
  */
     $iModalite = $this->createElement('select','modalite',array(
		    'label' => 'Modalite',
            'name' => 'modalite'
		    
     ));
    
      $iEntite = $this->createElement('select', 'entite', array(
		    'label' =>'Entite',
		    'name' => 'entite',
     ));
	
	
	$this->addElements(array($this->getiNom(),
	 $this->getiPrenom(),
	 $iEntite,
	 $iModalite,
	 $this->getiFonction(),
	 $this->getiPole(),
	 $this->getiDateEntree(),
	 $this->getiDateDebut(),
	 $this->getiDateFin(),
	 $this->getiPourcentage(),	
	 $this->getiStage(),
	 $this->getiSubmit())); 
	 
	 
	
	} 
	
	
}
#endregion MBA